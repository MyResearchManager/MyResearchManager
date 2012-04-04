<?php
   ob_start();
   session_start();

   if ($_SESSION['logado'] != 1)
        header("Location: login.php");

   $id = $_SESSION['id'];

   $cid = -1;

   if(isset($_GET["cid"]))
   {
      $cid = $_GET["cid"];
   }

   if ($cid < 1)
        header("Location: myrm.php");

   // ========================================
   // incluir varias checagens de seguranca!!!
   // ========================================

   include "connection.php";

   $sql = "DELETE FROM Conferences WHERE idConference = '$cid'";
   $exe = mysql_query($sql, $myrmconn) or print(mysql_error());

   header("Location: myrm.php");
?>
