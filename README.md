UBU JOB FOR TEST CODING
Mr.Settapong Phalaprom
2024

//////////////////////////////////////////////////////////////

http://arduino.esp8266.com/stable/package_esp8266com_index.json

Create Database : (nodemcu_db)

CREATE TABLE led_status (
  id INT AUTO_INCREMENT PRIMARY KEY,
  status VARCHAR(10),
  timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE sensor_data (
    id INT AUTO_INCREMENT PRIMARY KEY,
    temperature FLOAT NOT NULL,
    humidity FLOAT NOT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

link rel="icon" type="image/x-icon" href="/images/favicon.ico"
