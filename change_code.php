<?php
   ob_start();
   session_start();

   if ($_SESSION['logado'] != 1)
        header("Location: login.php");

   $id  = $_SESSION['id'];

   include "connection.php";

   $sql = "UPDATE Users SET confirmationCode=MD5(RAND()) WHERE idUser = $id";
  
   $exe = mysql_query( $sql, $myrmconn) or print(mysql_error());
   if($exe != null)
        echo "Code updated!";
   else
        echo "Failed to update code!";
?>
