<?php
   ob_start();
   session_start();

   if ($_SESSION['logado'] != 1)
        header("Location: login.php");

   $id = $_SESSION['id'];

   $sid = -1;
   if(isset($_POST["sid"]))
      $sid = $_POST["sid"];

   $desc = "";
   if(isset($_POST["desc"]))
   {
      $desc = $_POST["desc"];
   }

   // ========================================
   // incluir varias checagens de seguranca!!!
   // ========================================

   include "connection.php";

   $sql = "INSERT INTO DynamicTables (`description`, `key`, `locked`, `idSection`) VALUES ('$desc', MD5(RAND()), '0', 
$sid)";
   $exe = mysql_query($sql, $myrmconn) or print(mysql_error());

   header("Location: myrm.php");
?>
