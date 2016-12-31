void serialEvent() {  
  int valores = 0;
  while (Serial.available()) {// get the new byte:    
    char inChar = (char)Serial.read();     
    if(inChar == '*' ){
      mensaje++;
    }else  if (inChar == '-') {
      stringComplete = true;
      valores = mensaje;
      mensaje = 0;      
     
      break;
    }else{
      Msj[mensaje] += inChar;
    }
  }
  clave == 1;
  if (stringComplete) {
    stringComplete = false; 
    if(Msj[0] == "OPEN_DOOR"){
      int col1 = Msj[1].length();
      int col2 = Msj[2].length();
      float suma = col1+col2;  
      suma = (20 - suma)/2;
      col1 = (20 - col1)/2;
      col2 = (20 - col2)/2;
      lcd.clear();
      if(suma > 0){
        lcd.setCursor(suma,1);
        lcd.print(Msj[1]);
        lcd.print(" ");
        lcd.print(Msj[2]);
      }else{
        lcd.setCursor(col1,0);
        lcd.print(Msj[1]);
        lcd.setCursor(col2,1);
        lcd.print(Msj[2]);
      }  
      lcd.setCursor(5,3);
      if(Msj[3] == "hombre"){
        lcd.print("BIENVENIDO");
      }else{
        lcd.print("BIENVENIDA");
      }      
      RGB(0, 1, 0); // rojo
      delay(2000);
      lcdAction = 0;
      lcd.clear();
      lcdAction = 0;
    } else if(Msj[0] == "RFID_ENROLL"){
      lcd.clear();
      RFID_ENROLL(Msj[1]);
      lcdAction = 0;
    }else if(Msj[0] == "FP_ENROLL" && FingerPrint == "ON"){
      lcd.clear();
      fp_detec = 0;
      timeB = millis();
      while (!getFingerprintEnroll(Msj[1].toInt(),Msj[2] ) && (millis() - timeB) < 15000);  
      //
      if(fp_detec == 0){
        Serial.print("FP_ENROLL_FAIL");
      }
      Waiting();
      fp_detec = 0;
      delay(1000);
      lcd.clear();
      lcdAction = 0;
    }else if(Msj[0] == "FP_DELETE" && FingerPrint == "ON"){      
      deleteFingerprint(Msj[1].toInt());
      lcdAction = 0;
    }else if(Msj[0] == "FP_USERS" && FingerPrint == "ON"){
      for(int a=0; a<=125; a++){
        int coincidencia = 0;
        for(int b=0; b<=valores; b++){
          if(Msj[b].toInt() == a) {
             coincidencia = 1;
          }
        }
        if(coincidencia == 0){
          deleteFingerprint(a);
        }
      }
      //deleteFingerprint(Msj[1].toInt());
      //lcdAction = 0;
    }else if(Msj[0] == "USER_ADDED"){
      lcd.clear();
      lcd.setCursor(1,1);
      lcd.write("USUARIO ADICIONADO");
      RGB(0, 1, 0); // rojo
      delay(1200);
      lcd.clear();
      lcdAction = 0;
    }else if(Msj[0] == "USER_EXISTING"){
      lcd.clear();
      lcd.setCursor(1,1);
      lcd.write("USUARIO EXISTENTE");
      RGB(1, 0, 0); // rojo
      delay(1500);
      lcd.clear();
      lcdAction = 0;
    }else if(Msj[0] == "USER_DENIED"){
      int col1 = Msj[1].length();
      int col2 = Msj[2].length();
      float suma = col1+col2;  
      suma = (20 - suma)/2;
      col1 = (20 - col1)/2;
      col2 = (20 - col2)/2;
      lcd.clear();
      if(suma > 0){
        lcd.setCursor(suma,1);
        lcd.print(Msj[1]);
        lcd.print(" ");
        lcd.print(Msj[2]);
      }else{
        lcd.setCursor(col1,0);
        lcd.print(Msj[1]);
        lcd.setCursor(col2,1);
        lcd.print(Msj[2]);
      }    
      lcd.setCursor(2,3);
      lcd.write("NO TIENES ACCESO");
      RGB(1, 0, 0); // rojo
      delay(2000);
      lcd.clear();
      lcdAction = 0;
    }else if(Msj[0] == "USER_NONEXIST"){
      lcd.clear();
      lcd.setCursor(2,1);
      lcd.write("USUARIO NO EXISTE");
      RGB(1, 0, 0); // rojo
      delay(1500);
      lcd.clear();
      lcdAction = 0;
    }else if(Msj[0] == "USER_COMPLETFORM"){
      lcd.clear();
      lcd.setCursor(1,0);
      lcd.write("INGRESE LOS DATOS");
      lcd.setCursor(1,1);
      lcd.write("DEL USUARIO EN LA");
      lcd.setCursor(5,2);
      lcd.write("PLATAFORMA");
      RGB(1, 0, 0); // rojo
      delay(3000);
      lcd.clear();
      lcdAction = 0;
    }else if(Msj[0] == "USER_NON"){
      lcd.clear();
      lcd.setCursor(2,1);
      lcd.write("USUARIO NO EXISTE");
      RGB(1, 0, 0); // rojo
      delay(1500);
      verificar_usuario();
    }else if(Msj[0] == "USER_ADDSITE"){
      lcd.clear();
      lcd.setCursor(2,0);
      lcd.write("USUARIO YA TIENE");
      lcd.setCursor(0,1);
      lcd.write("LOS ACCESOS, INGRESE");
      lcd.setCursor(0,2);
      lcd.write("A LA PLATAFORMA PARA");
      lcd.setCursor(7,3);
      lcd.write("EDITAR");
      RGB(1, 0, 0); // rojo
      delay(1500);
      verificar_usuario();
    }else if(Msj[0] == "USER_ADD"){
      agregar_Acceso(Msj[1], Msj[2], Msj[3]);
    }else if(Msj[0] == "ADMIN"){
      if(Msj[1] == "OK"){
        menu_Administrador();        
      }else if(Msj[1] == "FAIL"){
        lcd.clear();
        lcd.setCursor(2,1);
        lcd.write("ACCESO DENEGADO");
        RGB(1, 0, 0); // rojo
        delay(1500);
        lcd.clear();
      }
      lcd.clear();
      lcdAction = 0;
    }else if(Msj[0] == "CHECK"){
      connection = 1;      
    }
    for(int i=0; i<4; i++){
      Msj[i] = "";
    }     
  }
  TiempoEnvio = millis();
  RGB(0, 0, 1); // azul
}
void RGB(int r, int g, int b){
  digitalWrite(ledVerde, g);
  digitalWrite(ledAzul, b);
  digitalWrite(ledRojo, r);
}

