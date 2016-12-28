//Agregamos las librerias necesarias
#include <EEPROM.h>
#include <SPI.h>
#include <RFID.h>
#include <Adafruit_Fingerprint.h>
#include <SoftwareSerial.h>
#include <Keypad.h>
#include <Wire.h>
#include <LCD.h>
#include <LiquidCrystal_I2C.h>
//Finger Print
int getFingerprintIDez();
SoftwareSerial mySerial(68, 69);
Adafruit_Fingerprint finger = Adafruit_Fingerprint(&mySerial);
// Inicializamos las variables del RFID
#define SS_PIN 10 // pin SDA hacia el pin 10
#define RST_PIN 33 // pin RST hacia el pin 9
RFID rfid(SS_PIN, RST_PIN); 
LiquidCrystal_I2C lcd( 0x27, 2,   1,  0,  4,  5,  6,  7, 3, POSITIVE );
int serNum0;
int serNum1;
int serNum2;
int serNum3;
int serNum4;
///Keypad
const byte ROWS = 4; //four rows
const byte COLS = 4; //four columns
//define the cymbols on the buttons of the keypads
char hexaKeys[ROWS][COLS] = {
  {'1','2','3','A'},
  {'4','5','6','B'},
  {'7','8','9','C'},
  {'*','0','#','D'}
};
byte rowPins[ROWS] = {4, 5, 6, 7}; //connect to the row pinouts of the keypad
byte colPins[COLS] = {28,26,24,22}; //connect to the column pinouts of the keypad
Keypad customKeypad = Keypad( makeKeymap(hexaKeys), rowPins, colPins, ROWS, COLS); 
int ledRojo = 16;
int ledAzul = 15;
int ledVerde = 14;
/////variables para lectura serial
String Msj[4];
int mensaje = 0;
boolean stringComplete = false;  // whether the string is complete
int lcdAction = 0;
//// Variables
String FingerPrint = "OFF";
char pass_fabricante[5] = "2016";
int clave = 1;
int Col = 8;
char pass_maestra[20];
char password1[6];
char password2[6];
char password3[6];
int c_pass = 0;
int pass = 0;
int pass_master = 0;
int pass_rec = 0;
int N;
char key;
long timeA, timeB = 0;
int recuperar = 0;
int menuadm = 0;
int menubio = 0;
int menurfid = 0;
int fp_detec = 0;
int rfid_detec = 0;
int conextion = 0;
void setup() { 
  pinMode(ledRojo, OUTPUT);
  pinMode(ledAzul, OUTPUT);
  pinMode(ledVerde, OUTPUT);
  digitalWrite(ledAzul, HIGH);
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
    FingerPrint = "ON";
  } else {
    Serial.print("FP-OFF");
  }
  password_Fabric();
  lcd.setCursor(0,1);
  lcd.print("FINGER: "); 
  lcd.setCursor(0,2);
  lcd.print("KEYPAD: "); 
  lcd.setCursor(0,3);
  lcd.print("RIFD:");  
  
  lcd.clear();
}
 ////////////////////////////********************************************LOOP PRINCIPAL************************\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
void loop() {
  if(lcdAction == 0 && clave == 1){
    lcd.setCursor(4,0);
    lcd.print("PASS-CONTROL");
    lcd.setCursor(13,3);
    lcd.print("MENU[D]");
  }else if (lcdAction == 2){
    lcd.setCursor(3,1);
   lcd.print("PROCESANDO...");
  }
  if(lcdAction == 2){
    lcd.setCursor(3,1);
    lcd.print("PROCESANDO...");
  }
  char key = customKeypad.getKey();  
  if (key != NO_KEY ){    
    //Serial.print("KP-");
    //Serial.print(key);
    timeA = millis();      
    if((key == '0' || key == '1' || key == '2' || key == '3' || key == '4' || key == '5' || key == '6' || key == '7' || key == '8' || key == '9') && clave <= 4){          
      password3[clave] = key;
      lcd.setCursor(Col, 2);       
      lcd.write("*");
      Col++;
      clave++;
      if(clave > 1){
        lcd.setCursor(11,3);
        lcd.write("BORRAR[B]");
      }
      if(clave == 5){
        lcd.setCursor(0,3);
        lcd.write("ACEPTAR[A]");
      }
    }else if(key == 'B' && clave > 1 ){ 
      password3[clave] = ' ';
      Col--;
      clave--;
      lcd.setCursor(Col, 2);
      lcd.write(" ");     
      if(clave == 1){
        lcd.clear();     
      }else if(clave > 1 && clave < 5){
        lcd.setCursor(0,3);
        lcd.write("          ");
      }
    }else if(key == 'A' && clave == 5){
      pass_rec =  0;   
      clave = 1;
      Col=8;
      pass=1;
      Serial.print("ADMIN-");
      Serial.print(password3[1]);
      Serial.print(password3[2]);
      Serial.print(password3[3]);
      Serial.print(password3[4]);
      Waiting();
    }else if(key == 'D' && clave == 1) {
       pass_Master();
    } else if(key == '*' && clave == 1) {      
      recuperar++; 
      for(int i=0; i<2000; i++){
        delay(10);
        key = customKeypad.getKey() ; 
        if(key == '*'){
          recuperar++; 
          if(recuperar == 3){
            recuperar_pass(); 
            break;
          }
        }else if(key != NO_KEY){
          break;
        }
      }
      recuperar=0;      
    } 
  }else if((millis() - timeA) > 10000 && clave != 1){
    pass_rec =  0;   
    clave = 1;
    Col=8;
    pass=1;  
    lcd.clear();
    lcd.setCursor(3,1);
    lcd.write("TIEMPO SUPERADO");
    delay(1200);
    lcd.clear();
  }
  if(lcdAction == 0){
    getFingerprintIDez();
    delay(10);  
    read_RFID();
    rfid.halt();
  }
}
////////////////////////////********************************************FUNCIONES ************************\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

void Waiting(){  
  for(int i=0; i<4; i++){
      Msj[i] = "";
    }
  lcdAction = 2;
  conextion = 0;
  timeB = millis();
  lcd.clear();
  lcd.setCursor(3,1);
  lcd.print("PROCESANDO...");
  while((millis() - timeB) < 8000 && lcdAction == 2){
     if(Serial.available()) {
        serialEvent();
        conextion = 1;
     }
  }
  if(conextion == 0){
    lcd.clear();
    lcd.setCursor(3,1);
    lcd.write("TIEMPO SUPERADO");
    lcd.setCursor(4,2);
    lcd.write("SIN CONEXION");
    delay(1200);
    lcd.clear();
  }
  lcdAction = 0;
  lcd.clear();
}


void password_Fabric(){
  for(int i=0;i<4;i++){
    pass_maestra[i]=EEPROM.read(i);
  }  
  String num2=String(pass_maestra);
  N=num2.length();
  if(N!=4){ 
    pass=1;
    Col=8;
    password_Admin();   
  }
}
void password_Admin(){
  pass=1;
  while(pass != 0){
    lcd.setCursor(0,0);
    lcd.write("INGRESE NUEVA CLAVE");
    lcd.setCursor(4,1);
    lcd.write("DE 4 DIGITOS:");
    lcd.setCursor(0,3);
    lcd.write("[A]ACEPTAR [B]BORRAR");
    key = customKeypad.getKey();
    if (key != NO_KEY) { 
      if(key == 'B' && clave > 1 ){ 
        password1[clave] = ' ';
        Col--;
        clave--;
        lcd.setCursor(Col, 2);
        lcd.write(" ");          
      }else if((key == '0' || key == '1' || key == '2' || key == '3' || key == '4' || key == '5' || key == '6' || key == '7' || key == '8' || key == '9') && clave <= 4){          
        password1[clave] = key;
        lcd.setCursor(Col, 2);       
        lcd.write("*");
        Col++;
        clave++;
      }else if(key == 'A' && clave == 5){
        c_pass = 1;   
        clave = 1;
        Col=8;
        pass=0; 
        lcd.clear();       
      }
    }    
  }
  while(c_pass == 1){    
    lcd.setCursor(2,0);
    lcd.write("CONFIRMAR CLAVE");
    lcd.setCursor(0,3);
    lcd.write("[A]ACEPTAR [B]BORRAR");
    key = customKeypad.getKey();
    if (key != NO_KEY) {  
      if(key == 'B' && clave > 1 ){ 
        password2[clave] = ' ';
        Col--;
        clave--;
        lcd.setCursor(Col, 2);
        lcd.write(" ");          
      }else if((key == '0' || key == '1' || key == '2' || key == '3' || key == '4' || key == '5' || key == '6' || key == '7' || key == '8' || key == '9') && clave <= 4){          
        password2[clave] = key;
        lcd.setCursor(Col, 2);       
        lcd.write("*");
        Col++;
        clave++;
      }else if(key == 'A' && clave == 5){
        c_pass = 0;   
        clave = 1;
        Col=8;
        pass=0; 
        lcd.clear();       
      }
    }    
  }  
  if(password1[1] == password2[1] && password1[2] == password2[2] && password1[3] == password2[3] && password1[4] == password2[4]){    
    lcd.setCursor(3,0);
    lcd.print("CLAVE GUARDADA"); 
    lcd.setCursor(4,1);
    lcd.print("EXITOSAMENTE"); 
    for(int i=0; i<4; i++){
      EEPROM.write(i,password1[i+1]);
    }
  }else{
    lcd.setCursor(1,0);
    lcd.print("CLAVES NO COINCIDEN"); 
    pass=1;
    menuadm=0;
    delay(1000);
    lcd.clear();
  }
  delay(1000);
  lcd.clear();
  menu_Admin();
}

void pass_Master(){  
  pass_master = 1;
  Col = 8;
  lcd.clear();
  timeA = millis();
  while(pass_master == 1 && (millis() - timeA) < 10000){    
    lcd.setCursor(1,0);
    lcd.write("MENU ADMINISTRADOR");
    lcd.setCursor(4,1);
    lcd.write("CLAVE MAESTRA");
    lcd.setCursor(0,3);
    lcd.write("[A]ACEPTAR [B]BORRAR");
    key = customKeypad.getKey() ; 
    if (key != NO_KEY) {
      timeA = millis();  
      if(key == 'B' && clave > 1 ){ 
        password3[clave] = ' ';
        Col--;
        clave--;
        lcd.setCursor(Col, 2);
        lcd.write(" ");          
      }else if((key == '0' || key == '1' || key == '2' || key == '3' || key == '4' || key == '5' || key == '6' || key == '7' || key == '8' || key == '9') && clave <= 4){          
        password3[clave] = key;
        lcd.setCursor(Col, 2);       
        lcd.write("*");
        Col++;
        clave++;
      }else if(key == 'A' && clave == 5){
        pass_master = 0;   
        clave = 1;
        Col=8;
        pass=1;
        lcd.clear();       
      }
    }    
  }
  for(int i=0;i<4;i++){
    pass_maestra[i]=EEPROM.read(i);
  }  
  lcd.clear();
  if(password3[1] == pass_maestra[0] && password3[2] == pass_maestra[1] && password3[3] == pass_maestra[2] && password3[4] == pass_maestra[3]){
    menu_Admin();  
  }else if(pass_master == 1){
    pass_master=0;
    lcd.setCursor(3,1);
    lcd.write("TIEMPO SUPERADO");
    delay(1200);
    lcd.clear();
  }else{  
    lcd.setCursor(2,1);
    lcd.write("CLAVE INCORRECTA");
    delay(1000);
    pass_master=0;
    lcd.clear();
  }
}

void recuperar_pass(){
  pass_rec = 1;
  Col = 8;
  lcd.clear();
  long timeA = millis();
  while(pass_rec == 1 && (millis() - timeA) < 10000){    
    lcd.setCursor(2,0);
    lcd.write("RESTAURAR CLAVE");
    lcd.setCursor(0,1);
    lcd.write("CODIGO DE FABRICANTE");
    lcd.setCursor(0,3);
    lcd.write("[A]ACEPTAR [B]BORRAR");
    char key = customKeypad.getKey() ; 
    if (key != NO_KEY) {
      timeA = millis();  
      if(key == 'B' && clave > 1 ){ 
        password3[clave] = ' ';
        Col--;
        clave--;
        lcd.setCursor(Col, 2);
        lcd.write(" ");          
      }else if((key == '0' || key == '1' || key == '2' || key == '3' || key == '4' || key == '5' || key == '6' || key == '7' || key == '8' || key == '9') && clave <= 4){          
        password3[clave] = key;
        lcd.setCursor(Col, 2);       
        lcd.write("*");
        Col++;
        clave++;
      }else if(key == 'A' && clave == 5){
        pass_rec =  0;   
        clave = 1;
        Col=8;
        pass=1;              
      }
    }    
  }
  lcd.clear(); 
  if(password3[1] == pass_fabricante[0] && password3[2] == pass_fabricante[1] && password3[3] == pass_fabricante[2] && password3[4] == pass_fabricante[3]){
    password_Admin();
  }else if(pass_rec == 1){
    lcd.setCursor(3,1);
    lcd.write("TIEMPO SUPERADO");
    delay(1200);
    lcd.clear();
  }else{   
    lcd.setCursor(2,1);
    lcd.write("CODIGO INCORRECTO");
    delay(1200);
    lcd.clear();
  }
}

void verificar_Password(){
  lcd.clear();
  for(int i=1;i<5;i++){
    Serial.print(password3[i]);       
  } 
  delay(200);
  for(int i=0;i<4;i++){
    pass_maestra[i]=EEPROM.read(i);
  }  
  if(password3[1] == pass_maestra[0] && password3[2] == pass_maestra[1] && password3[3] == pass_maestra[2] && password3[4] == pass_maestra[3]){
    Serial.print("KP-USER-ADMIN");    
    Waiting();
  }else{     
    lcd.setCursor(2,0);
    lcd.write("CLAVE INCORRECTA");
    delay(1000);
    lcd.clear();
  }
}

void menu_Admin(){
  menuadm=1;
  lcd.clear();
  lcd.setCursor(1,0);
  lcd.print("MENU ADMINISTRADOR");
  lcd.setCursor(0,1);
  lcd.print("[1]-CAMBIAR CLAVE");
  lcd.setCursor(0,2);
  lcd.print("[2]-BIOMETRIA");
  lcd.setCursor(0,3);
  lcd.print("[3]-RFID [0]-VOLVER");
  timeA = millis();
  while(menuadm!=0 && (millis() - timeA) < 10000){
    if(Serial.available()){
      serialEvent();
      break;
    }
    key = customKeypad.getKey() ; 
    if (key != NO_KEY) {  
      timeA = millis();
      if(key == '0'){
        lcd.clear(); 
        menuadm=0;
        break;
      }else if(key == '3'){
        lcd.clear();
        menu_RFID();
      }else if(key == '2'){
        lcd.clear();
        menu_FP();
      }else if(key == '1'){
        lcd.clear();
        password_Admin();
      }
    } 
  }
  menubio=0;
  menuadm=0;
  lcd.clear();  
}
void menu_FP(){
  menubio=1;
  lcd.clear();
  lcd.setCursor(3,0);
  lcd.print("MENU BIOMETRIA");
  lcd.setCursor(0,2);
  lcd.print("[1]-AGREGAR USUARIO");
  lcd.setCursor(10,3);
  lcd.print("[0]-VOLVER");
  timeA = millis();
  while(menubio!=0 ){
    if((millis() - timeA) < 10000){
      key = customKeypad.getKey() ; 
      if (key != NO_KEY) {  
        timeA = millis();
        if(key == '0'){
          lcd.clear();
          menubio=0;
          menu_Admin();
        }else if(key == '1'){
          Serial.print("FP-P-ENROLL");
          Waiting();
          menurfid=0;
          menu_Admin();
        }
      }
    }else{
        menubio=0;
        menuadm=0;
        lcd.clear();   
    }      
  }
}
void menu_RFID(){
  menurfid=1;
  lcd.clear();
  lcd.setCursor(3,0);
  lcd.print("MENU RFID");
  lcd.setCursor(0,2);
  lcd.print("[1]-AGREGAR USUARIO");
  lcd.setCursor(10,3);
  lcd.print("[0]-VOLVER");
  timeA = millis();
  while(menurfid!=0){
    if((millis() - timeA) < 10000){
      key = customKeypad.getKey() ; 
      if (key != NO_KEY) {  
        timeA = millis();
        if(key == '0'){
          lcd.clear();
          menurfid=0;
          menu_Admin();
        }else if(key == '1'){
          lcd.clear();
          Serial.print("RFID-P-ENROLL");
          Waiting();
          menurfid=0;
          menu_Admin();
        }
      } 
    }else{
        menurfid=0;
        menuadm=0;
        lcd.clear();   
    }
  }
}
