<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // 获取表单数据
    $login = htmlspecialchars(trim($_POST['login'])); // 用户名或邮箱
    $password = htmlspecialchars(trim($_POST['password'])); // 用户输入的密码

    // 数据库配置
    $servername = "localhost";
    $username = "root";
    $db_password = ""; // 数据库密码
    $dbname = "db"; // 数据库名

    try {
        // 连接数据库
        $conn = new mysqli($servername, $username, $db_password, $dbname);
        if ($conn->connect_error) {
            throw new Exception("Database connection failed: " . $conn->connect_error);
        }

        // 确保表 `user_account` 存在
        $conn->query("
        CREATE TABLE IF NOT EXISTS user_account (
            number INT NOT NULL AUTO_INCREMENT,
            id VARCHAR(255) NOT NULL UNIQUE,
            content TEXT NOT NULL,
            password BOOLEAN NOT NULL,
            PRIMARY KEY (number)
        );
        ");

        // 查询用户名或邮箱是否存在（假设此处是 `user_account` 表中存储的用户信息）
        $sql = "SELECT * FROM user_account WHERE (id = ? OR content = ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $login, $login); // `id` 或 `content` 替换为用户名或邮箱字段
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            // 验证密码
            if (password_verify($password, $user['content'])) { // 假设 `content` 字段存储了加密后的密码
                session_start();
                $_SESSION['user_id'] = $user['number']; // 存储用户的主键 ID
                $_SESSION['username'] = $user['id']; // 假设 `id` 是用户名
                echo "<script>alert('Login successful!'); window.location.href='index.html';</script>";
            } else {
                echo "<script>alert('Invalid password. Please try again.');</script>";
            }
        } else {
            echo "<script>alert('No user found with that username or email.');</script>";
        }

        $stmt->close();
        $conn->close();
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            background-color: #f7f7f7;
        }

        form {
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            width: 300px;
            background-color: #fff;
        }

        form h2 {
            margin-bottom: 20px;
            text-align: center;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border: none;
            border-radius: 5px;
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        .hint {
            font-size: 0.8rem;
            color: #666;
            margin-bottom: 5px;
        }

        .register {
            margin-top: 10px;
            text-align: center;
            font-size: 0.9rem;
        }

        .register a {
            color: #007BFF;
            text-decoration: none;
        }

        .register a:hover {
            text-decoration: underline;
        }

        .error {
            color: red;
            font-size: 0.9rem;
        }
    </style>
</head>

<body>
    <form method="POST" action="">
        <h2>Login</h2>
        <label for="login">Username or Email</label>
        <span class="hint">Enter your registered username or email address.</span>
        <input type="text" name="login" id="login" placeholder="Username or Email" required>

        <label for="password">Password</label>
        <span class="hint">Enter your password (case-sensitive).</span>
        <input type="password" name="password" id="password" placeholder="Password" required>

        <button type="submit">Login</button>

        <div class="register">
            Don't have an account? <a href="register.php">Register here</a>
        </div>
    </form>
</body>

</html>