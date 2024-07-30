<?php
$servername = "localhost";
$username = "root";
$password = "topza1997";
$dbname = "nodemcu_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$temperature = $_POST['temperature'];
$humidity = $_POST['humidity'];

$stmt = $conn->prepare("INSERT INTO sensor_data (temperature, humidity) VALUES (?, ?)");
$stmt->bind_param("dd", $temperature, $humidity);
$stmt->execute();
$stmt->close();

$conn->close();
?>
