<?php			
error_reporting(E_ERROR | E_WARNING | E_PARSE);
session_start();
if( isset($_SESSION['ingreso']) || isset($_COOKIE['nueva'])){
	if(!isset($_SESSION['ingreso']) && isset($_COOKIE['nueva'])){
		$_SESSION['ingreso'] = $_COOKIE['nueva'];		
	}
	$URL="modulo";
  echo "<script>location.href='$URL'</script>";
}
?>
<!DOCTYPE html>
<html lang="en" class="no-js">

<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
	<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
	<link rel="stylesheet" href="css/login.css" type="text/css" />
    <script src="http://code.jquery.com/jquery-latest.js"></script>
	<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
    <script src="js/modernizr.js"></script>
    <script src="js/jquery-1.12.0.min.js"></script>
    <script>

  
    function login(){
      var fd = new FormData();
      var email = document.getElementById("sesion-email").value;
      var pass = document.getElementById("sesion-password").value;
			fd.append("ingreso", email);
			fd.append("password", pass);
			
      var xhr = new XMLHttpRequest();
			xhr.open("POST", "login.php");
			xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                      // Handle response.
                  var r = xhr.responseText;
                  document.getElementById('resp_sesion').style.display = 'block';	
                  if(r == 1){
                    document.getElementById("resp_sesion").innerHTML = "Completa las casillas";
                    document.getElementById('resp_sesion').style.color = '#FF0000';	
                    document.getElementById("sesion-email").className += " animation-error";
                    document.getElementById("sesion-password").className += " animation-error";
                  }
                  if(r == 2){
                    document.getElementById("resp_sesion").innerHTML = "Email invalido, <b> registrate para abrir una cuenta</b>";
                    document.getElementById('resp_sesion').style.color = '#FF0000';
                    document.getElementById("sesion-email").className += " animation-error";
                  }
                  if(r == 3){
                    document.getElementById("resp_sesion").innerHTML = "Contraseña incorrecta, intentalo nuevamente";
                    document.getElementById('resp_sesion').style.color = '#FF0000';
                    document.getElementById("sesion-password").className += " animation-error";
                  }
                  if(r == 4){
                    document.getElementById("sesion-email").className += " bienvenido";
                    document.getElementById("sesion-password").className += " bienvenido";
                    document.getElementById("resp_sesion").innerHTML = "Bienvenido!";
                    document.getElementById('resp_sesion').style.color = 'green';
                    document.getElementById('login-btn').style.background = 'green';
                    setTimeout("location.href = 'modulo';",1000);
                  }
                }
      };
			xhr.send(fd);
       
     	
    }
    </script>
</head>
<body onload="myFunction()">
    <section>
        <div class="mobile-screen">
          <div class="header">
            <h1>Bienvenido!</h1>
          </div>
          
          <div class="logo">
            <img src="img/ctrllpas.png" />
          </div>
          
          <form id="registration-form">
            <input type="email" name="email" placeholder="Email">
            <input type="password" name="pass" placeholder="Contraseña">
            <input type="password" name="repass" placeholder="Confirma contraseña">
            <a href="#" class="login-btn" id="signup-btn">Registrate!</a>
          </form>

          <form id="login-form">
            <input id="sesion-email" type="email" name="email" placeholder="Email">
            <input id="sesion-password" type="password" name="pass" placeholder="Contraseña">
            <a onClick="login()" id="login-btn" class="login-btn">Ingresar</a>
          </form>
          
          <form id="fpass-form">
            <input id="sesion_password" type="text" name="forgotten" placeholder="Email">
            <a href="#" class="login-btn" id="getpass-btn">Recuperar contraseña</a>
          </form>
          
          <div class="other-options">
            <div class="option option2"  id="newUser"><p class="option-text">Registrate</p></div>
            <div class="option option2"  id="fPass"><p class="option-text">Olvidaste tu contraseña?</p></div>
          </div>
          <center>
          <div class="other-options2">
            <div class="option option1"  id="volver"><p class="option-text">Volver</p></div>
          </div>
          
          <div id="resp_sesion"></div>  
          </center>
        </div>
        <!-- /form -->
        <script language="javascript">
         $("#newUser").click(function(){
            $("h1").text("Registrate!");
            $(".logo").css({
              "width":"120px",
              "height":"120px",
              "top":"10px"
            });            
            $("#login-form").fadeOut(200);
            $(".other-options2").delay(300).fadeIn(500);
            $("#registration-form").delay(300).fadeIn(500);
            $(".other-options").fadeOut(200);
            $('#resp_sesion').css({
                  "display":"none"
                });
          });
          
          $("#signup-btn,#getpass-btn").click(function(){
            $("h1").text("Log in");
            $(".logo").css({
              "width":"150px",
              "height":"150px",
              "top":"30px"
            });
            $(".other-options2").fadeOut(200);
            $("#registration-form,#fpass-form").fadeOut(200);
            $("#login-form").delay(300).fadeIn(500);
            $(".other-options").fadeIn(300);
            $('#resp_sesion').css({
                  "display":"none"
                });
          });

          $("#fPass").click(function(){
            $("h1").text("Recuperar contraseña");
            $(".logo").css({
              "width":"190px",
              "height":"190px",
              "top":"40px"
            });

            $("#login-form").fadeOut(200);
            $("#fpass-form").delay(300).fadeIn(500);
            $(".other-options2").delay(300).fadeIn(500);
            $(".other-options").fadeOut(200);
            $('#resp_sesion').css({
                  "display":"none"
                });
          });
          $("#volver").click(function(){
            $('#resp_sesion').css({
                  "display":"none"
                });
            $("h1").text("Bienvenido!");
            $(".logo").css({
              "width":"150px",
              "height":"150px",
              "top":"30px"
            });
            $(".other-options2").fadeOut(200);
            $("#registration-form,#fpass-form").fadeOut(200);
            $("#login-form").delay(300).fadeIn(500);
            $(".other-options").fadeIn(300);
          });
          $('.mobile-screen').find('input, textarea').on('keyup blur focus', function (e) {
  
            var $this = $(this),
                label = $this.prev('label');
                $('#resp_sesion').css({
                  "display":"none"
                });
              if (e.type === 'keyup') {
                  $this.removeClass('animation-error'); 
              } else if (e.type === 'blur') {
                  $this.removeClass('animation-error');  
              } else if (e.type === 'focus') {
                $this.removeClass('animation-error'); 
              }

          });
        </script>
    </section>
</body>
</html>
