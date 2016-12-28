<?php
session_start();
	$email = $_SESSION['ingreso'];
	
	 
	if( $var == 0){
		error_reporting(E_ERROR | E_WARNING | E_PARSE);
		include("../connectmysql.php");
		$sql = "SELECT nivel FROM usuarios WHERE email='$email'"; 
		$result = mysqli_query($conn, $sql);
		if (mysqli_num_rows($result) > 0) {
			// output data of each row
			while($row = mysqli_fetch_assoc($result)) {
						$nivel = $row['nivel'];
			}
		}
		echo'
		<div id="sesion"> 
			
			<nav id="cssmenu">			
				<ul>
				<li><a href="#"><i class="fa fa-user-secret fa-2x" aria-hidden="true"></i></a>
				   <ul>

					  <li>
					  	<div id="flecha1" class="triangle-up"></div>
						<a href="#">CURSO 1</a>
						<ul>
							<li><a href="#">Sub Producto</a></li>
							<li><a href="#">Sub Producto</a></li>
						 </ul>
					  </li>
					  <li><a href="#">CURSO 2</a>
					  </li>
					  <li><a href="#">CURSO 3</a>
					  </li>
					  <li><a href="/passctrl/logout.php"><i class="fa fa-power-off " aria-hidden="true"></i>&nbsp;&nbsp; SALIR</a>
					  </li>
				   </ul>
				</li>	
				
			</nav>
		</div>
		
		';
	}else if( $var == 1){
		echo "
		<script>location.href = '/passctrl/';
		";
	}
	?>