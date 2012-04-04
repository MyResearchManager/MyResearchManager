<?php
   ob_start();
   session_start();

   if ($_SESSION['logado'] != 1)
        header("Location: login.php");

   $id = $_SESSION['id'];

   $rid = -1;
   if(isset($_POST["rid"]))
   {
      $rid = $_POST["rid"];
   }

   $cname = "";
   if(isset($_POST["cname"]))
   {
      $cname = $_POST["cname"];
   }

   $curl = "";
   if(isset($_POST["curl"]))
   {
      $curl = $_POST["curl"];
   }

   // ========================================
   // incluir varias checagens de seguranca!!!
   // ========================================

   include "connection.php";

   $sql = "INSERT INTO Conferences (`idResearch`, `name`, `url`) VALUES ('$rid', '$cname', '$curl')";
   $exe = mysql_query($sql, $myrmconn) or print(mysql_error());

   header("Location: myrm.php");
?>
