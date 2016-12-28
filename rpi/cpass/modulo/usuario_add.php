<?php
    error_reporting(E_ERROR | E_WARNING | E_PARSE);
    include("../connectmysql.php");
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
        $codigo = generarCodigo(9);
        do {		
            $rcodigo = "SELECT id_code FROM usuarios_modulos WHERE id_code='$codigo'";
            $result = $conn->query($rcodigo);
            $rowcodigo = $result->num_rows;
            $codigo = generarCodigo(9);
        } while ($rowcodigo!=0);
        $img = $_FILES['imagen']['name'];
        if($img){
            $ruta = "img/usuarios/" . $_FILES['imagen']['name'];
            $resultado = @move_uploaded_file($_FILES["imagen"]["tmp_name"], $ruta);
            
            if ($resultado){
                $sql = "INSERT INTO usuarios_modulos (user, idModulo, imagen, nombre, apellido, email, telefono, empresa, cargo, rut, sexo, acceso, biometria, rfid, nfc, id_code)
                VALUES ('ON', '$modulo', '".$img."','".$nombre."','".$apellido."','".$email."','".$celular."','".$empresa."',
                '".$cargo."','".$rut."','".$sexo."','".$acceso."','".$bio."','".$rfid."','".$nfc."','".$codigo."')";             
                if ($conn->query($sql) === TRUE ) {	
                    echo "Usuario adicionado exitosamente!";
                }else{
                    echo "Problemas de conexión con el servidor";    
                }
            }else{
                echo "Tenemos problemas con tu imagen";
            }
        }else{
            $sql = "INSERT INTO usuarios_modulos (user, idModulo, nombre, apellido, email, telefono, empresa, cargo, rut, sexo, acceso, biometria, rfid, nfc, id_code)
                VALUES ('ON', '$modulo', '$nombre','$apellido','$email','$celular','$empresa','$cargo','$rut','$sexo','$acceso','$bio','$rfid','$nfc','$codigo')"; 
            if ($conn->query($sql) === TRUE ) {	
                echo "Usuario adicionado exitosamente!";
            }else{
                echo "Problemas de conexión con el servidor";    
            }
        }
            	
    }

    function generarCodigo($longitud) {
        $key = '';
        $pattern = '1234567890'; //
        $max = strlen($pattern)-1;
        for($i=0;$i < $longitud;$i++) $key .= $pattern{mt_rand(0,$max)};
        return $key;
    }
?>