<?php
session_start();
include("connectmysql.php");
$_SESSION['ingreso'] = mysqli_real_escape_string($conn,$_POST['ingreso']);
$_SESSION['password'] = mysqli_real_escape_string($conn,md5($_POST['password']));
if($_POST['ingreso'] && $_POST['password']){
	$sq="SELECT * FROM usuarios WHERE email='".$_SESSION['ingreso']."' AND EstadoKEY = '1'";
	$query = mysqli_query($conn,$sq);
	$numero =  $query->num_rows;
	if($numero != 0 ){
		while($row= mysqli_fetch_assoc($query)){
			$bdemail = $row['email'];
			$bdpassword = $row['password'];
		}
		if($_SESSION['ingreso'] == $bdemail && $_SESSION['password'] == $bdpassword){
			$sq3="SELECT * FROM usuarios WHERE email='".$_SESSION['ingreso']."'";
			$query3 = mysqli_query($conn,$sq3);
			$numero3 =  $query3->num_rows;
			if($numero3 != 0 ){
				while($row= mysqli_fetch_assoc($query3)){
					$usuario = $row['nombre'];
					$nivel = $row['nivel'];
				}				
			}
			$expira = time()+ 86400;				
			setcookie('nueva', $_SESSION['ingreso'],$expira);
			$_SESSION['usuario'] = $usuario;
			$_SESSION['nivel'] = $nivel;
			$conn->close();
			echo '4';
			/*echo "<script language='javascript'> location.href = 'index.php'; </script>";*/
			
		}else{
			session_destroy();
			$conn->close();
			echo '3';
			/*echo '<script language="javascript">alert("Este usuario est"+"\u00e1"+" incorrecto!");</script>';
			echo "<script language='javascript'> location.href = 'index.php'; </script>";*/
		}		
	}else{ 
		session_destroy();
		$conn->close();
		echo '2';
		/*echo '<script language="javascript">alert("Este usuario no est"+"\u00e1"+" registrado o tu cuenta no está activada");</script>';
		echo "<script language='javascript'> location.href = 'index.php'; </script>";*/
	}		
}else{
	session_destroy();
	echo '1';
	/*echo '<script language="javascript">alert("Completa el login");</script>';  
	echo "<script language='javascript'> location.href = 'index.php'; </script>";*/
}
?>