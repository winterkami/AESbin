<?php
// Enlai Li 261068637

// db info
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
            number int NOT NULL AUTO_INCREMENT,
            id VARCHAR(255) NOT NULL UNIQUE,
            content TEXT NOT NULL,
            password BOOLEAN NOT NULL,
            visits INT NOT NULL DEFAULT 0,
            date DATE NOT NULL DEFAULT CURDATE(),
            PRIMARY KEY (number)
        );
    ");
} catch (Exception $e) {
    echo $e->getMessage();
} finally {
    $mysqli->close();
}
