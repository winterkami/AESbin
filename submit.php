<?php
// Enlai Li 261068637

// function for aes encryption
function encrypt($string, $password)
{
    $encryption_method = 'aes-256-cbc';
    // Generate a random initialization vector (IV)
    $iv_length = openssl_cipher_iv_length($encryption_method);
    $iv = random_bytes($iv_length);

    $key = hash('sha256', $password, true);
    $ciphertext = openssl_encrypt($string, $encryption_method, $key, OPENSSL_RAW_DATA, $iv);
    return base64_encode($iv . $ciphertext);
}


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
            id VARCHAR(255) NOT NULL UNIQUE,
            content TEXT NOT NULL,
            password BOOLEAN NOT NULL
        );
    ");

    // get inputs 
    $content = $_POST['content'];
    $password = $_POST['password'];
    // encrypt content with aes if password is provided
    if ($password) {
        $content_db = encrypt($content, $password);
        $password_db = true;
    }
    // content stays plaintext if no password
    else {
        $content_db = $content;
        $password_db = false;
    }

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
        "INSERT INTO user_content (id, content, password) VALUES (?, ?, ?)",
        [$id, $content_db, $password_db]
    );
    // redirect to unique page
    header("Location: pastes/$id");
    exit();
} catch (Exception $e) {
    echo $e->getMessage();
} finally {
    $mysqli->close();
}
