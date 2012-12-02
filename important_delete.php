<?php
   ob_start();
   session_start();

   if ($_SESSION['logado'] != 1)
        header("Location: login.php");

   $id = $_SESSION['id'];

   $iid = -1;
   if(isset($_GET["iid"]))
      $iid = $_GET["iid"];

   if ($iid < 1)
        header("Location: myrm.php");

   // ========================================
   // incluir varias checagens de seguranca!!!
   // ========================================

   include "connection.php";

   $sql = "DELETE FROM ImportantDates WHERE idImportantDate = '$iid'";
   $exe = mysql_query($sql, $myrmconn) or print(mysql_error());

   header("Location: myrm.php");
?>
