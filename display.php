<?php
// Enlai Li 261068637
/**
 * Return false if wrong password. Return the decrypted content otherwise.
 */
function decrypt($encrypted_string, $password)
{
    $encryption_method = 'aes-256-cbc';
    // decode the base64 from encrypt function
    $decoded = base64_decode($encrypted_string);

    $iv_length = openssl_cipher_iv_length($encryption_method);
    $iv = substr($decoded, 0, $iv_length);
    $ciphertext = substr($decoded, $iv_length);

    // return false if IV length incorrect 
    //to avoid displaying error message on the website
    if (strlen($iv) !== $iv_length) {
        return false;
    }

    $key = hash('sha256', $password, true);
    return openssl_decrypt($ciphertext, $encryption_method, $key, OPENSSL_RAW_DATA, $iv);
}
// make sure database exists
require_once "database_create.php";
// db info
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db";

// global var to track the content
$content = "";
// global var to track decryption success
$decryption_failed = false;
// global var to track if the content is encrypted
$is_encrypted = true;
// display content logic, for the GET method
if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["id"])) {
    try {
        $mysqli = new mysqli($servername, $username, $password);
        if ($mysqli->connect_error) {
            throw new Exception($mysqli->connect_error);
        }
        $mysqli->select_db($dbname);

        // Get ID from query string
        $id = $_GET['id'];
        // Increment visit count for recent page's popular tab
        $mysqli->execute_query(
            "UPDATE user_content SET visits = visits + 1  
            WHERE id = ?",
            [$id]
        );
        // Get submission from database
        $result = $mysqli->execute_query(
            "SELECT content, password FROM user_content 
            WHERE id = ?",
            [$id]
        );
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $content = $row["content"];
            if ($row["password"] == 1) {
                $is_encrypted = true;
            } else {
                $is_encrypted = false;
            }
        } else {
            throw new Exception("No Content with this ID");
        }
    } catch (Exception $e) {
        echo $e->getMessage();
    } finally {
        $mysqli->close();
    }
}
// decrypt content, for the POST method when pressing submit
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $encrypted_content = $_POST["content"];
    $password = $_POST["password"];
    // try to decrypt
    $content = $encrypted_content;
    if ($encrypted_content && $password) {
        $decrypted_content = decrypt($encrypted_content, $password);
        // if decryption is successful, display the plaintext
        // otherwise set failure flag
        if ($decrypted_content !== false) {
            $content = $decrypted_content;
            $is_encrypted = false;
        } else {
            $decryption_failed = true;
            $is_encrypted = true;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Aesbin Home</title>
    <link rel="stylesheet" href="../animation.css" />
    <link rel="stylesheet" href="../general_styles.css">
    <style>
        .footer button:disabled {
            background-color: grey;

            cursor: not-allowed;
        }
    </style>
</head>

<body>
    <header class="navbar">
        <div class="logo">
            <img src="../logo.PNG" />
        </div>
        <nav>
            <ul class="nav-links">
                <li><a href="../index.html">Home</a></li>
                <li><a href="../about.html">About</a></li>
                <li><a href="../recent_pastes_v2.html">Recent</a></li>
                <li><a href="../login.php">Login</a></li>
                <li><a href="../dashboard.html">Dashboard</a></li>


                <li>
                    <button id="logout-button" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: black; font-family: 'Courier New', Courier, monospace;
        font-weight: bold;">
                        Logout
                    </button>
                </li>
            </ul>

        </nav>
        <!--css animation for falling lettesr: a, e, s, b, i , n-->

        <div class="falling-letters">
            <span class="letter">A</span>
            <span class="letter">E</span>
            <span class="letter">S</span>
            <span class="letter">b</span>
            <span class="letter">i</span>
            <span class="letter">n</span>
        </div>
    </header>

    <div class="picture-box">

        <img class="picture-box" src="../nebula.jpg" alt="image of the horsehead nebula">

    </div>

    <!-- only display decryption zone if text is encrypted -->
    <?php if ($is_encrypted): ?>
        <form method="POST">
            <main class="main-content">
                <p style="font-family: 'Courier New', Courier, monospace; color: white;">
                    Provide a password below to decrypt text.
                </p>
                <div class="content-inner">
                    <?php if ($decryption_failed): ?>
                        <p style="color:red;margin-top:10px;" class="error">Incorrect decryption password. Try again!</p>
                    <?php endif; ?>
                    <div class="text-area-container">
                        <textarea name="content" rows="10" cols="50" required readonly><?php echo $content; ?></textarea>
                    </div>
                </div>
            </main>

            <footer class="footer">
                <div>
                    <label for="password">Password: </label>
                    <input
                        name="password"
                        type="password"
                        id="password"
                        placeholder="Enter password" />
                    <button type="submit">Decrypt</button>
                </div>
            </footer>
        </form>
        <!-- otherwise only display the text box -->
    <?php else: ?>
        <form method="GET">
            <main class="main-content">
                <p style="font-family: 'Courier New', Courier, monospace; color: white;">
                    Plaintext is available. No password required.
                </p>
                <div class="content-inner">
                    <div class="text-area-container">
                        <textarea name="content" rows="10" cols="50" required readonly><?php echo $content; ?></textarea>
                    </div>
                </div>
            </main>
            <footer class="footer">
                <div>
                    <label for="password">Password: </label>
                    <input
                        name="password"
                        type="password"
                        id="password"
                        placeholder="No password required" readonly />
                    <button type="submit" disabled>Decrypt</button>
                </div>
            </footer>
        </form>
    <?php endif; ?>
</body>

</html>