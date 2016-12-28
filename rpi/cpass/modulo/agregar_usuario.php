<?php			
  error_reporting(E_ERROR | E_WARNING | E_PARSE);
  session_start();
  if( isset($_SESSION['ingreso']) || isset($_COOKIE['nueva'])){
    if(!isset($_SESSION['ingreso']) && isset($_COOKIE['nueva'])){
      $_SESSION['ingreso'] = $_COOKIE['nueva'];		
    }
    $var = 0;
  }else{
    $URL="/passctrl/";
    echo "<script>location.href='$URL'</script>";
    $var = 1;
  }
  $modulo = 1;
?>
<!DOCTYPE html>
<html lang="en" class="no-js">

<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
	<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
  <link rel="stylesheet" href="/passctrl/css/tables.css" type="text/css" />
	<link rel="stylesheet" href="/passctrl/css/input.css" type="text/css" />
  <link rel="stylesheet" href="/passctrl/css/style.css" type="text/css" />
  <link rel="stylesheet" href="/comercializadora/css/font-awesome.min.css">
  <link rel="stylesheet" href="/passctrl/css/drop.css" type="text/css" />
  <link rel="stylesheet" href="/passctrl/css/estadisticas.css" type="text/css" />

  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-T8Gy5hrqNKT+hzMclPo118YTQO6cYprQmhrYwIiQ/3axmI1hQomh7Ud2hPOy8SP1" crossorigin="anonymous">
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
  <script src="http://code.jquery.com/jquery-latest.js"></script>
	<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.2/jquery.min.js'></script>
  <script src="/passctrl/js/modernizr.js"></script>
  <script src="/passctrl/js/inputfile.js"></script>
  <script src="/passctrl/js/efectos.js"></script>
  <script src="/passctrl/js/tooltip.js"></script>
  <script type="text/javascript" src="/passctrl/js/jquery.Rut.min.js"></script>
    <script type="text/javascript" src="/passctrl/js/jquery.Rut.js"></script>
  <link rel="stylesheet" href="/passctrl/js/tooltip.css">
  <script>
  function AddUser()
	{
    var fd = new FormData();
    var input = ["", "", "","", "", "", "",""];
    for(i=1;i<=7;i++){
      input[i] = document.getElementById("form"+i).value;
      //alert(input[i]);
    }
    if(input[1] != ""){
        if(input[3] != ""){
            var sexo = document.getElementById('gender-male').checked;
            var acceso = document.getElementById('gender-si').checked;
            var bio = document.getElementById('gender-si-bio').checked;
            var rfid = document.getElementById('gender-si-rfid').checked;
            var nfc = document.getElementById('gender-si-nfc').checked;
            if(sexo == true){
            fd.append("sexo", "hombre");
            }else{
            fd.append("sexo", "mujer");
            }
            if(acceso == true){
            fd.append("acceso", "1");
            }else{
            fd.append("acceso", "0");
            }
            if(bio == true){
            fd.append("bio", "1");
            }else{
            fd.append("bio", "0");
            }
            if(rfid == true){
            fd.append("rfid", "1");
            }else{
            fd.append("rfid", "0");
            }
            if(nfc == true){
            fd.append("nfc", "1");
            }else{
            fd.append("nfc", "0");
            }
            fd.append("imagen", document.getElementById('files').files[0]);
            fd.append("modulo", 1);
            fd.append("nombre", input[1]);
            fd.append("apellido", input[2]);
            fd.append("rut", input[3]);
            fd.append("empresa", input[4]);
            fd.append("cargo", input[5]);
            fd.append("email", input[6]);
            fd.append("celular", input[7]);	
            fd.append("permiso", input[7]);
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "usuario_add.php");
            xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                var r = xhr.responseText;				
                //document.getElementById("Perfil"+id).innerHTML = r;
                alert(r);
                if(r=="Usuario adicionado exitosamente!"){
                    setTimeout("location.href = 'usuarios.php';",10);
                }
            }
            };
            xhr.send(fd);
        }else{
            alert("Debe ingresar un RUT");
        }
    }else{
        alert("Debe ingresar un nombre");
    }
     
	} 
  function readURL2(input) {
      if(input.files[0].type=='image/jpeg' || input.files[0].type=='image/png') {
          $.each(jQuery(input)[0].files, function (i, file) {
              var reader = new FileReader();
              reader.onload = function (e) {
              $('.overlay_uploader').hide();  
              $('.spinner').hide();  
              document.getElementById("img_user2").innerHTML = '<img src="'+e.target.result+'">';
              //$('.box'+user).css('background-image','url('+ e.target.result+')');
              }
              reader.readAsDataURL(input.files[0]);
          });
      }else{
          $('.overlay_uploader').hide();  
          $('.spinner').hide();
          alert("Solo se permiten archivos .jpg y .png");
      }
  }
  </script>
</head>

<body class="">

  <div class="container">
    <div class="breadcrumbs">
      <img style="top: 7px; left: 80px; height: 45px; z-index: 2; position: absolute;" src="/passctrl/img/ctrllpas.png">      
      <ul class="social">
        <?php
            include("sesion.php");
        ?>
      </ul>
    </div>
    <header class="clearfix">
      <span>PassCtrl</span> 
      <h1>Agregar usuario</h1>
      <nav>
        <a href="#" class="icon-arrow-left" data-info="Previous">Previous</a>
        <a href="#" class="icon-drop" data-info="See All">See All</a>
      </nav>     
    </header>
    <!--End Header -->
    <ul class="tl-menu">
      <li><a href="#">Logo</a></li>
      <li class="tl-current"><a title="Ver modulos" href="/passctrl/modulo/" class="entypo-shareable" id="navItem1">Option 1</a></li>
      <li><a href="#" class="icon-chart" id="navItem2">Option 2</a></li>
      <li><a href="#" id="navItem3">Option 3</a></li>
      <li> <a href="#" class="icon-download" id="navItem4">Active</a></li>
      <li><a href="#" class="entypo-network" id="navItem5">Option 4</a></li>
      <li><a href="#" class="icon-lamp" id="navItem6">Option 5</a></li>
      <li><a href="#" class="icon-file" id="navItem7">Option 6</a></li>
    </ul>
    <div  class="main"> 
        <div class="img_user2" id="img_user2">
            <img src="/passctrl/img/usuarios/anonimo.jpg">
        </div>
        <div id="content_uploader" class="content_uploader2">
            <div id="box" class="box">
                <input id="files" class="filefield" type="file" name="archivo" value="">
                <p class="select_bottom">Seleccionar un archivo</p>
                <div class="spinner"></div>
                <div class="overlay_uploader"></div>
            </div>
        </div> 
        <script>
            $(document).ready(function(){
            $('.select_bottom').click(function(){
                    $('.filefield').trigger('click');
                })
            $('.filefield').change(function(){
                if($(this).val()!=''){
                $('.overlay_uploader').show();  
                $('.spinner').show();  
                readURL2(this);
                }
            })
            })  
                    
        </script>      
        <table class="add_user">
            <tbody>
                <tr>  
                    <td>Nombre:</td>
                    <td><input id="form1" class="input_text" type="text" ></td>
                    <td>Apellido:</td>
                    <td><input id="form2" class="input_text" type="text" ></td>
                </tr>
                <tr >
                    <td >RUT:</td>
                    <td ><input id="form3"  class="input_text" type="text" ></td>
                    <script>
                        $('#form3').Rut({
                            on_error: function(){ alert('Rut incorrecto'); },
                            format_on: 'keyup'
                        });
                    </script>
                    <td >Sexo:</td>
                    <td >
                        <div id="select_sexo" class="div_selec">
                            <input id="gender-male" type="radio" name="gender" value="hombre" checked>
                            <label class="male" for="gender-male"><i class="fa fa-male" aria-hidden="true"></i></label>

                            <input   id="gender-female" type="radio" name="gender" value="mujer" >
                            <label class="famele" for="gender-female"><i class="fa fa-female" aria-hidden="true"></i></label>  
                        </div>
                    </td>
                </tr>
                <tr>
                    <td >Empresa:</td>
                    <td ><input id="form4" type="text" ></td>
                    <td >Cargo:</td>
                    <td ><input id="form5" type="text" ></td>
                </tr>
                <tr>
                    <td>Email:</td>
                    <td ><input id="form6" type="email" ></td>
                </tr>
                <tr>
                    <td>Celular:</td>
                    <td><input id="form7" type="text" ></td>
                </tr>
            </tbody>
        </table>
         <br>
        <br>
        <br>
        <table class="datos_acceso">
            <thead>
                <tr>        
                    <th>Módulo</th>
                    <th>Acceso</th>
                    <th><a title="Biometria"><img class="manImg" src="/passctrl/img/icon/Fingerprint Scan-50.png"></img></a></th>
                    <th><a title="RFID"><img class="manImg" src="/passctrl/img/icon/RFID Tag Filled-50.png"></img></a></th>
                    <th><a title="NFC"><img class="manImg" src="/passctrl/img/icon/NFC N-52.png"></img></a></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td >
                        1
                    </td> 
                    <td >
                        <div id="wrapper1" class="div_selec">
                            <input id="gender-si" type="radio" name="estado" value="si">
                            <label class="male" for="gender-si"><i class="fontawesome-ok"></i></label>

                            <input   id="gender-no" type="radio" name="estado" value="no" checked>
                            <label class="famele" for="gender-no"><i class="fa fa-times" aria-hidden="true"></i></label>  
                        </div>
                    </td>
                    <td >
                        <div id="wrapper3" class="div_selec">
                            <input id="gender-si-bio" type="radio" name="biometria" value="si">
                            <label class="male" for="gender-si-bio"><i class="fa fa-check" aria-hidden="true"></i></label>

                            <input   id="gender-no-bio" type="radio" name="biometria" value="no" checked>
                            <label class="famele" for="gender-no-bio"><i class="fa fa-times" aria-hidden="true"></i></label>  
                        </div>
                    </td>
                    <td >
                        <div id="wrapper4" class="div_selec">
                        <input id="gender-si-rfid" type="radio" name="rfid" value="si">
                        <label class="male" for="gender-si-rfid"><i class="fa fa-check" aria-hidden="true"></i></label>

                        <input   id="gender-no-rfid" type="radio" name="rfid" value="no" checked>
                        <label class="famele" for="gender-no-rfid"><i class="fa fa-times" aria-hidden="true"></i></label>  
                        </div>
                    </td>
                    <td >
                        <div id="wrapper5" class="div_selec">
                        <input id="gender-si-nfc" type="radio" name="nfc" value="si">
                        <label class="male" for="gender-si-nfc"><i class="fa fa-check" aria-hidden="true"></i></label>

                        <input   id="gender-no-nfc" type="radio" name="nfc" value="no" checked>
                        <label class="famele" for="gender-no-nfc"><i class="fa fa-times" aria-hidden="true"></i></label>  
                        </div>
                    </td>
                
                </tr>
                
            </tbody>
        </table> 
        <br>
        <center>
            <div  class="btn_container">
                <a id="btnsubir" title="Guardar usuario" class="btn blue" onclick="AddUser()"><i class="fa fa-floppy-o" aria-hidden="true"></i>&nbsp;&nbspGUARDAR</a>
            </div>    
        </center>
    </div>
    <!--End Main -->

    <!--Slider Nav 1-->

    <nav class="slider-menu slider-menu-vertical slider-menu-left" id="slider-menu-s1">
      <h3>MODULO</h3>
      <a href="/passctrl/modulo/usuarios.php" ><i class="fa fa-users" aria-hidden="true"></i>&nbsp;<p>Usuarios</p></a>
      <a href="/passctrl/modulo/agregar_usuario.php"><i  class="fa fa-user-plus" aria-hidden="true"></i>&nbsp;<p>Agregar Usuario</p></a>
      <a href="#"></a>
      <a href="#"></a>
      <a href="#"></a>
      <a href="#"></a>
      <a href="#"></a>
    </nav>

  </div>
  <!--End Containter -->


  <!-- Classie - class helper functions by @desandro https://github.com/desandro/classie -->

  <!-- Add id=navItem# to nav and add a seperate function below to match the id. Each nav item must have a unique id navItem1#. -->
  <script src="/passctrl/js/classie.js"></script>
  <script>
    var menuLeft = document.getElementById( 'slider-menu-s1' ),
    				showLeft = document.getElementById( 'showLeft' ),
    				body = document.body;
    
    			navItem1.onclick = function() {
    				classie.toggle( this, 'active' );
    				classie.toggle( menuLeft, 'slider-menu-open' );
    				disableOther( 'navItem1' );
    			};
          
    			navItem2.onclick = function() {
    				classie.toggle( this, 'active' );
    				classie.toggle( menuLeft, 'slider-menu-open' );
    				disableOther( 'navItem2' );
    			};
          
          navItem3.onclick = function() {
    				classie.toggle( this, 'active' );
    				classie.toggle( menuLeft, 'slider-menu-open' );
    				disableOther( 'navItem3' );
    			};
          
          navItem4.onclick = function() {
    				classie.toggle( this, 'active' );
    				classie.toggle( menuLeft, 'slider-menu-open' );
    				disableOther( 'navItem4' );
    			};
          
    			navItem5.onclick = function() {
    				classie.toggle( this, 'active' );
    				classie.toggle( menuLeft, 'slider-menu-open' );
    				disableOther( 'navItem5' );
    			};
          
          navItem6.onclick = function() {
    				classie.toggle( this, 'active' );
    				classie.toggle( menuLeft, 'slider-menu-open' );
    				disableOther( 'navItem6' );
    			};
          
          navItem7.onclick = function() {
    				classie.toggle( this, 'active' );
    				classie.toggle( menuLeft, 'slider-menu-open' );
    				disableOther( 'navItem7' );
    			};
    
    			function disableOther( button ) {
    				if( button !== 'showLeft' ) {
    					classie.toggle( showLeft, 'disabled' );
    				}
    			}
  </script>


</body>

</html>