//Agregamos las librerias necesarias
#include <SPI.h>
#include <RFID.h>
#include <Wire.h>
#include <LCD.h>
#include <LiquidCrystal_I2C.h>
 #include <Adafruit_Fingerprint.h>
#include <SoftwareSerial.h>

int getFingerprintIDez();
// Pinaje (MFRC522 hacia Arduino)
// MFRC522 pin SDA hacia el pin 10
// MFRC522 pin SCK hacia el pin 13
// MFRC522 pin MOSI hacia el pin 11
// MFRC522 pin MISO hacia el pin 12
// MFRC522 pin GND a tierra
// MFRC522 pin RST hacia el pin 9
// MFRC522 pin 3.3V A 3.3. V
SoftwareSerial mySerial(2, 3);
Adafruit_Fingerprint finger = Adafruit_Fingerprint(&mySerial);
#define SS_PIN 10 // pin SDA hacia el pin 10
#define RST_PIN 9 // pin RST hacia el pin 9
 
RFID rfid(SS_PIN, RST_PIN); 
 LiquidCrystal_I2C lcd( 0x27, 2,   1,  0,  4,  5,  6,  7,           3, POSITIVE );
// Inicializamos las variables:
int serNum0;
int serNum1;
int serNum2;
int serNum3;
int serNum4;
 
void setup() { 
  lcd.backlight();
  pinMode(2, OUTPUT);
  digitalWrite(2,HIGH);
	Serial.begin(9600); //Establecemos una velocidad de 9600 baudios
	SPI.begin(); 
  lcd.begin(20, 4);
  // Print a message to the LCD.
  lcd.setCursor(4,0);
  lcd.print("PASS-CONTROL");
	rfid.init();

  finger.begin(57600);
  
  if (finger.verifyPassword()) {
    Serial.println("Found fingerprint sensor!");
  } else {
    Serial.println("Did not find fingerprint sensor :(");
    while (1);
  }
  Serial.println("Waiting for valid finger...");
}
 
void loop() {
  getFingerprintIDez();
  delay(50);
	if (rfid.isCard()) {
		if (rfid.readCardSerial()) {
			if (rfid.serNum[0] != serNum0 || 
				rfid.serNum[1] != serNum1 || 
				rfid.serNum[2] != serNum2 || 
				rfid.serNum[3] != serNum3 || 
				rfid.serNum[4] != serNum4) {
				Serial.println(" ");
				Serial.println("Tarjeta encontrada");
				serNum0 = rfid.serNum[0];
				serNum1 = rfid.serNum[1];
				serNum2 = rfid.serNum[2];
				serNum3 = rfid.serNum[3];
				serNum4 = rfid.serNum[4];
 
				Serial.println(" ");
				Serial.println("Número de identificación:");
				Serial.print("En decimal: ");
				Serial.print(rfid.serNum[0],DEC);
				Serial.print(", ");
				Serial.print(rfid.serNum[1],DEC);
				Serial.print(", ");
				Serial.print(rfid.serNum[2],DEC);
				Serial.print(", ");
				Serial.print(rfid.serNum[3],DEC);
				Serial.print(", ");
				Serial.print(rfid.serNum[4],DEC);
				Serial.println(" ");

        lcd.setCursor(5,3);
				Serial.print("En Hexadecimal: ");
				Serial.print(rfid.serNum[0],HEX);
        lcd.print(rfid.serNum[0],HEX);
				Serial.print(", ");
				Serial.print(rfid.serNum[1],HEX);
        lcd.print(rfid.serNum[1],HEX);
				Serial.print(", ");
				Serial.print(rfid.serNum[2],HEX);
        lcd.print(rfid.serNum[2],HEX);
				Serial.print(", ");
				Serial.print(rfid.serNum[3],HEX);
        lcd.print(rfid.serNum[3],HEX);
				Serial.print(", ");
				Serial.print(rfid.serNum[4],HEX);
        lcd.print(rfid.serNum[4],HEX);
				Serial.println(" ");
			} 
			else {
				// En caso de que se haya leído nuevamente la tarjeta.
				Serial.println("Tarjeta leída anteriormente, ingrese otra.");
			}
		}
	}
	rfid.halt();
}

uint8_t getFingerprintID() {
  uint8_t p = finger.getImage();
  switch (p) {
    case FINGERPRINT_OK:
      Serial.println("Image taken");
      break;
    case FINGERPRINT_NOFINGER:
      Serial.println("No finger detected");
      return p;
    case FINGERPRINT_PACKETRECIEVEERR:
      Serial.println("Communication error");
      return p;
    case FINGERPRINT_IMAGEFAIL:
      Serial.println("Imaging error");
      return p;
    default:
      Serial.println("Unknown error");
      return p;
  }

  // OK success!

  p = finger.image2Tz();
  switch (p) {
    case FINGERPRINT_OK:
      Serial.println("Image converted");
      break;
    case FINGERPRINT_IMAGEMESS:
      Serial.println("Image too messy");
      return p;
    case FINGERPRINT_PACKETRECIEVEERR:
      Serial.println("Communication error");
      return p;
    case FINGERPRINT_FEATUREFAIL:
      Serial.println("Could not find fingerprint features");
      return p;
    case FINGERPRINT_INVALIDIMAGE:
      Serial.println("Could not find fingerprint features");
      return p;
    default:
      Serial.println("Unknown error");
      return p;
  }
  
  // OK converted!
  p = finger.fingerFastSearch();
  if (p == FINGERPRINT_OK) {
    Serial.println("Found a print match!");
  } else if (p == FINGERPRINT_PACKETRECIEVEERR) {
    Serial.println("Communication error");
    return p;
  } else if (p == FINGERPRINT_NOTFOUND) {
    Serial.println("Did not find a match");
    return p;
  } else {
    Serial.println("Unknown error");
    return p;
  }   
  
  // found a match!
  Serial.print("Found ID #"); Serial.print(finger.fingerID); 
  Serial.print(" with confidence of "); Serial.println(finger.confidence); 
}

// returns -1 if failed, otherwise returns ID #
int getFingerprintIDez() {
  uint8_t p = finger.getImage();
  if (p != FINGERPRINT_OK)  return -1;

  p = finger.image2Tz();
  if (p != FINGERPRINT_OK)  return -1;

  p = finger.fingerFastSearch();
  if (p != FINGERPRINT_OK)  return -1;
  
  // found a match!
  Serial.print("Found ID #"); Serial.print(finger.fingerID); 
  Serial.print(" with confidence of "); Serial.println(finger.confidence);
  return finger.fingerID; 
}
