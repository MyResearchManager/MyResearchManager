<?php
   ob_start();
   session_start();

   if ($_SESSION['logado'] != 1)
        header("Location: login.php");

   $id = $_SESSION['id'];

   $lid = -1;

   if(isset($_GET["lid"]))
      $lid = $_GET["lid"];

   if ($lid < 1)
        header("Location: myrm.php");

   // ========================================
   // incluir varias checagens de seguranca!!!
   // ========================================

   include "connection.php";

   $sql = "DELETE FROM Links WHERE idLink = '$lid'";
   $exe = mysql_query($sql, $myrmconn) or print(mysql_error());

   header("Location: myrm.php");
?>
