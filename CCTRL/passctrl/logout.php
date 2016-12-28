<?php
session_start();
$expire = time()-86400;
setcookie('nueva', $_SESSION['ingreso'], $expire);
session_destroy();
echo '<script language="javascript">alert("Sesi+"\u00f3"+n finalizada!");</script>';
echo "<script language='javascript'> location.href = 'http://localhost/passctrl'; </script>";
?>