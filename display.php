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
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            /* ensures the box elements resize well */
            font-family: "Courier New", Courier, monospace, sans-serif;
        }

        body {
            display: flex;
            /* using flexbox for the page elements */
            flex-direction: column;
            min-height: 100vh;
        }

        /* NAV BAR STYLE BELOW -> logo and links*/
        .navbar {
            height: 100px;
            display: flex;

            align-items: center;
            justify-content: space-between;

            padding: 10px 30px;
            background-color: #bfb8bd;
            /* light pink color */
            color: black;
            position: relative;
        }

        .logo {
            flex: 0 0 auto;
            /* keep on left side */
            margin: 5px;
            margin-right: 5rem;
        }

        /* view settings for logo */

        .logo img {
            width: 105px;
            height: auto;
            display: block;

            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.4);
            padding: 15px, 20px;

            background-color: #bfb8bd;
            border: 2px solid white;

            transform: scale(1.1);
            /* logo zoom */
        }

        .nav-links {
            flex: 1;
            list-style: none;
            /* remove dots from list elements */
            display: flex;

            justify-content: space-evenly;
            align-items: center;

            gap: 30px;
            /* space between items and space away from edges of screen */
            margin-right: 60rem;
        }

        .nav-links a {
            text-decoration: none;
            color: black;
            font-size: 1.5rem;

            font-weight: bold;
            font-variant: small-caps;
            transition: color 0.3s;
        }

        .nav-links a:hover {
            color: white;
        }

        /* style  for main page elements -> inner content box, text entry */

        .main-content {
            flex: 1;
            display: flex;

            justify-content: center;
            align-items: center;

            padding: 20px;
            border: 25px solid white;
            background-color: #bfb8bd;
            /* light pink */
        }

        /* inner content box for style -> paste main submission area  */

        .content-inner {
            width: 100%;
            max-width: 600px;
            padding: 20px;

            border: 10px solid #bfb8bd;
            border-radius: 15px;
            background-color: #4d5859;

            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .text-area-container {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;

            width: 100%;
            max-width: 600px;
            text-align: center;
        }

        /* responsive sizing for text box area */
        textarea {
            width: 100%;
            height: auto;

            min-height: 150px;
            padding: 10px;
            border: 2px solid #ccc;

            border-radius: 5px;
            font-size: 1rem;
            resize: both;
            box-sizing: border-box;
        }

        /* styles for the footer: password entry and submit button */
        .footer {
            display: flex;
            justify-content: center;
            align-items: center;

            padding: 20px;
            background-color: #bfb8bd;
            /* Footer brand color */
            color: #bfb8bd;
            gap: 10px;
        }

        .footer input[type="password"] {
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .footer button {
            padding: 5px 10px;
            background-color: #f39c12;
            /* Accent color */
            border: none;

            border-radius: 5px;
            color: #fff;
            cursor: pointer;
        }

        .footer button:hover {
            background-color: #363f40;
        }

        .footer button:disabled {
            background-color: #363f40;
        }

        /* responsive elements */
        @media (max-width: 600px) {
            .nav-links {
                flex-direction: column;
                gap: 10px;
            }

            .footer {
                flex-direction: column;
                gap: 5px;
            }
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
                <li><a href="#">Home</a></li>
                <li><a href="#">About</a></li>
                <li><a href="#">Recent</a></li>
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

    <!-- only display decryption zone if text is encrypted -->
    <?php if ($is_encrypted): ?>
        <form method="POST">
            <main class="main-content">
                <div class="content-inner">
                    <p>
                        Provide a password below to decrypt text.
                    </p>
                    <?php if ($decryption_failed): ?>
                        <p style="color:red;margin-top:10px;" class="error">Incorrect decryption password. Try again!</p>
                    <?php endif; ?>
                    <br />
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
        <form>
            <main class="main-content">
                <div class="content-inner">
                    <p>
                        Plaintext is available. No password required.
                    </p>
                    <br />
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