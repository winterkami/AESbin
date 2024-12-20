<?php
// Enlai Li 261068637
session_start();

require_once "database_create.php";

// db info
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db";

// check if the request is correct
if ($_SERVER['REQUEST_METHOD'] !== 'GET' || !isset($_GET['sort']) || !isset($_GET['page'])) {
    http_response_code(400); // Bad Request
    exit();
}

try {
    $mysqli = new mysqli($servername, $username, $password);
    if ($mysqli->connect_error) {
        throw new Exception($mysqli->connect_error);
    }
    $mysqli->select_db($dbname);

    // get sorting method
    $sort = $_GET['sort'] ?? "recent";
    // get current page
    $page = intval($_GET['page']) ?? 1;
    // get search
    $search = $_GET['search'] ?? "";

    switch ($sort) {
        case 'popular':
            $order_by = "visits DESC, number DESC";
            break;
        case 'oldest':
            $order_by = "number ASC";
            break;
        case 'recent':
        default:
            $order_by = "number DESC";
            break;
    }

    $items_per_page = 10;
    $start_from = ($page - 1) * $items_per_page;

    $items_per_page_plus_one = $items_per_page + 1;

    $search_pattern = "%" . $search . "%";

    $is_logged_in = isset($_SESSION['user_id']);

    if ($is_logged_in) {

        $user_id = $_SESSION['user_id'];
        $query = "
            SELECT date, id, content, visits
            FROM user_content
            WHERE content LIKE CONCAT('USER:', ?, ';%')
            AND content LIKE ?
            ORDER BY $order_by
            LIMIT ? OFFSET ?
        ";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("ssii", $user_id, $search_pattern, $items_per_page_plus_one, $start_from);
    } else {

        $query = "
            SELECT date, id, content, visits
            FROM user_content
            WHERE content NOT LIKE 'USER:%;%'
            AND content LIKE ?
            ORDER BY $order_by
            LIMIT ? OFFSET ?
        ";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("sii", $search_pattern, $items_per_page_plus_one, $start_from);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $rows = $result->fetch_all();

    // check if next page exists
    if (sizeof($rows) <= $items_per_page) {
        $has_next_page = false;
    } else {
        $has_next_page = true;
    }
    $entries = array_slice($rows, 0, $items_per_page);

    echo json_encode([
        "entries" => $entries,
        "has_next_page" => $has_next_page
    ]);
} catch (Exception $e) {
    echo $e->getMessage();
} finally {
    $mysqli->close();
}
