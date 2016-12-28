//Agregamos las librerias necesarias
#include <SPI.h>
#include <RFID.h>
#include <LiquidCrystal.h>
#include <Adafruit_Fingerprint.h>
#include <SoftwareSerial.h>
#include <Keypad.h>

int getFingerprintIDez();
// Pinaje (MFRC522 hacia Arduino)
// MFRC522 pin SDA hacia el pin 10
// MFRC522 pin SCK hacia el pin 13
// MFRC522 pin MOSI hacia el pin 11
// MFRC522 pin MISO hacia el pin 12
// MFRC522 pin GND a tierra
// MFRC522 pin RST hacia el pin 9
// MFRC522 pin 3.3V A 3.3. V
SoftwareSerial mySerial(68, 69);
Adafruit_Fingerprint finger = Adafruit_Fingerprint(&mySerial);
#define SS_PIN 48 // pin SDA hacia el pin 10
#define RST_PIN 33 // pin RST hacia el pin 9
 
RFID rfid(SS_PIN, RST_PIN); 
 LiquidCrystal lcd(13, 12, 6, 5, 4, 3);
// Inicializamos las variables:
int serNum0;
int serNum1;
int serNum2;
int serNum3;
int serNum4;


const byte ROWS = 4; //four rows
const byte COLS = 4; //four columns
//define the cymbols on the buttons of the keypads
char hexaKeys[ROWS][COLS] = {
  {'1','2','3','A'},
  {'4','5','6','B'},
  {'7','8','9','C'},
  {'*','0','#','D'}
};
byte rowPins[ROWS] = {61, 60, 59, 58}; //connect to the row pinouts of the keypad
byte colPins[COLS] = {57, 56, 55, 54}; //connect to the column pinouts of the keypad

//initialize an instance of class NewKeypad
Keypad customKeypad = Keypad( makeKeymap(hexaKeys), rowPins, colPins, ROWS, COLS); 

String inputString = "";         // a string to hold incoming data
boolean stringComplete = false;  // whether the string is complete
int lcdAction = 0;
void setup() { 
	Serial.begin(9600); //Establecemos una velocidad de 9600 baudios
	SPI.begin(); 
  lcd.begin(20, 4);
  // Print a message to the LCD.
  lcd.setCursor(4,0);
  lcd.print("PASS-CONTROL");
	rfid.init();
  finger.begin(57600);  
  if (finger.verifyPassword()) {
    Serial.print("FP-ON");
  } else {
    Serial.print("FP-OFF");
    while (1);
  }
  lcd.setCursor(0,1);
  lcd.print("FINGER: "); 
  lcd.setCursor(0,2);
  lcd.print("KEYPAD: "); 
  lcd.setCursor(0,3);
  lcd.print("RIFD:");
  
  inputString.reserve(200);// reserve 200 bytes for the inputString:
  lcd.clear();
}
 
void loop() {
  if(lcdAction == 0){
     lcd.setCursor(4,0);
     lcd.print("PASS-CONTROL");
     lcd.setCursor(13,3);
     lcd.print("MENU[D]");
  }else if(lcdAction == 1){
     lcd.setCursor(4,0);
     lcd.print("PASS-CONTROL");
     lcd.setCursor(5,2);
     lcd.print("BIENVENIDO");
     delay(1500);
     lcdAction = 0;
     lcd.clear();
  }
  
  if (stringComplete) {
    if(inputString == "OPEN_DOOR"){
      lcd.clear();
      lcdAction = 1;
    } else if(inputString == "RFID_CLEAR"){
      serNum0 = 0;
      serNum1 = 0;
      serNum2 = 0;
      serNum3 = 0;
      serNum4 = 0;
    }else if(inputString == "FP_ENROLL"){
      int id = 0;
      while (!  getFingerprintEnroll(id) );  
    }else if(inputString == "FP_DELETE"){
      uint8_t id = 0;
      deleteFingerprint(id);
    }
    inputString = ""; // clear the string:
    stringComplete = false;
  }
  
  char customKey = customKeypad.getKey();  
  if (customKey){    
    Serial.print("KP-");
    Serial.print(customKey);
  }
  getFingerprintIDez();
  delay(10);
  
  
	if (rfid.isCard()) {
		if (rfid.readCardSerial()) {	
        if (rfid.serNum[0] != serNum0 || 
        rfid.serNum[1] != serNum1 || 
        rfid.serNum[2] != serNum2 || 
        rfid.serNum[3] != serNum3 || 
        rfid.serNum[4] != serNum4) {			
  				serNum0 = rfid.serNum[0];
  				serNum1 = rfid.serNum[1];
  				serNum2 = rfid.serNum[2];
  				serNum3 = rfid.serNum[3];
  				serNum4 = rfid.serNum[4];
          Serial.print("RFID-");
          Serial.print(rfid.serNum[0],HEX);
          Serial.print(rfid.serNum[1],HEX);
          Serial.print(rfid.serNum[2],HEX);
          Serial.print(rfid.serNum[3],HEX);
          Serial.print(rfid.serNum[4],HEX);		
        }
		}
	}
	rfid.halt();
}

void serialEvent() {
  while (Serial.available()) {// get the new byte:    
    char inChar = (char)Serial.read();    
    if (inChar == '-') {
      stringComplete = true;
      break;
    }
    inputString += inChar;
  }
}

