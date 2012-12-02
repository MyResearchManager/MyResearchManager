<?php
   ob_start();
   session_start();

   if ($_SESSION['logado'] != 1)
        header("Location: login.php");

   $id  = $_SESSION['id'];

   $name = "";
   if(isset($_POST["name"]))
      $name = $_POST["name"];

   include "connection.php";

   $sql = "UPDATE Users SET name = '$name' WHERE idUser = '$id'";
   $exe = mysql_query($sql, $myrmconn) or print(mysql_error());

   header("Location: profile.php");
?>
