// Speak to arduino with PHP
// CC BY-NC-SA 2012 lululombard
// Made to work with statebeta.php and serial.sh
// Not yet commented, it's beta !

void setup() {
  Serial.begin(115200);
  for (byte i = 2; i <= 13; ++i)
    pinMode(i, OUTPUT);  
  }

void loop() {
  if (Serial.available()) {
    byte cmd = Serial.read();
    switch (cmd) {
    case '1': 
      Serial.println();
      for (byte i = 2; i <= 13; ++i){
        Serial.print(digitalRead(i));
        if (i != 13)
        Serial.write(';');
      }
      break;
    case '1': 
      for (byte i = 0; i <= 25; ++i){
        Serial.println("BOOT FILLING LINES FOR SCREEN");
      }
      break;

    case 'a'...'l': // GCC only (Not C standard)
      byte pin = cmd - 'a' + 2;
      if (digitalRead(pin) == LOW)
        digitalWrite(pin, HIGH);
      else 
        digitalWrite(pin, LOW);
      break;
    }
  }
}