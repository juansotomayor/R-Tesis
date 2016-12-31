<?php
    error_reporting(E_ERROR | E_WARNING | E_PARSE);
    include($_SERVER['DOCUMENT_ROOT']."/passctrl/connectmysql.php");
    $modulo = mysqli_real_escape_string($conn,$_POST['modulo']);
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
    if($modulo){
        $img = $_FILES['imagen']['name'];
        if($img){
            $ruta = "img/usuarios/" . $_FILES['imagen']['name'];
            $resultado = @move_uploaded_file($_FILES["imagen"]["tmp_name"], $ruta);
            
            if ($resultado){
                $sql = "INSERT INTO usuarios_modulos (user, idModulo, imagen, nombre, apellido, email, telefono, empresa, cargo, rut, sexo, acceso, biometria, rfid, nfc)
                VALUES ('ON', '$modulo', '".$img."','".$nombre."','".$apellido."','".$email."','".$celular."','".$empresa."',
                '".$cargo."','".$rut."','".$sexo."','".$acceso."','".$bio."','".$rfid."','".$nfc."')";             
                if ($conn->query($sql) === TRUE ) {	
                    echo "Usuario adicionado exitosamente!";
                }else{
                    echo "Problemas de conexión con el servidor";    
                }
            }else{
                echo "Tenemos problemas con tu imagen";
            }
        }else{
            $sql = "INSERT INTO usuarios_modulos (user, idModulo, nombre, apellido, email, telefono, empresa, cargo, rut, sexo, acceso, biometria, rfid, nfc)
                VALUES ('ON', '$modulo', '$nombre','$apellido','$email','$celular','$empresa','$cargo','$rut','$sexo','$acceso','$bio','$rfid','$nfc')"; 
            if ($conn->query($sql) === TRUE ) {	
                echo "Usuario adicionado exitosamente!";
            }else{
                echo "Problemas de conexión con el servidor";    
            }
        }
            	
    }
?>