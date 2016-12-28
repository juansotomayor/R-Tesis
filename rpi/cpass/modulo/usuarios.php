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
  <script type="text/javascript" src="/comercializadora/js/jquery.Rut.min.js"></script>
  <script type="text/javascript" src="/comercializadora/js/jquery.Rut.js"></script>
  <link rel="stylesheet" href="/passctrl/js/tooltip.css">
  <script>
  
  /********************************/
    function ventanaMenu(user, id)
	{
    document.getElementById("ic_perfil"+user).className = document.getElementById("ic_perfil"+user).className.replace( /(?:^|\s)current(?!\S)/g , '' )
    document.getElementById("ic_estadisticas"+user).className = document.getElementById("ic_estadisticas"+user).className.replace( /(?:^|\s)current(?!\S)/g , '' )
    var usuarios = document.getElementById("Q_usuarios").value;
    for(i=1;i<=usuarios;i++){
      document.getElementById("c_perfil"+i).style.display = 'none';
      document.getElementById("c_estadisticas"+i).style.display = 'none';
      if(i != user){   
        document.getElementById(i).className = document.getElementById(i).className.replace( /(?:^|\s)select(?!\S)/g , '' )
        document.getElementById("user_info"+i).className =document.getElementById("user_info"+i).className.replace( /(?:^|\s)cortina(?!\S)/g , '' )         
      }      
    }
    vista=document.getElementById("user_info"+user).className;
    if (vista==''){
			document.getElementById("user_info"+user).className = "cortina";    
      document.getElementById(user).className = "select";    
		}else{       
			document.getElementById(user).className = document.getElementById(user).className.replace( /(?:^|\s)select(?!\S)/g , '' )
			document.getElementById("user_info"+user).className = document.getElementById("user_info"+user).className.replace( /(?:^|\s)cortina(?!\S)/g , '' )
	  }
    
	}
  function ventanaEstadisticas(user, modulo, id)
	{
    
       document.getElementById("ic_perfil"+user).className = document.getElementById("ic_perfil"+user).className.replace( /(?:^|\s)current(?!\S)/g , '' )

   vista=document.getElementById("c_estadisticas"+user).style.display;
		if (vista=='block'){
			vista='none';
      document.getElementById("ic_estadisticas"+user).className = document.getElementById("ic_estadisticas"+user).className.replace( /(?:^|\s)current(?!\S)/g , '' )
		}else{
			vista='block';
      document.getElementById("ic_estadisticas"+user).className = "current";				
		}
		document.getElementById("c_estadisticas"+user).style.display = vista;
     document.getElementById("c_perfil"+user).style.display = 'none';
     var fd = new FormData();		
    //alert(R_Shipper+"-"+R_Embarque+"-"+R_Carton+"-"+R_Palet+"-"+R_SO+"-"+R_Detalle+"-"+R_Imagen+"-"+R_Modelo+"-"+R_Linea+"-"+R_Qty+"-"+R_Prodid+"-"+R_Para+"/");
    fd.append("usuario", id);
    fd.append("modulo", modulo);
    fd.append("buscar", 'usuario');
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "usuarios_estadisticas.php");
    xhr.onreadystatechange = function() {
      if (xhr.readyState == 4 && xhr.status == 200) {
        var r = xhr.responseText;				
        document.getElementById("Estadisticas"+id).innerHTML = r;
        
      }
    };
    xhr.send(fd);
	}
  
  function ventanaPerfil(user, modulo, id)
	{
   document.getElementById("ic_estadisticas"+user).className = document.getElementById("ic_estadisticas"+user).className.replace( /(?:^|\s)current(?!\S)/g , '' )
   vista=document.getElementById("c_perfil"+user).style.display;
		if (vista=='block'){
			vista='none';
		}else{
      document.getElementById("ic_perfil"+user).className = "current"; 
			vista='block';				
		}
    
    document.getElementById("content_uploader"+id).style.display = "none";
		document.getElementById("c_perfil"+user).style.display = vista;
    document.getElementById("c_estadisticas"+user).style.display = 'none';
    var fd = new FormData();		
    //alert(R_Shipper+"-"+R_Embarque+"-"+R_Carton+"-"+R_Palet+"-"+R_SO+"-"+R_Detalle+"-"+R_Imagen+"-"+R_Modelo+"-"+R_Linea+"-"+R_Qty+"-"+R_Prodid+"-"+R_Para+"/");
    fd.append("usuario", id);
    fd.append("modulo", modulo);
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "usuarios_perfil.php");
    xhr.onreadystatechange = function() {
      if (xhr.readyState == 4 && xhr.status == 200) {
        var r = xhr.responseText;				
        document.getElementById("Perfil"+id).innerHTML = r;
        
      }
    };
    xhr.send(fd);
	}
  function editarUser(user)
  {
    var sexo = document.getElementById("S"+user).value; 
    var acceso= document.getElementById("AC"+user).value; 
    var bio= document.getElementById("BIO"+user).value; 
    var rfid= document.getElementById("RFID"+user).value; 
    var nfc= document.getElementById("NFC"+user).value; 
     if(sexo == 'H'){
        document.getElementById("gender-male"+user).checked = true;
        document.getElementById("gender-female"+user).checked = false;
      }else{
        document.getElementById("gender-male"+user).checked = false;
        document.getElementById("gender-female"+user).checked = true;
      }
     if(acceso == 'si'){
        document.getElementById("gender-si"+user).checked = true;
        document.getElementById("gender-no"+user).checked = false;
      }else{
        document.getElementById("gender-si"+user).checked = false;
        document.getElementById("gender-no"+user).checked = true;
      }
      if(bio == 'si'){
        document.getElementById("gender-si-bio"+user).checked = true;
        document.getElementById("gender-no-bio"+user).checked = false;
      }else{
        document.getElementById("gender-si-bio"+user).checked = false;
        document.getElementById("gender-no-bio"+user).checked = true;
      }
      if(rfid == 'si'){
        document.getElementById("gender-si-rfid"+user).checked = true;
        document.getElementById("gender-no-rfid"+user).checked = false;
      }else{
        document.getElementById("gender-si-rfid"+user).checked = false;
        document.getElementById("gender-no-rfid"+user).checked = true;
      }
      if(nfc == 'si'){
        document.getElementById("gender-si-nfc"+user).checked = true;
        document.getElementById("gender-no-nfc"+user).checked = false;
      }else{
        document.getElementById("gender-si-nfc"+user).checked = false;
        document.getElementById("gender-no-nfc"+user).checked = true;
      }
    for(i=1; i<=7;i++){
      var x = document.getElementById("form"+i+user).readOnly;
      if (x == true){
        document.getElementById("form"+i+user).className = "border"; 
        document.getElementById("form"+i+user).readOnly=false;
        document.getElementById("sexo"+user).style.display = "none";
        document.getElementById("acceso"+user).style.display = "none";
        document.getElementById("biometria"+user).style.display = "none";
        document.getElementById("rfid"+user).style.display = "none";
        document.getElementById("nfc"+user).style.display = "none";
        document.getElementById("content_uploader"+user).style.display = "block";
        document.getElementById("add"+user).style.display = "block";
        document.getElementById("btnsubir"+user).style.display = "block";
        document.getElementById("wrapper1"+user).style.display = "block"; 
        document.getElementById("wrapper2"+user).style.display = "block"; 
        document.getElementById("wrapper3"+user).style.display = "block";
        document.getElementById("wrapper4"+user).style.display = "block";
        document.getElementById("wrapper5"+user).style.display = "block";
         
             
      }else{   
        
        document.getElementById("form"+i+user).readOnly=true; 
        document.getElementById("add"+user).style.display = "none";
        document.getElementById("wrapper1"+user).style.display = "none";
        document.getElementById("wrapper2"+user).style.display = "none"; 
        document.getElementById("wrapper3"+user).style.display = "none";  
        document.getElementById("wrapper4"+user).style.display = "none";  
        document.getElementById("wrapper5"+user).style.display = "none";  
        document.getElementById("btnsubir"+user).style.display = "none"; 
        document.getElementById("content_uploader"+user).style.display = "none";
        document.getElementById("sexo"+user).style.display = "block";  
        document.getElementById("acceso"+user).style.display = "block"; 
        document.getElementById("biometria"+user).style.display = "block";
        document.getElementById("rfid"+user).style.display = "block";
        document.getElementById("nfc"+user).style.display = "block";
        document.getElementById("form"+i+user).className = document.getElementById("form"+i+user).className.replace( /(?:^|\s)border(?!\S)/g , '' )
      }      
    }    
  }
  function modificarUser(id, modulo)
	{
    var fd = new FormData();
    var input = ["", "", "","", "", "", "",""];
    for(i=1;i<=7;i++){
      input[i] = document.getElementById("form"+i+id).value;
      //alert(input[i]);
    }
    
     var sexo = document.getElementById('gender-male'+id).checked;
     var acceso = document.getElementById('gender-si'+id).checked;
     var bio = document.getElementById('gender-si-bio'+id).checked;
     var rfid = document.getElementById('gender-si-rfid'+id).checked;
     var nfc = document.getElementById('gender-si-nfc'+id).checked;
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
    fd.append("imagen", document.getElementById('files'+id).files[0]);
    fd.append("modulo", modulo);
    fd.append("id", id);
    fd.append("nombre", input[1]);
   	fd.append("apellido", input[2]);
     fd.append("rut", input[3]);
     fd.append("empresa", input[4]);
     fd.append("cargo", input[5]);
     fd.append("email", input[6]);
     fd.append("celular", input[7]);	
     fd.append("permiso", input[7]);
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "usuario_edit.php");
    xhr.onreadystatechange = function() {
      if (xhr.readyState == 4 && xhr.status == 200) {
        var r = xhr.responseText;				
        //document.getElementById("Perfil"+id).innerHTML = r;
        alert(r);
        if(r=="Modificación exitosa!"){
          setTimeout("location.href = 'usuarios.php';",10);
        }
      }
    };
    xhr.send(fd);
	} 
  function readURL2(input, user) {
      if(input.files[0].type=='image/jpeg' || input.files[0].type=='image/png') {
          $.each(jQuery(input)[0].files, function (i, file) {
              var reader = new FileReader();
              reader.onload = function (e) {
              $('.overlay_uploader').hide();  
              $('.spinner').hide();  
              document.getElementById("img_user"+user).innerHTML = '<img src="'+e.target.result+'">';
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
  function fecha_dia(user, modulo){
    var fecha = document.getElementById('fecha_dia'+user).value;
    
    var fd = new FormData();		
    //alert(R_Shipper+"-"+R_Embarque+"-"+R_Carton+"-"+R_Palet+"-"+R_SO+"-"+R_Detalle+"-"+R_Imagen+"-"+R_Modelo+"-"+R_Linea+"-"+R_Qty+"-"+R_Prodid+"-"+R_Para+"/");
    fd.append("usuario", user);
    fd.append("modulo", modulo);
    fd.append("fecha", fecha);
    fd.append("buscar", 'fecha_dia');
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "usuarios_estadisticas.php");
    xhr.onreadystatechange = function() {
      if (xhr.readyState == 4 && xhr.status == 200) {
        var r = xhr.responseText;	
        document.getElementById("Estadisticas_tabla"+user).innerHTML = r;
        
      }
    };
    xhr.send(fd);
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
      <h1>Usuarios</h1>
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
    <div id="main_usuarios" class="main">       
      <?php
      error_reporting(E_ERROR | E_WARNING | E_PARSE);
      include("../connectmysql.php");
      $modulo = '1';
      $sql2 = "SELECT * FROM usuarios_modulos WHERE idModulo = '$modulo'";
      $query2 = mysqli_query($conn,$sql2);
      $numero2 =  $query2->num_rows;
      if($numero2 != 0 ){
          echo'
          <table class="list_usuarios">
              <thead>
                <tr class="tr">
                  <th class="th" colspan=2>Nombre</th>
                  <th class="th">Apellido</th>
                  <th class="th">Empresa</th>
                  <th class="th">Cargo</th>	
                </tr>
              </thead>
              <tbody>'
          ;
        $Q =0;
        while ($registro = mysqli_fetch_assoc($query2)){ 
          $Q++;
          $id=$registro['id'];
          $nombre=$registro['nombre'];
          $apellido=$registro['apellido'];
          $cargo = $registro['cargo'];
          $empresa = $registro['empresa']; 
          $img = $registro['imagen'];          
            echo '
            <tr id="'.$Q.'" class="tr" onclick="ventanaMenu(this.id, '.$id.')" >  
              <td class="td" class="tdfoto">
                <div id="img_perfil">';
              if($img){
                  echo'
                  <img src="/passctrl/modulo/img/usuarios/'.$img.'">
                  ';
              }else{
                  echo'
                  <img src="/passctrl/img/usuarios/anonimo.jpg">
                  ';
              }
              echo '
                </div>
              </td>  
              <td class="td">'.$nombre.'</td>
              <td class="td">'.$apellido.'</td>
              <td class="td">'.$empresa.'</td>
              <td class="td">'.$cargo.'</td>
            </tr>
            <tr id="caja'.$id.'"  class="caja"  name="">
              <td class="td" colspan="8" id="td'.$id.'">
                <div id="user_info'.$Q.'" >
                  <nav class="nav nav1">
                    <ul>
                      <li>
                        <a  title="Estadísticas" id="ic_estadisticas'.$Q.'" onclick="ventanaEstadisticas('.$Q.', '.$modulo.', '.$id.')"><i class="fa fa-bar-chart" aria-hidden="true"></i></a>
                      </li>
                      <li>
                        <a  title="Perfil" id="ic_perfil'.$Q.'" onclick="ventanaPerfil('.$Q.', '.$modulo.', '.$id.')"><i class="fa fa-user" aria-hidden="true"></i></a>
                      </li>
                      <li>
                        <a href="#">Nav Item</a>
                      </li>               
                    </ul>
                  </nav>						
                  <div id="c_estadisticas'.$Q.'" class="menu_user">
                    <div id="Estadisticas'.$id.'" class="menu_estadisticas">
                    
                    </div>
                    <div id="Estadisticas_tabla'.$id.'" class="tabla_estadisticas">
                    
                    </div>
                  </div>
                  <div id="c_perfil'.$Q.'" class="menu_user"> 
                    <div id="content_uploader'.$id.'" class="content_uploader">
                        <div id="box" class="box'.$id.'">
                            <input id="files'.$id.'" class="filefield filefield'.$id.'" type="file" name="archivo" value="">
                            <p class="select_bottom select_bottom'.$id.'">Seleccionar un archivo</p>
                            <div class="spinner"></div>
                            <div class="overlay_uploader"></div>
                        </div>
                    </div> 
                    <div id="Perfil'.$id.'">
                    
                    </div>
                                       
                    
                  </div>';
                 echo"
           
                <script>
                 $(document).ready(function(){
                  $('.select_bottom".$id."').click(function(){
                          $('.filefield".$id."').trigger('click');
                      })
                  $('.filefield".$id."').change(function(){
                      if($(this).val()!=''){
                      $('.overlay_uploader').show();  
                      $('.spinner').show();  
                      readURL2(this,".$id.");
                      }
                  })
                  })  
                           
              </script>  
                  ";
                  echo'
                </div>
                
              </td>
            </tr>
              ';
          }
        
        echo'
              <script src="/passctrl/js/inputEfect.js"></script>
              <input type="hidden" id="Q_usuarios" value="'.$Q.'">
              </tbody>
          </table>
          ';
           
        $conn->close();	
      }else{
        $conn->close();	
        echo 'No se logro acceder al módulo o no tiene usuarios registrados';
      }              
      ?> 
      
    </div>
    <!--End Main -->

    <!--Slider Nav 1-->

    <nav class="slider-menu slider-menu-vertical slider-menu-left" id="slider-menu-s1">
      <h3>MODULO</h3>
      <a href="/passctrl/modulo/usuarios.php" ><i class="fa fa-users" aria-hidden="true"></i>&nbsp;<p>Usuarios</p></a>
      <a href="/passctrl/modulo/agregar_usuario.php"><i  class="fa fa-user-plus" aria-hidden="true"></i>&nbsp;<p>Agregar Usuario</p></a>      <a href="#">Item 3</a>
      <a href="#">Item 4</a>
      <a href="#">Item 5</a>
      <a href="#">Item 6</a>
      <a href="#">Item 7</a>
    </nav>

  </div>
  <!--End Containter -->


  <!-- Classie - class helper functions by @desandro https://github.com/desandro/classie -->

  <!-- Add id=navItem# to nav and add a seperate function below to match the id. Each nav item must have a unique id navItem1#. -->
  <script src="js/classie.js"></script>
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