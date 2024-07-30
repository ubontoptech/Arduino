<?php
$servername = "localhost";
$username = "root";
$password = "topza1997";
$dbname = "nodemcu_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$status = isset($_GET['status']) ? $_GET['status'] : '';
if ($status == 'ON' || $status == 'OFF') {
  $stmt = $conn->prepare("INSERT INTO led_status (status) VALUES (?)");
  $stmt->bind_param("s", $status);
  $stmt->execute();
  $stmt->close();
}

$result = $conn->query("SELECT * FROM led_status ORDER BY timestamp DESC LIMIT 1");
$current_status = '';
if ($result->num_rows > 0) {
  $row = $result->fetch_assoc();
  $current_status = $row['status'];
}

$conn->close();
echo $current_status;
?>
