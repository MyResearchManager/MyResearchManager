<?php
   ob_start();
   session_start();

   if ($_SESSION['logado'] != 1)
        header("Location: login.php");

   $id = $_SESSION['id'];

   $pid = -1;
   if(isset($_GET["pid"]))
      $pid = $_GET["pid"];


   include "connection.php";

   //TODO: check if publication is REALLY clean!! 

   $sql = "DELETE FROM PublicationMembers WHERE idPublication='$rid'";
   $exe = mysql_query($sql, $myrmconn) or print(mysql_error());

   $sql = "DELETE FROM Publications WHERE idPublication='$pid'";
   $exe = mysql_query($sql, $myrmconn) or print(mysql_error());

   header("Location: myrm.php");
?>
