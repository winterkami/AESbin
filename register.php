<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user_database";

try {
    // 连接到MySQL服务器（不指定数据库）
    $conn = new mysqli($servername, $username, $password);
    if ($conn->connect_error) {
        throw new Exception($conn->connect_error);
    }

    // 创建数据库（如果不存在）
    $sql_create_db = "CREATE DATABASE IF NOT EXISTS $dbname";
    if (!$conn->query($sql_create_db)) {
        throw new Exception("Database creation failed: " . $conn->error);
    }

    // 选择数据库
    $conn->select_db($dbname);

    // 创建用户表（如果不存在）
    $sql_create_table = "
    CREATE TABLE IF NOT EXISTS user_account (
        id INT PRIMARY KEY AUTO_INCREMENT,
        username VARCHAR(50) UNIQUE NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL
    )";
    if (!$conn->query($sql_create_table)) {
        throw new Exception("Table creation failed: " . $conn->error);
    }

    // 处理注册请求
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        // 密码哈希处理
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // 插入用户信息
        $sql_insert = "INSERT INTO user_account (username, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql_insert);
        $stmt->bind_param('sss', $username, $email, $hashed_password);

        if ($stmt->execute()) {
            echo "Registration successful!";
            header("Location: login.html");
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }

    $conn->close();

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
