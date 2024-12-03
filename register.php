<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // 数据库连接
    $conn = new mysqli('localhost', 'root', '', 'user_database');

    if ($conn->connect_error) {
        die('Connection Failed: ' . $conn->connect_error);
    }

    // 密码哈希处理
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // 插入用户信息
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sss', $username, $email, $hashed_password);

    if ($stmt->execute()) {
        echo "Registration successful!";
        header("Location: login.html");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
