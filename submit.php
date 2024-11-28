<?php
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
            id INT AUTO_INCREMENT PRIMARY KEY,
            content TEXT NOT NULL
        )
    ");
    // insert content
    $content = $_POST['content'];
    $mysqli->execute_query(
        "INSERT INTO user_content (content) VALUES (?)",
        [$content]
    );
} catch (Exception $e) {
    echo $e->getMessage();
} finally {
    $mysqli->close();
}
