<?php
$servername = "localhost";
$username = "root";
$password = "topza1997";
$dbname = "nodemcu_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$result = $conn->query("SELECT * FROM sensor_data ORDER BY timestamp DESC LIMIT 10");
if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    echo "<tr><td>" . $row['timestamp'] . "</td><td>" . $row['temperature'] . "</td><td>" . $row['humidity'] . "</td></tr>";
  }
} else {
  echo "<tr><td colspan='3'>No sensor data available</td></tr>";
}

$conn->close();
?>
