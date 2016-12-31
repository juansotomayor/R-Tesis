//Agregamos las librerias necesarias
#include <SPI.h>
#include <RFID.h>
#include <Wire.h>
#include <LCD.h>
#include <LiquidCrystal_I2C.h>
 
//                     Addr, En, Rw, Rs, d4, d5, d6, d7, backlighpin, polarity
LiquidCrystal_I2C lcd( 0x27, 2,   1,  0,  4,  5,  6,  7,           3, POSITIVE );
// Pinaje (MFRC522 hacia Arduino)
// MFRC522 pin SDA hacia el pin 10
// MFRC522 pin SCK hacia el pin 13
// MFRC522 pin MOSI hacia el pin 11
// MFRC522 pin MISO hacia el pin 12
// MFRC522 pin GND a tierra
// MFRC522 pin RST hacia el pin 9
// MFRC522 pin 3.3V A 3.3. V
 
#define SS_PIN 10 // pin SDA hacia el pin 10
#define RST_PIN 9 // pin RST hacia el pin 9
 
RFID rfid(SS_PIN, RST_PIN); 
// Inicializamos las variables:
int serNum0;
int serNum1;
int serNum2;
int serNum3;
int serNum4;
 
void setup() { 
  pinMode(2, OUTPUT);
  digitalWrite(2,HIGH);
	Serial.begin(9600); //Establecemos una velocidad de 9600 baudios
	SPI.begin(); 
  lcd.begin(20, 4);
  lcd.backlight();
  // Print a message to the LCD.
  lcd.setCursor(4,0);
  lcd.print("PASS-CONTROL");
	rfid.init();
}
 
void loop() {
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
