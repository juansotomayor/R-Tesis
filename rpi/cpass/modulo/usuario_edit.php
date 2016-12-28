<?php
    error_reporting(E_ERROR | E_WARNING | E_PARSE);
    include("../connectmysql.php");
    $modulo = mysqli_real_escape_string($conn,$_POST['modulo']);
    $id = mysqli_real_escape_string($conn,$_POST['id']);
    $nombre = mysqli_real_escape_string($conn,$_POST['nombre']);
    $apellido = mysqli_real_escape_string($conn,$_POST['apellido']);
    $rut = mysqli_real_escape_string($conn,$_POST['rut']);
    $empresa = mysqli_real_escape_string($conn,$_POST['empresa']);
    $cargo = mysqli_real_escape_string($conn,$_POST['cargo']);
    $email = mysqli_real_escape_string($conn,$_POST['email']);
    $sexo = mysqli_real_escape_string($conn,$_POST['sexo']);
    $celular = mysqli_real_escape_string($conn,$_POST['celular']);
    $acceso = mysqli_real_escape_string($conn,$_POST['acceso']);
    $bio = mysqli_real_escape_string($conn,$_POST['bio']);
    $rfid = mysqli_real_escape_string($conn,$_POST['rfid']);
    $nfc = mysqli_real_escape_string($conn,$_POST['nfc']);
    if($id && $modulo){
        $img = $_FILES['imagen']['name'];
        if($img){
            $ruta = "img/usuarios/" . $_FILES['imagen']['name'];
            $resultado = @move_uploaded_file($_FILES["imagen"]["tmp_name"], $ruta);
            
            if ($resultado){
                $sql = "UPDATE usuarios_modulos SET imagen ='".$img."', nombre ='".$nombre."',apellido ='".$apellido."',email ='".$email."',telefono ='".$celular."',empresa='".$empresa."', cargo='".$cargo."', RUT='".$rut."', sexo='".$sexo."',acceso='".$acceso."',biometria='".$bio."',rfid='".$rfid."',nfc='".$nfc."', fecha_act=now() WHERE id='".$id."'";
                if ($conn->query($sql) === TRUE ) {	
                    echo "Modificación exitosa!";
                }else{
                    echo "Error de conexión!";    
                }
            }else{
                echo "Problemas al cargar la imagen al servidor";
            }
        }else{
            $sql = "UPDATE usuarios_modulos SET nombre ='".$nombre."',apellido ='".$apellido."',email ='".$email."',telefono ='".$celular."',empresa='".$empresa."', cargo='".$cargo."', RUT='".$rut."', sexo='".$sexo."',acceso='".$acceso."',biometria='".$bio."',rfid='".$rfid."',nfc='".$nfc."', fecha_act=now() WHERE id='".$id."'";
            if ($conn->query($sql) === TRUE ) {	
                echo "Modificación exitosa!";
            }else{
                echo "Error de conexión!";    
            }
        }
            	
    }
?>