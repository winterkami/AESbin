<?php
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

    // Display last submission
    $result = $mysqli->query("SELECT content FROM user_content ORDER BY id DESC LIMIT 1");
    $content = "";
    if ($result->num_rows > 0) {
        $content = $result->fetch_assoc()["content"];
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
    <a href="index.html">Submit New Content</a>
</body>

</html>