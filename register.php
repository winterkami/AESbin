<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get form data
    $email = htmlspecialchars(trim($_POST['email']));
    $password = htmlspecialchars(trim($_POST['password']));
    $confirm_password = htmlspecialchars(trim($_POST['confirm_password']));

    // Check if passwords match
    if ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match! Please try again.');</script>";
    } else {
        // Validate password strength (at least 8 characters, with letters, numbers, and special characters)
        if (!preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,}$/', $password)) {
            echo "<script>alert('Password must include at least one uppercase letter, one lowercase letter, one number, and one special character.');</script>";
        } else {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Connect to database
            $conn = new mysqli("localhost", "root", "", "db"); // Database name is `db`
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Ensure `user_account` table exists
            $conn->query("
            CREATE TABLE IF NOT EXISTS user_account (
                number INT NOT NULL AUTO_INCREMENT,
                id VARCHAR(255) NOT NULL UNIQUE, -- Email
                content TEXT NOT NULL,          -- Encrypted password
                password BOOLEAN NOT NULL,      -- Indicates if encrypted
                PRIMARY KEY (number)
            );
            ");

            // Check if email already exists
            $sql_check = "SELECT * FROM user_account WHERE id = ?";
            $stmt_check = $conn->prepare($sql_check);
            $stmt_check->bind_param("s", $email); // Assuming email is unique
            $stmt_check->execute();
            $result_check = $stmt_check->get_result();

            if ($result_check->num_rows > 0) {
                echo "<script>alert('Email already exists! Please try another.');</script>";
                $stmt_check->close();
            } else {
                $stmt_check->close();
                // Insert user data into `user_account` table
                $sql = "INSERT INTO user_account (id, content, password) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $is_password_encrypted = true; // Indicates if encrypted
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
    </style>
</head>

<body>
    <form method="POST" action="">
        <h2>Register</h2>

        <label for="email">Email</label>
        <span class="hint">Enter a valid email address (e.g., example@example.com).</span>
        <input type="email" name="email" id="email" placeholder="Enter email" required>

        <label for="password">Password</label>
        <span class="hint">
            Password must include:
            <ul>
                <li>At least 8 characters</li>
                <li>At least one uppercase letter</li>
                <li>At least one lowercase letter</li>
                <li>At least one number</li>
                <li>At least one special character (e.g., @, #, $, etc.)</li>
            </ul>
        </span>
        <input type="password" name="password" id="password" placeholder="Enter a strong password" required>

        <label for="confirm_password">Confirm Password</label>
        <span class="hint">Re-enter the password to confirm it matches.</span>
        <input type="password" name="confirm_password" id="confirm_password" placeholder="Re-enter password" required>

        <button type="submit">Register</button>
    </form>
</body>

</html>
