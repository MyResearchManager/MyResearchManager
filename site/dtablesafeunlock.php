<?php
   ob_start();
   session_start();

   if ($_SESSION['logado'] != 1)
        header("Location: login.php");

   $id = $_SESSION['id'];

   $tid = -1;

   if(isset($_GET["tid"]))
   {
      $tid = $_GET["tid"];
   }

   if ($tid < 1)
        header("Location: myrm.php");

   // ========================================
   // incluir varias checagens de seguranca!!!
   // ========================================

   include "connection.php";

   $sql = "UPDATE DynamicTables SET locked = '0' WHERE idDynamicTable = '$tid'";
   $exe = mysql_query($sql, $myrmconn) or print(mysql_error());

   header("Location: myrm.php");
?>
