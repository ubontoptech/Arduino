#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <DHT.h>

#define led01 D4
#define DHTPIN D1     // Pin where the DHT11 is connected
#define DHTTYPE DHT11 // DHT 11

const char* ssid = "Room_TopZAstudio";
const char* password = "topza1997";
const char* serverName = "http://192.168.1.47/nodemcu/control_led.php";
const char* sensorServerName = "http://192.168.1.47/nodemcu/sensor_data.php";

WiFiClient client;
DHT dht(DHTPIN, DHTTYPE);

unsigned long lastSendTime = 0; // Variable to store the last time the data was sent
const unsigned long interval = 300000; // 5 minutes in milliseconds

void setup() {
  Serial.begin(115200);
  WiFi.begin(ssid, password);

  while (WiFi.status() != WL_CONNECTED) {
    delay(1000);
    Serial.println("Connecting to WiFi...");
  }

  Serial.println("Connected to WiFi");

  dht.begin();
  pinMode(led01, OUTPUT);
  digitalWrite(led01, HIGH);
}

void loop() {
  if (WiFi.status() == WL_CONNECTED) {
    HTTPClient http;

    // Get LED status
    http.begin(client, serverName);
    int httpCode = http.GET();
    if (httpCode > 0) {
      String payload = http.getString();
      if (payload == "ON") {
        digitalWrite(led01, LOW);
        Serial.println("1 | LED ON");
      } else if (payload == "OFF") {
        digitalWrite(led01, HIGH);
        Serial.println("1 | LED OFF");
      }
    }
    http.end();

    unsigned long currentTime = millis();
    if (currentTime - lastSendTime >= interval) {
      // Read sensor data
      float h = dht.readHumidity();
      float t = dht.readTemperature();
      
      // Check if the readings are valid
      if (isnan(h) || isnan(t)) {
        Serial.println("Failed to read from DHT sensor!");
      } else {
        // Print out the sensor readings
        Serial.print("Temperature: ");
        Serial.print(t);
        Serial.print(" °C, Humidity: ");
        Serial.print(h);
        Serial.println(" %");

        // Send sensor data
        http.begin(client, sensorServerName);
        http.addHeader("Content-Type", "application/x-www-form-urlencoded");
        String postData = "temperature=" + String(t) + "&humidity=" + String(h);
        int sensorHttpCode = http.POST(postData);
        if (sensorHttpCode > 0) {
          Serial.println("Sensor data sent successfully.");
        } else {
          Serial.println("Failed to send sensor data.");
        }
        http.end();

        lastSendTime = currentTime; // Update last send time
      }
    }
  } else {
    Serial.println("WiFi not connected.");
  }

  delay(1000); // Small delay to avoid continuous loop execution
}
