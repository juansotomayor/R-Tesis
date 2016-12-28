<?php
    error_reporting(E_ERROR | E_WARNING | E_PARSE);
    include("../connectmysql.php");
    $modulo = mysqli_real_escape_string($conn,$_POST['modulo']);
    $usuario = mysqli_real_escape_string($conn,$_POST['usuario']);
    $sql2 = "SELECT * FROM usuarios_modulos WHERE idModulo = '$modulo' AND id='$usuario'";
    $query2 = mysqli_query($conn,$sql2);
    $numero2 =  $query2->num_rows;
    if($numero2 != 0 ){
    $Q =0;
    while ($registro = mysqli_fetch_assoc($query2)){ 
        $Q++;
        $id=$registro['id'];
        $nombre=$registro['nombre'];
        $apellido=$registro['apellido'];
        $sexo=$registro['sexo'];
        $fecha=$registro['fecha'];
        $biometria=$registro['biometria'];
        $rfid=$registro['rfid'];
        $nfc=$registro['nfc'];
        $telefono=$registro['telefono'];
        $email=$registro['email'];
        $estado=$registro['acceso'];
        $user = $registro['user'];
        $img = $registro['imagen']; 
        $rut = $registro['RUT'];
        $cargo = $registro['cargo'];
        $empresa = $registro['empresa'];
        $id_code = $registro['id_code'];
        if($estado == '1'){
            $ee = '<i class="fa fa-check verde" aria-hidden="true"></i>';
            $ACCESO = 'si';
        }else{
            $ee =  '<i class="fa fa-times rojo" aria-hidden="true"></i>';
            $ACCESO = 'no';
        }
        if($sexo == 'hombre'){
            $sx = '&nbsp;&nbsp;<i class="fa fa-male verde" aria-hidden="true"></i>';
            $SEXO = 'H';
        }else if($sexo == 'mujer'){
            $sx = '&nbsp;&nbsp;<i class="fa fa-female rojo" aria-hidden="true"></i>';
            $SEXO= 'M';
        }
        $BIO = '<img class="manImg" src="/passctrl/img/icon/Fingerprint Scan-50.png"></img>';
        $RF = '<img class="manImg" src="/passctrl/img/icon/RFID Tag Filled-50.png"></img>';
        $NF = '<img class="manImg" src="/passctrl/img/icon/NFC N-52.png"></img>';

        if($biometria == 1 ){
            $BIOMETRIA = 'si';
            $eebio = '<i class="fa fa-check verde" aria-hidden="true"></i>';
        }else{
            $BIOMETRIA = 'no';
            $eebio =  '<i class="fa fa-times rojo" aria-hidden="true"></i>';
        }
        if($rfid == 1){
            $RFID = 'si';
            $eerfid = '<i class="fa fa-check verde" aria-hidden="true"></i>';
        }else{
            $RFID = 'no';
            $eerfid =  '<i class="fa fa-times rojo" aria-hidden="true"></i>';
        }
        if($nfc == 1){
            $NFC = 'si';
            $eenfc = '<i class="fa fa-check verde" aria-hidden="true"></i>';
        }else{
            $NFC = 'no';
            $eenfc =  '<i class="fa fa-times rojo" aria-hidden="true"></i>';
        }//<div id="img_user"><img src="/passctrl/img/usuarios/anonimo.jpg"></div>
        if($user == "ON"){//<div id="img_user"><img src="/passctrl/img/usuarios/plataf.jpg"></div>
        echo '  
            <div class="img_user" id="img_user'.$id.'">                   
             ';
        if($img){
            echo'
             <img src="/passctrl/modulo/img/usuarios/'.$img.'">
            ';
        }else{
            echo'
            <img src="/passctrl/img/usuarios/anonimo.jpg">
            ';
        }
        
        echo'
            </div>
             <table class="datos_user">
                    <tbody>
                        <tr>  
                            <td class="titulo">Nombre:</td>
                            <td class="normal"><input id="form1'.$id.'" type="text" value="'.$nombre.'"  readonly></td>
                            <td class="titulo">Apellido:</td>
                            <td class="normal" ><input id="form2'.$id.'" type="text" value="'.$apellido.'" readOnly></td>
                        </tr>
                        <tr >
                            <td class="titulo">RUT:</td>
                            <td class="normal"><input id="form3'.$id.'" type="text" value="'.$rut.'" readOnly></td>
                            <script>
                                $("#form3'.$id.'").Rut({
                                    on_error: function(){ alert("Rut incorrecto"); },
                                    format_on: "keyup""
                                });
                            </script>
                            <td class="titulo">Sexo:</td>
                            <td class="normal" style="font-size:20px;">
                                <div id="wrapper2'.$id.'" class="wrapper">
                                    <input id="gender-male'.$id.'" type="radio" name="gender" value="hombre">
                                    <label class="male" for="gender-male'.$id.'"><i class="fa fa-male" aria-hidden="true"></i></label>

                                    <input   id="gender-female'.$id.'" type="radio" name="gender" value="mujer" >
                                    <label class="famele" for="gender-female'.$id.'"><i class="fa fa-female" aria-hidden="true"></i></label>  
                                </div>
                                <span id="sexo'.$id.'">'.$sx.'</span><input type="hidden" id="S'.$id.'" value="'.$SEXO.'">
                            </td>
                        </tr>
                        <tr>
                            <td class="titulo">Empresa:</td>
                            <td class="normal"><input id="form4'.$id.'" type="text" value="'.$empresa.'" readOnly></td>
                            <td class="titulo">Cargo:</td>
                            <td class="normal"><input id="form5'.$id.'" type="text" value="'.$cargo.'" readOnly></td>
                        </tr>
                        <tr>
                            <td class="titulo"  >Email:</td>
                            <td class="normal txt_email"><input id="form6'.$id.'" type="text" value="'.$email.'" readOnly></td>
                        </tr>
                        <tr>
                            <td class="titulo" >Celular:</td>
                            <td class="normal"><input id="form7'.$id.'" type="text" value="'.$telefono.'" readOnly></td>
                            <td class="titulo" >Serial:</td>
                            <td class="normal"><input type="text" value="'.$id_code.'" readOnly></td>
                        </tr>
                        <tr>
                            <td colspan="2" class="titulo" >Fecha de registro:</td>
                            <td colspan="2" class="normal">'.$fecha.'</td>
                        </tr>
                    </tbody>
                </table>
                <table class="datos_user">
                    <thead>
                        <tr>        
                            <th>Módulo</th>
                            <th>Acceso</th>
                            <th><a title="Biometria">'.$BIO.'</a></th>
                            <th><a title="RFID">'.$RF.'</a></th>
                            <th><a title="NFC">'.$NF.'</a></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td >
                                '.$modulo.'
                            </td> 
                            <td >
                                <div id="wrapper1'.$id.'" class="wrapper">
                                    <input id="gender-si'.$id.'" type="radio" name="estado" value="si">
                                    <label class="male" for="gender-si'.$id.'"><i class="fontawesome-ok"></i></label>

                                    <input   id="gender-no'.$id.'" type="radio" name="estado" value="no" >
                                    <label class="famele" for="gender-no'.$id.'"><i class="fa fa-times" aria-hidden="true"></i></label>  
                                </div>
                                <span id="acceso'.$id.'">'.$ee.'</span><input type="hidden" id="AC'.$id.'" value="'.$ACCESO.'">
                            </td>
                            <td >
                                <div id="wrapper3'.$id.'" class="wrapper">
                                    <input id="gender-si-bio'.$id.'" type="radio" name="biometria" value="si">
                                    <label class="male" for="gender-si-bio'.$id.'"><i class="fa fa-check" aria-hidden="true"></i></label>

                                    <input   id="gender-no-bio'.$id.'" type="radio" name="biometria" value="no" >
                                    <label class="famele" for="gender-no-bio'.$id.'"><i class="fa fa-times" aria-hidden="true"></i></label>  
                                </div>
                                <span id="biometria'.$id.'">'.$eebio.'</span><input type="hidden" id="BIO'.$id.'" value="'.$BIOMETRIA.'">
                            </td>
                            <td >
                                <div id="wrapper4'.$id.'" class="wrapper">
                                <input id="gender-si-rfid'.$id.'" type="radio" name="rfid" value="si">
                                <label class="male" for="gender-si-rfid'.$id.'"><i class="fa fa-check" aria-hidden="true"></i></label>

                                <input   id="gender-no-rfid'.$id.'" type="radio" name="rfid" value="no" >
                                <label class="famele" for="gender-no-rfid'.$id.'"><i class="fa fa-times" aria-hidden="true"></i></label>  
                                </div>
                                <span id="rfid'.$id.'">'.$eerfid.'</span><input type="hidden" id="RFID'.$id.'" value="'.$RFID.'">
                            </td>
                            <td >
                                <div id="wrapper5'.$id.'" class="wrapper">
                                <input id="gender-si-nfc'.$id.'" type="radio" name="nfc" value="si">
                                <label class="male" for="gender-si-nfc'.$id.'"><i class="fa fa-check" aria-hidden="true"></i></label>

                                <input   id="gender-no-nfc'.$id.'" type="radio" name="nfc" value="no" >
                                <label class="famele" for="gender-no-nfc'.$id.'"><i class="fa fa-times" aria-hidden="true"></i></label>  
                                </div>
                                <span id="nfc'.$id.'">'.$eenfc.'</span><input type="hidden" id="NFC'.$id.'" value="'.$NFC.'">
                            </td>
                      
                        </tr>
                        <tr>
                            <td   style="text-align:right; border: none;" colspan="5" >
                                <a id="add'.$id.'" style="display:none;" > <i   class="fa fa-plus fa-2x plomo" aria-hidden="true"></i></a>
                            </td>
                        </tr>
                        <tr style="margin-top:4px;">
                            <td class="normal" colspan="2">
                                <a id="'.$id.'" title="Editar" onclick="editarUser(this.id)"><i class="plomo fa fa-pencil fa-2x" aria-hidden="true"></i></span></a>
                                &nbsp&nbsp&nbsp
                                <a id="'.$id.'" title="Eliminar usuario" onclick="eliminarUser(this.id)"><i class="plomo fa fa-trash fa-2x" aria-hidden="true"></i></span></a>
                            </td>
                            <td colspan="4">
                                <div  class="btn_container">
                                    <a id="btnsubir'.$id.'" title="Guardar cambios" class="btn blue2" onclick="modificarUser('.$id.','.$modulo.')"><i class="fa fa-floppy-o" aria-hidden="true"></i>&nbsp;&nbspGUARDAR</a>
                                </div>
                            </td>
                        </tr>
                        
                    </tbody>
                    </table>                
                
            ';
        }
    }
    	
    $conn->close();	
    }else{
    $conn->close();	
        echo 'No se logro acceder a su estado de cuarto';
    }
?>