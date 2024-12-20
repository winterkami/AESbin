<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve form data
    $email = htmlspecialchars(trim($_POST['email'])); // email
    $password = htmlspecialchars(trim($_POST['password'])); // User's input password

    // Database configuration
    $servername = "localhost";
    $username = "root";
    $db_password = ""; // Database password
    $dbname = "db"; // Database name

    try {
        // Connect to the database
        $conn = new mysqli($servername, $username, $db_password, $dbname);
        if ($conn->connect_error) {
            throw new Exception("Database connection failed: " . $conn->connect_error);
        }

        // Ensure the `user_account` table exists
        $conn->query("
        CREATE TABLE IF NOT EXISTS user_account (
            number INT NOT NULL AUTO_INCREMENT,
            id VARCHAR(255) NOT NULL UNIQUE,  -- email
            content TEXT NOT NULL,           -- hashed password
            password BOOLEAN NOT NULL,       -- indicates if it's hashed
            PRIMARY KEY (number)
        );
        ");

        // Query to check if email exists
        $sql = "SELECT * FROM user_account WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email); // Bind email to the query
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            // Verify the password
            if (password_verify($password, $user['content'])) {
                session_start();
                $_SESSION['user_id'] = $user['number']; // Store user's primary key
                $_SESSION['email'] = $user['id'];       // Store email
                echo "<script>alert('Login successful!'); window.location.href='index.html';</script>";
            } else {
                echo "<script>alert('Invalid password. Please try again.');</script>";
            }
        } else {
            echo "<script>alert('No user found with that email.');</script>";
        }

        // Close statement and connection
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

        input[type="email"],
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
    </style>
</head>

<body>
    <form method="POST" action="">
        <h2>Login</h2>
        <label for="email">Email</label>
        <span class="hint">Enter your registered email address.</span>
        <input type="email" name="email" id="email" placeholder="Email" required>

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
