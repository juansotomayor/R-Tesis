#include <Adafruit_Fingerprint.h>
#include <SoftwareSerial.h>
#include <SPI.h>
#include <RFID.h>
#include <Keypad.h>
#include <Wire.h>
#include <LCD.h>
#include <LiquidCrystal_I2C.h>
int getFingerprintIDez();
SoftwareSerial mySerial(68, 69);
Adafruit_Fingerprint finger = Adafruit_Fingerprint(&mySerial);
#define SS_PIN 10 // pin SDA hacia el pin 10
#define RST_PIN 22 // pin RST hacia el pin 9
RFID rfid(SS_PIN, RST_PIN); 
const byte ROWS = 4; 
const byte COLS = 4; 
char hexaKeys[ROWS][COLS] = {
  {'1','2','3','A'},
  {'4','5','6','B'},
  {'7','8','9','C'},
  {'*','0','#','D'}
};
byte rowPins[ROWS] = {4, 5, 6, 7}; //connect to the row pinouts of the keypad
byte colPins[COLS] = {22,24,26,28}; //connect to the column pinouts of the keypad
Keypad customKeypad = Keypad( makeKeymap(hexaKeys), rowPins, colPins, ROWS, COLS); 
LiquidCrystal_I2C lcd( 0x27, 2,   1,  0,  4,  5,  6,  7, 3, POSITIVE );
int serNum0;
int serNum1;
int serNum2;
int serNum3;
int serNum4;
float tiempoRFID, timeA, timeB = 0;
int fp_detec = 0;
int rfid_detec = 0;
int ledRojo = 16;
int ledAzul = 15;
int ledVerde = 14;
String Msj[4];
int mensaje = 0;
boolean stringComplete = false;

void setup(){
  pinMode(ledRojo, OUTPUT);
  pinMode(ledAzul, OUTPUT);
  pinMode(ledVerde, OUTPUT);
  digitalWrite(ledAzul, HIGH);
  Serial.begin(9600);
  lcd.backlight();  
  SPI.begin(); 
  rfid.init();
  lcd.begin(20,4);
  lcd.setCursor(4,0);
  lcd.print("PASS-CONTROL");
  finger.begin(57600);  
  if (finger.verifyPassword()) {
    Serial.print("FP-ON");
  } else {
    Serial.print("FP-OFF");
  }
}  
void loop(){
  getFingerprintIDez();
  delay(10);  
  char customKey = customKeypad.getKey();  
  if (customKey){
    Serial.print(customKey);
  }
  if((millis() - tiempoRFID) > 1000){
    serNum0 = 0;
    serNum1 = 0;
    serNum2 = 0;
    serNum3 = 0;
    serNum4 = 0;    
  }
  if (rfid.isCard()) {
    tiempoRFID = millis();
    if (rfid.readCardSerial()) {
      if (rfid.serNum[0] != serNum0 || rfid.serNum[1] != serNum1 || rfid.serNum[2] != serNum2 || rfid.serNum[3] != serNum3 || 
        rfid.serNum[4] != serNum4) {
        Serial.println(" ");
        Serial.println("Tarjeta encontrada");
        serNum0 = rfid.serNum[0];
        serNum1 = rfid.serNum[1];
        serNum2 = rfid.serNum[2];
        serNum3 = rfid.serNum[3];
        serNum4 = rfid.serNum[4];
        lcd.setCursor(5,3);
        lcd.print(rfid.serNum[0],HEX);
        lcd.print(rfid.serNum[1],HEX);
        lcd.print(rfid.serNum[2],HEX);
        lcd.print(rfid.serNum[3],HEX);
        lcd.print(rfid.serNum[4],HEX);
        Serial.print("En Hexadecimal: ");
        Serial.print(rfid.serNum[0],HEX);        
        Serial.print(", ");
        Serial.print(rfid.serNum[1],HEX);        
        Serial.print(", ");
        Serial.print(rfid.serNum[2],HEX);
        Serial.print(", ");
        Serial.print(rfid.serNum[3],HEX);
        Serial.print(", ");
        Serial.print(rfid.serNum[4],HEX);
        Serial.println(" ");
      } 
      else {
        Serial.println("Tarjeta leída anteriormente, ingrese otra.");
      }
    }
  }
  rfid.halt();
}

void serialEvent() {
  while (Serial.available()) {// get the new byte:    
    char inChar = (char)Serial.read();  
    if(inChar == '*' ){
      mensaje++;
    }else  if (inChar == '-') {
      stringComplete = true;
      mensaje = 0;
      break;
    }else{
      Msj[mensaje] += inChar;
    }
  }
  if (stringComplete) {
    stringComplete = false; 
    if(Msj[0] == "OPEN_DOOR"){
      lcd.clear();
      lcd.setCursor(5,3);
      lcd.print("BIENVENIDO"); 
      lcd.clear();
    } else if(Msj[0] == "RFID_ENROLL"){
      lcd.clear();
      //RFID_ENROLL();
    }else if(Msj[0] == "FP_ENROLL" ){
      lcd.clear();
      //fp_detec = 0;
      timeB = millis();
      while (!getFingerprintEnroll(Msj[1].toInt()) && (millis() - timeB) < 15000);  
      //
      //if(fp_detec == 0){
      //  Serial.print("FP_ENROLL_FAIL");
      //}
      //fp_detec = 0;
      lcd.clear();
    }else if(Msj[0] == "FP_DELETE" ){      
      deleteFingerprint(Msj[1].toInt());
    }else if(Msj[0] == "USER_ADDED"){
      lcd.clear();
      lcd.setCursor(1,1);
      lcd.write("USUARIO ADICIONADO");
      lcd.clear();
    }else if(Msj[0] == "USER_EXISTING"){
      lcd.clear();
      lcd.setCursor(1,1);
      lcd.write("USUARIO EXISTENTE");
      lcd.clear();
    }else if(Msj[0] == "USER_DENIED"){      
      lcd.clear();
      lcd.setCursor(2,3);
      lcd.write("NO TIENES ACCESO");
      lcd.clear();
    }else if(Msj[0] == "USER_NONEXIST"){
      lcd.clear();
      lcd.setCursor(2,1);
      lcd.write("USUARIO NO EXISTE");
      delay(1500);
      lcd.clear();
    }else if(Msj[0] == "USER_COMPLETFORM"){
      lcd.clear();
      lcd.setCursor(1,0);
      lcd.write("INGRESE LOS DATOS");
      lcd.setCursor(1,1);
      lcd.write("DEL USUARIO EN LA");
      lcd.setCursor(5,2);
      lcd.write("PLATAFORMA");
      delay(3000);
      lcd.clear();
    }
    for(int i=0; i<4; i++){
      Msj[i] = "";
    }
     
  }
}
