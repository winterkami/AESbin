<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // 获取表单数据
    $username = htmlspecialchars(trim($_POST['username']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = htmlspecialchars(trim($_POST['password']));
    $confirm_password = htmlspecialchars(trim($_POST['confirm_password']));

    // 验证密码是否一致
    if ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match! Please try again.');</script>";
    } else {
        // 验证密码强度（至少8个字符，包含字母、数字和特殊字符）
        if (!preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,}$/', $password)) {
            echo "<script>alert('Password must include at least one uppercase letter, one lowercase letter, one number, and one special character.');</script>";
        } else {
            // 加密密码
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // 连接数据库
            $conn = new mysqli("localhost", "root", "", "db"); // 数据库名为 `db`
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // 确保 `user_content` 表存在
            $conn->query("
            CREATE TABLE IF NOT EXISTS user_content (
                number INT NOT NULL AUTO_INCREMENT,
                id VARCHAR(255) NOT NULL UNIQUE, -- 用户名或邮箱
                content TEXT NOT NULL,          -- 加密密码
                password BOOLEAN NOT NULL,      -- 用于标识是否加密
                PRIMARY KEY (number)
            );
            ");

            // 检查用户名或邮箱是否已存在
            $sql_check = "SELECT * FROM user_content WHERE id = ?";
            $stmt_check = $conn->prepare($sql_check);
            $stmt_check->bind_param("s", $email); // 假设邮箱唯一
            $stmt_check->execute();
            $result_check = $stmt_check->get_result();

            if ($result_check->num_rows > 0) {
                echo "<script>alert('Email already exists! Please try another.');</script>";
                $stmt_check->close();
            } else {
                $stmt_check->close();
                // 插入用户数据到 `user_content` 表
                $sql = "INSERT INTO user_content (id, content, password) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $is_password_encrypted = true; // 标识是否加密
                $stmt->bind_param("ssi", $email, $hashed_password, $is_password_encrypted);

                if ($stmt->execute()) {
                    echo "<script>alert('Registration successful! You can now log in.'); window.location.href='login.php';</script>";
                } else {
                    echo "<script>alert('Error: " . $stmt->error . "');</script>";
                }

                $stmt->close();
            }

            $conn->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        form {
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            width: 300px;
            background-color: #f9f9f9;
        }
        form h2 {
            margin-bottom: 20px;
            text-align: center;
        }
        input[type="text"], input[type="email"], input[type="password"] {
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
    </style>
</head>
<body>
    <form method="POST" action="">
        <h2>Register</h2>
        <label for="username">Username</label>
        <span class="hint">Choose a unique username (3-20 characters).</span>
        <input type="text" name="username" id="username" placeholder="Enter username" required>
        
        <label for="email">Email</label>
        <span class="hint">Enter a valid email address (e.g., example@example.com).</span>
        <input type="email" name="email" id="email" placeholder="Enter email" required>
        
        <label for="password">Password</label>
        <span class="hint">Choose a strong password (8+ characters).</span>
        <input type="password" name="password" id="password" placeholder="Enter password" required>
        
        <label for="confirm_password">Confirm Password</label>
        <span class="hint">Re-enter the password to confirm.</span>
        <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm password" required>
        
        <button type="submit">Register</button>
    </form>
</body>
</html>
