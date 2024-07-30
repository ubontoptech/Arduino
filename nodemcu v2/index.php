<!DOCTYPE html>
<html>
<head>
  <title>IoT Control UBU</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
  <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
</head>
<body>
<div class="container">
  <h1 class="mt-5">Smart Control UBU</h1>
  <div class="card mt-3">
    <div class="card-body">
      <h5 class="card-title">| ควบคุมหลอดไฟ (Control LED) |</h5>
      <button id="turnOn" class="btn btn-success">Turn ON</button>
      <button id="turnOff" class="btn btn-danger">Turn OFF</button>
    </div>
  </div>
  <div class="card mt-3">
    <div class="card-body">
      <h5 class="card-title">สถานะปัจจุบัน (Current Status)</h5>
      <p id="currentStatus" class="card-text">
        <?php
        $servername = "localhost";
        $username = "root";
        $password = "topza1997";
        $dbname = "nodemcu_db";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
        }

        $result = $conn->query("SELECT * FROM led_status ORDER BY timestamp DESC LIMIT 1");
        if ($result->num_rows > 0) {
          $row = $result->fetch_assoc();
          echo "LED is currently: " . $row['status'];
        } else {
          echo "No status available";
        }

        $conn->close();
        ?>
      </p>
    </div>
  </div>
  <div class="card mt-3">
    <div class="card-body">
      <h5 class="card-title">ข้อมูลเซ็นเซอร์ (Sensor Data)</h5>
      <table class="table">
        <thead>
          <tr>
            <th>Timestamp</th>
            <th>Temperature</th>
            <th>Humidity</th>
          </tr>
        </thead>
        <tbody id="sensorData">
          <?php
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
        </tbody>
      </table>
    </div>
  </div>
  <div class="card mt-3">
    <div class="card-body">
      <h5 class="card-title">ประวัติสถานะ (Status Log)</h5>
      <table class="table">
        <thead>
          <tr>
            <th>Timestamp</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody id="statusLog">
          <?php
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
        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {
  $("#turnOn").click(function() {
    $.ajax({
      url: "control_led.php",
      type: "GET",
      data: { status: "ON" },
      success: function(response) {
        updateStatus();
      }
    });
  });

  $("#turnOff").click(function() {
    $.ajax({
      url: "control_led.php",
      type: "GET",
      data: { status: "OFF" },
      success: function(response) {
        updateStatus();
      }
    });
  });

  function updateStatus() {
    $.ajax({
      url: "control_led.php",
      type: "GET",
      success: function(response) {
        $("#currentStatus").text("LED is currently: " + response);
        updateLog();
      }
    });
  }

  function updateLog() {
    $.ajax({
      url: "status_log.php",
      type: "GET",
      success: function(response) {
        $("#statusLog").html(response);
      }
    });
  }

  function updateSensorData() {
    $.ajax({
      url: "sensor_data_log.php",
      type: "GET",
      success: function(response) {
        $("#sensorData").html(response);
      }
    });
  }

  setInterval(updateSensorData, 5000); // Update sensor data every 5 seconds
});
</script>
</body>
</html>
