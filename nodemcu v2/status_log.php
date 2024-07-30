<?php
$servername = "localhost";
$username = "root";
$password = "topza1997";
$dbname = "nodemcu_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$result = $conn->query("SELECT * FROM led_status ORDER BY timestamp DESC");
if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    echo "<tr><td>" . $row['timestamp'] . "</td><td>" . $row['status'] . "</td></tr>";
  }
} else {
  echo "<tr><td colspan='2'>No log available</td></tr>";
}

$conn->close();
?>
