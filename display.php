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

    $key = hash('sha256', $password, true);
    return openssl_decrypt($ciphertext, $encryption_method, $key, OPENSSL_RAW_DATA, $iv);
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
    $mysqli->select_db($dbname);

    // Get ID from query string
    if (!isset($_GET["id"])) {
        throw new Exception("No ID");
    }
    $id = $_GET['id'];

    // Display submission
    $result = $mysqli->execute_query(
        "SELECT content FROM user_content WHERE id = ?",
        [$id]
    );
    $content = "";
    if ($result->num_rows > 0) {
        $content = $result->fetch_assoc()["content"];
    } else {
        throw new Exception("No Content with this ID");
    }
} catch (Exception $e) {
    echo $e->getMessage();
} finally {
    $mysqli->close();
}
?>
<html>

<body>
    <h1>Submitted Content</h1>
    <div style="border: 1px solid #ccc; padding: 20px; width: 50%; margin: auto;">
        <p><?php echo nl2br(htmlspecialchars($content)); ?></p>
    </div>
    <br>
    <a href="../index.html">Submit New Content</a>
</body>

</html>