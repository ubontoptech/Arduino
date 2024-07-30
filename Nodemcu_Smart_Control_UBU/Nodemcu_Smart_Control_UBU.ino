#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>

const char* ssid = "Room_TopZAstudio";
const char* password = "topza1997";
const char* serverName = "http://192.168.1.47/nodemcu/control_led.php";

WiFiClient client;

void setup() {
  Serial.begin(115200);
  WiFi.begin(ssid, password);

  while (WiFi.status() != WL_CONNECTED) {
    delay(1000);
    Serial.println("Connecting to WiFi...");
  }

  Serial.println("Connected to WiFi");

  pinMode(D4, OUTPUT);
  digitalWrite(D4, HIGH);
}

void loop() {
  if (WiFi.status() == WL_CONNECTED) {
    HTTPClient http;
    http.begin(client, serverName);  // Using WiFiClient with the URL
    int httpCode = http.GET();

    if (httpCode > 0) {
      String payload = http.getString();
      if (payload == "ON") {
        digitalWrite(D4, LOW);
        Serial.println("LED ON");
      } else if (payload == "OFF") {
        digitalWrite(D4, HIGH);
        Serial.println("LED OFF");
      }
    }
    http.end();
  }
  delay(5000); // Check every 5 seconds
}
