void read_RFID(){
  if (rfid.isCard()) {
    tiempoRFID = millis();
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
        Serial.print("RFID-USER-");
        Serial.print(rfid.serNum[0],HEX);
        Serial.print(rfid.serNum[1],HEX);
        Serial.print(rfid.serNum[2],HEX);
        Serial.print(rfid.serNum[3],HEX);
        Serial.print(rfid.serNum[4],HEX);
        Serial.print("-");
        Serial.print(modulo);
        lcd.clear();
        lcdAction = 2;
        Waiting();
      }
    }
  }
}

void RFID_ENROLL(String idUser){
  rfid_detec = 0;
  lcd.setCursor(3,0);
  lcd.print("APROXIME LLAVE");
  lcd.setCursor(0,1);
  lcd.print("MAGNETICA AL SENSOR");
  timeA = millis();
  while((millis() - timeA) < 15000){
    if (rfid.isCard()) {
      tiempoRFID = millis();
      if (rfid.readCardSerial()) {
          rfid_detec = 1; 
          lcd.setCursor(3,3);
          lcd.print("LLAVE DETECTADA");      
          serNum0 = rfid.serNum[0];
          serNum1 = rfid.serNum[1];
          serNum2 = rfid.serNum[2];
          serNum3 = rfid.serNum[3];
          serNum4 = rfid.serNum[4];
          Serial.print("RFID-P-");
          Serial.print(rfid.serNum[0],HEX);
          Serial.print(rfid.serNum[1],HEX);
          Serial.print(rfid.serNum[2],HEX);
          Serial.print(rfid.serNum[3],HEX);
          Serial.print(rfid.serNum[4],HEX); 
          Serial.print("-");
           Serial.print(modulo); 
           Serial.print("-");
           Serial.print(idUser); 
          delay(1200); 
          lcd.clear();          
          Waiting();     
          break;
      }
    }
  }
  if(rfid_detec == 0){
    Serial.print("RFID-ENROLL-FAIL");
    lcd.setCursor(1,3);
    lcd.print("LLAVE NO DETECTADA");  
    delay(1200); 
  }
  lcd.clear();
}

