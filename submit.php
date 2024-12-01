<?php
// Enlai Li 261068637
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db";

try {
    $mysqli = new mysqli($servername, $username, $password);
    if ($mysqli->connect_error) {
        throw new Exception($mysqli->connect_error);
    }

    // create database
    $mysqli->query("CREATE DATABASE IF NOT EXISTS $dbname");
    // create table
    $mysqli->select_db($dbname);
    $mysqli->query("
    CREATE TABLE IF NOT EXISTS user_content (
            id VARCHAR(255) NOT NULL UNIQUE,
            content TEXT NOT NULL
        );
    ");

    // get content
    $content = $_POST['content'];
    // generate unique id that doesn't exist in the db
    $id = null;
    do {
        $id = bin2hex(random_bytes(6));
        $result = $mysqli->execute_query(
            "SELECT COUNT(*) as count FROM user_content 
            WHERE id = ?",
            [$id]
        );
        $count = $result->fetch_assoc()["count"];
    } while ($count > 0);
    // insert content
    $mysqli->execute_query(
        "INSERT INTO user_content (id, content) VALUES (?, ?)",
        [$id, $content]
    );
    // redirect to unique page
    header("Location: pastes/$id");
    exit();
} catch (Exception $e) {
    echo $e->getMessage();
} finally {
    $mysqli->close();
}
