var gpio = require('rpi-gpio');
var SerialPort = require("serialport").SerialPort;
var serialport = new SerialPort(process.argv[2]);
var mysql      = require('mysql');

var host  = 'localhost';  //RPI
var user = 'root';
var password = 'nosewn';
var database = 'raidbuue_cControl';
/*
var host  = '103.224.22.99';  //raidbotics.com
var user = 'raidbuue';
var password = '6V9P%0#0G8hM';
var database = 'raidbuue_cControl';
*/
var Type = '';
var Code = '';
gpio.setup(7, gpio.DIR_OUT);

serialport.on('open', function(){
    console.log('Serial Port Opend');
});
/********************************************Lectura_Serial***********************************************************/
serialport.on('data', function(data){
    var receivedData = data.toString();
    var Data = receivedData.split("-");
    dataSize = Data.length;
    if(Data[0] == "FP"){
    if(Data[1] == "USER"){
        Access("BIOMETRIA", Data["2"]);
    }else if(Data[1] == "P"){
        if(Data[2] == "ENROLL"){
        CheckBiometric();
        }
        if(Data[2] == "FAIL"){

        }else{
        Code = Data[2];
        AddUser("BIOMETRIA", Code);
        }
    }
    }else if(Data[0] == "RFID"){
    if(Data[1] == "USER"){
        Access("RFID", Data["2"]);
    }else if(Data[1] == "P"){
        if(Data[2] == "ENROLL"){
        serialport.write("RFID_ENROLL-");
        }
    }else if(Data[1] == "ENROLL"){
        if(Data[2] == "FAIL"){

        }else{
        Code = Data[2];
        CheckRFID("RFID", Code);
        }
    }
    }else if(Data[0] == "USER"){
    console.log("RBA");
        Access("KEYPAD", Data[1]);
    }else if(Data[0] == "ADMIN"){
    console.log("Admin");
        CheckAdmin(Data[1], Data[2], Data[3]);

    }else if(Data[0] == "CHECK"){
    serialport.write("CHECK-");
    console.log("CHECK");
    //EstadoModulo(Data[1], Data[2]);
    }
    console.log(Data);
});

serialport.on('close', function(){
    console.log("puerto cerrado... reconectando");
    serialport = new SerialPort("/dev/ttyUSB0");
});
/********************************************Abrir_puerta***********************************************************/
function Open(x, y, z) {
    serialport.write("OPEN_DOOR*"+x+"*"+y+"*"+z+"-")
    //rpi
    gpio.write(7, true);
    setTimeout(function () {
        gpio.write(7, false);
    }, 1000);
}
/********************************************Adicionar_Usuario***********************************************************/
function AddUser(x, y) {
    console.log("AddUser");
    var connection = mysql.createConnection({
    host : host,
    user: user,
    password: password,
    database : database
    });
    connection.connect();
    var post  = {tipo: x, codigo: y, user:'ON'};
    var query = connection.query('INSERT INTO usuarios SET ?', post, function(error, result) {
    if (error) {
        console.log(error.message);
    } else {
        serialport.write("USER_ADDED-");
        console.log('success');
    }
    });
    console.log(query.sql); // INSERT INTO posts SET `id` = 1, `title` = 'Hello MySQL'
    connection.end();
}
/*******************************************Chequeo usuario Biometrico*******************************************************/
function CheckBiometric(){
    console.log("ChecBiometricr");
    var mysql      = require('mysql');
    var connection = mysql.createConnection({
        host : host,
        user: user,
        password: password,
        database : database
    });
    connection.connect();
    var query = connection.query('SELECT * FROM usuarios WHERE tipo = "BIOMETRIA" ', function(err, rows) {
    if (err) {
        console.log(error.message);
    } else {
            console.log("Lista de usuarios de biometria");
            var codigo = 0;
            for (var i = 0; i < rows.length; i++) {
            codigo = rows[i].codigo;
            console.log(codigo);
            if((codigo - i) != 0){
                codigo = i;
                break;
            }else{
                codigo++;
            }
            };
            serialport.write("FP_ENROLL*"+codigo+"-");
            console.log("agregar a "+codigo);
    }
    });
    connection.end();
}
/********************************************Chequear usuario RFID***********************************************************/
function CheckRFID(x, y){
    console.log("ChecRFID");
    var mysql      = require('mysql');
    var connection = mysql.createConnection({
        host : host,
        user: user,
        password: password,
        database : database
    });
    connection.connect();
    var query = connection.query('SELECT * FROM usuarios WHERE tipo = "'+x+'" AND codigo = "'+y+'"', function(err, rows) {
    if (err) {
        console.log(error.message);
    } else {
        for (var i = 0; i < rows.length; i++) {
        console.log(rows[i]);
        };
        if( rows.length == 0){
        console.log("agregar usuario");
        AddUser(x, y);
        }else{
        serialport.write("USER_EXISTING-");
        console.log("usuario existente");
        }
    }
    });
    console.log(query.sql); // INSERT INTO posts SET `id` = 1, `title` = 'Hello MySQL'
    connection.end();
}
/********************************************Chequear Administrador***********************************************************/
function CheckAdmin(x, y, z){
    console.log("CheckAdmin ");
    var mysql      = require('mysql');
    var connection = mysql.createConnection({
        host : host,
        user: user,
        password: password,
        database : database
    });
    connection.connect();
    var query = connection.query('SELECT * FROM pass_ctrl WHERE modulo = "'+x+'" AND codigo = "'+y+'" AND pass_admin = "'+z+'"', function(err, rows) {
    if (err) {
        console.log(error.message);
    } else {
        if( rows.length != 0 ){
        console.log("admin ok");
        serialport.write("ADMIN*OK-");
        }else{
        serialport.write("ADMIN*FAIL-");
        console.log("usuario existente");
        }
    }
    });
    console.log(query.sql); // INSERT INTO posts SET `id` = 1, `title` = 'Hello MySQL'
    connection.end();
}
/********************************************Access***********************************************************/
function Access(x, y){
    console.log("Access");
    var mysql      = require('mysql');
    var connection = mysql.createConnection({
        host : host,
        user: user,
        password: password,
        database : database
    });
    connection.connect();
    var query = connection.query('SELECT * FROM usuarios WHERE tipo = "'+x+'" AND codigo = "'+y+'"', function(err, rows) {
    if (err) {
        console.log(error.message);
    } else {
    if( rows.length == 0){
        console.log("usuario no existe");
        serialport.write("USER_NONEXIST-");
        if(x == "BIOMETRIA"){
        serialport.write("FP_DELETE*"+y);
        }
    }else{
        console.log("usuario existente");
        var id = rows[0].id;
        var nombre = rows[0].nombre;
        var apellido = rows[0].apellido;
        var sexo = rows[0].sexo;
        var estado = rows[0].estado;
        var user = rows[0].user;
        console.log(nombre);
        console.log(estado);
        console.log(user);
        if(estado == "HABILITADO" && user == "ON"){
        Open(nombre, apellido, sexo);
        History(id, nombre, apellido, x, estado);
        }else if(estado == "DESHABILITADO" || user == "OFF"){
        serialport.write("USER_DENIED*"+nombre+"*"+apellido +"-");
        History(id, nombre, apellido, x, estado);
        }else if(!nombre || !apellido || !sexo || !estado){
        serialport.write("USER_COMPLETFORM-");
        }else{
        serialport.write("USER_NONEXIST-");
        }
    }
    }
  });
  console.log(query.sql); // INSERT INTO posts SET `id` = 1, `title` = 'Hello MySQL'
  connection.end();
}
/*****************************************Almacenar el historial de ingreso***************************************************/
function History(a, b, c, d, e) {
  console.log("History");
  var connection = mysql.createConnection({
    host : host,
    user: user,
    password: password,
    database : database
  });
  connection.connect();
  var post  = {id: a, nombre: b, apellido: c, tipo: d, estado: e};
  var query = connection.query('INSERT INTO ingreso SET ?', post, function(error, result) {
    if (error) {
        console.log(error.message);
    } else {
        console.log('History ok');
    }
  });
  console.log(query.sql); // INSERT INTO posts SET `id` = 1, `title` = 'Hello MySQL'
  connection.end();
}
/********************************************Estado Modulo***********************************************************/
function EstadoModulo(x, y) {
  console.log("Estade_module");
  var connection = mysql.createConnection({
    host : host,
    user: user,
    password: password,
    database : database
  });
  connection.connect();
  var query = connection.query('UPDATE pass_ctrl SET estado = "ON" WHERE modulo = "'+x+'" AND codigo = "'+y+'"', function(error, result) {
    if (error) {
        console.log(error.message);
    } else {
        console.log('History ok');
    }
  });
  console.log(query.sql); // INSERT INTO posts SET `id` = 1, `title` = 'Hello MySQL'
  connection.end();
}

