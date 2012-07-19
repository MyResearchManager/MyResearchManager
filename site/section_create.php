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

   $stitle = "";
   if(isset($_POST["stitle"]))
   {
      $cname = $_POST["stitle"];
   }

   // ========================================
   // incluir varias checagens de seguranca!!!
   // ========================================

   include "connection.php";

   $sql = "INSERT INTO Sections (`idResearch`, `title`) VALUES ('$rid', '$stitle')";
   $exe = mysql_query($sql, $myrmconn) or print(mysql_error());

   header("Location: myrm.php");
?>
