<?php
   ob_start();
   session_start();

   if ($_SESSION['logado'] != 1)
        header("Location: login.php");

   $id  = $_SESSION['id'];

   $pwd_current = "";
   if(isset($_POST["pwd_current"]))
      $pwd_current = $_POST["pwd_current"];

   $pwd_new1 = "";
   if(isset($_POST["pwd_new1"]))
      $pwd_new1 = $_POST["pwd_new1"];

   $pwd_new2 = "";
   if(isset($_POST["pwd_new2"]))
      $pwd_new2 = $_POST["pwd_new2"];

   if($pwd_new1 != $pwd_new2)
      die("The informed passwords are different!");

   include "connection.php";

   $idUser = -1;

   $sql = "SELECT idUser FROM Users WHERE idUser = $id and password=MD5(CONCAT('$pwd_current',salt))";
   $exe = mysql_query( $sql, $myrmconn) or print(mysql_error());
   if($exe != null)
      if($line = mysql_fetch_array($exe))
          $idUser = $line['idUser'];

   if($idUser < 0)
      die("Wrong password!");

   $sql = "UPDATE Users SET password = MD5(CONCAT('$pwd_new1',salt)) WHERE idUser = '$id'";
   $exe = mysql_query($sql, $myrmconn) or print(mysql_error());

   echo "Password changed successfully!";
?>
