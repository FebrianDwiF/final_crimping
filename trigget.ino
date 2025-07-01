#define LED_PIN 10 // Ganti sesuai pin LED kamu
bool lastState = HIGH;

void setup() {
  Serial.begin(115200);
  pinMode(5, INPUT_PULLUP);
  pinMode(LED_PIN, OUTPUT);
  digitalWrite(LED_PIN, LOW);
}

void loop() {
  bool currentState = digitalRead(5);

  if (currentState == LOW && lastState == HIGH) {
    Serial.println("TRIGGER");
  }
  lastState = currentState;

  if (Serial.available()) {
    String command = Serial.readStringUntil('\n');
    command.trim();

    if (command == "true") {
      digitalWrite(LED_PIN, HIGH);
      Serial.println("ACK: LED ON");
    } else if (command == "false") {
      digitalWrite(LED_PIN, LOW);
      Serial.println("ACK: LED OFF");
    } else {
      Serial.println("ACK: Perintah tidak dikenali → " + command);
    }
  }

  delay(10);
}
