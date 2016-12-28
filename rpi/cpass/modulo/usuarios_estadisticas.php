<?php
//http://qnimate.com/guide-to-styling-html5-input-elements/#prettyPhoto
//http://php.net/manual/es/datetime.format.php
//http://stackoverflow.com/questions/13178858/php-and-mysql-smallest-and-largest-possible-date
error_reporting(E_ERROR | E_WARNING | E_PARSE);
include("../connectmysql.php");
$modulo = mysqli_real_escape_string($conn,$_POST['modulo']);
$usuario = mysqli_real_escape_string($conn,$_POST['usuario']);
$fecha = mysqli_real_escape_string($conn,$_POST['fecha']);
$buscar = mysqli_real_escape_string($conn,$_POST['buscar']);
if($buscar == "usuario"){
    $sql2 = "SELECT * FROM ingreso WHERE idModulo = '$modulo' AND id='$usuario' AND fecha=(SELECT MIN(fecha) FROM ingreso WHERE idModulo = '$modulo' AND id='$usuario')";
    $query2 = mysqli_query($conn,$sql2);
    $numero2 =  $query2->num_rows;
    if($numero2 != 0 ){
        while ($registro = mysqli_fetch_assoc($query2)){  
            $min=date_create($registro['fecha']);  
            $min =date_format($min, 'Y-m-d');    
        }
    }

    $sql2 = "SELECT * FROM ingreso WHERE idModulo = '$modulo' AND id='$usuario' AND fecha=(SELECT MAX(fecha) FROM ingreso WHERE idModulo = '$modulo' AND id='$usuario')";
    $query2 = mysqli_query($conn,$sql2);
    $numero2 =  $query2->num_rows;
    if($numero2 != 0 ){
        while ($registro = mysqli_fetch_assoc($query2)){ 
            $max=date_create($registro['fecha']);  
            $max= date_format($max, 'Y-m-d'); 
        }
    }
    echo '    
        Reporte por dia<input type="date" id="fecha_dia'.$usuario.'" onchange="fecha_dia('.$usuario.','.$modulo.')" min="'.$min.'" max="'.$max.'"><br>
    ';
}else if($buscar == "fecha_dia"){
    
    $fecha2 = date_create($fecha);
    $fecha2= date_format($fecha2, 'Y-m-d'); 
    $sql2 = "SELECT * FROM ingreso WHERE  idModulo = '$modulo' AND id='$usuario' AND fecha LIKE '$fecha2%'";
    $query2 = mysqli_query($conn,$sql2);
    $numero2 =  $query2->num_rows;
    if($numero2 != 0 ){
        echo'
        <h3>Reporte correspondiente a la fecha '.$fecha2.'</h3>
        <table>
            <thead>
                <tr>
                    <th>Acceso</th>
                    <th>Sensor</th>
                    <th>Fecha</th>
                <tr>
            </thead>
            <tbody>
        ';
        $Q =0;
        while ($registro = mysqli_fetch_assoc($query2)){ 
            $Q++; 
            if($registro['estado'] == '1'){
                $estado = '<i class="fa fa-check verde fa-2x" aria-hidden="true"></i>';
            }else {
                $estado = '<i class="fa fa-times rojo fa-2x" aria-hidden="true"></i>';
           }
            if($registro['tipo'] == 'biometria'){
                $img = '<img title="Biometria" class="manImg" src="/passctrl/img/icon/Fingerprint Scan-50.png"></img>';
            }else if($registro['tipo'] == 'rfid'){
                $img = '<img title="RFID" class="manImg" src="/passctrl/img/icon/RFID Tag Filled-50.png"></img>';
           }else if($registro['tipo'] == 'nfc'){
                $img = '<img title="NFC" class="manImg" src="/passctrl/img/icon/NFC N-52.png"></img>';
            }else{
                $img = 'none';
            }
            echo'
            <tr>
                <td>'.$estado.'</td>
                <td>'.$img.'</td>
                <td>'.$registro['fecha'].'</td>
            </tr>
            ';
            
        }
        echo '
            </tbody>
        </table>
        ';
    }
}




/*$sql2 = "SELECT * FROM ingreso WHERE idModulo = '$modulo' AND id='$usuario' AND fecha BETWEEN (SELECT MIN(fecha) FROM ingreso) AND (SELECT MAX(fecha) FROM ingreso)";
$query2 = mysqli_query($conn,$sql2);
$numero2 =  $query2->num_rows;
if($numero2 != 0 ){
    $Q =0;
    while ($registro = mysqli_fetch_assoc($query2)){ 
        $Q++; 
        
    }
}
echo $Q;*/
?>