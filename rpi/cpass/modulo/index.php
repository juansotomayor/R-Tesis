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
	<link rel="stylesheet" href="/passctrl/css/style.css" type="text/css" />
  <link rel="stylesheet" href="/passctrl/css/drop.css" type="text/css" />
  <link rel="stylesheet" href="/passctrl/css/font-awesome.min.css">
  <script src="http://code.jquery.com/jquery-latest.js"></script>
	<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
  <script src="/passctrl/js/modernizr.js"></script>
  <script src="/passctrl/js/jquery-1.12.0.min.js"></script>
  <script src="/passctrl/js/efectos.js"></script>
  <script src="/passctrl/js/tooltip.js"></script>
  <link rel="stylesheet" href="/passctrl/js/tooltip.css">
  <script>
  </script>
</head>

<body class="">

  <div class="container">
    <div class="breadcrumbs">
      <img style="top: 7px; left: 80px; height: 45px; z-index: 2; position: absolute;" src="/passctrl/img/ctrllpas.png">      
      <ul class="social">
        <?php
            include("../modulo/sesion.php");
        ?>
      </ul>
    </div>
    <header class="clearfix">
      <span>PassCtrl</span> 
      <h1>Modulos <?php echo $var;?></h1>
      <nav>
        <a href="#" class="icon-arrow-left" data-info="Previous">Previous</a>
        <a href="#" class="icon-drop" data-info="See All">See All</a>
      </nav>     
    </header>
    <!--End Header -->
    <ul class="tl-menu">
      <li><a href="#" >Logo</a></li>
      <li class="tl-current"><a title="Ver modulos" href="/passctrl/modulo/" class="entypo-shareable" id="navItem1">Option 1</a> </li>
      <li><a href="#" class="icon-chart" id="navItem2">Option 2</a></li>
      <li><a href="#" class="entypo-camera" id="navItem3">Option 3</a></li>
      <li> <a href="#" class="icon-download" id="navItem4">Active</a></li>
      <li><a href="#" class="entypo-network" id="navItem5">Option 4</a></li>
      <li><a href="#" class="icon-lamp" id="navItem6">Option 5</a></li>
      <li><a href="#" class="icon-file" id="navItem7">Option 6</a></li>
    </ul>
    <div id="main_usuarios" class="main">
      
      

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