<?php
   ob_start();
   session_start();

   if ($_SESSION['logado'] != 1)
        header("Location: login.php");

   $id = $_SESSION['id'];

   $rid = -1;
   if(isset($_POST["rid"]))
      $rid = $_POST["rid"];

   $title = "";
   if(isset($_POST["title"]))
      $title = $_POST["title"];

   // ========================================
   // incluir varias checagens de seguranca!!!
   // ========================================

   include "connection.php";

   $sql = "UPDATE Researches SET title = '$title' WHERE idResearch=$rid";
   $exe = mysql_query($sql, $myrmconn) or print(mysql_error());

   header("Location: myrm.php");
?>
