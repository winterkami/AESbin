<?php
// Enlai Li 261068637

// make sure database exists
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

    // // Debug
    // $sort = "recent";
    // $page = 2;

    // get sorting method
    $sort = $_GET['sort'] ?? "recent";
    // get current page 
    $page = intval($_GET['page']) ?? 1;
    // get search
    $search = $_GET['search'] ?? "";

    // determine sorting method
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

    // determine from where to start 
    $items_per_page = 10;
    $start_from = ($page - 1) * $items_per_page;

    // get submissions in a particular order
    $result = $mysqli->execute_query(
        "SELECT date, id, content, visits 
        FROM user_content
        WHERE content LIKE ?
        ORDER BY $order_by 
        LIMIT ? OFFSET ?",
        ["%" . $search . "%", $items_per_page + 1, $start_from]
    );
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
