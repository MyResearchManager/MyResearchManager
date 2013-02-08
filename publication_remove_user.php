<?php
   ob_start();
   session_start();

   if ($_SESSION['logado'] != 1)
        header("Location: login.php");

   $pid = "";
   if(isset($_GET["pid"]))
      $pid = $_GET["pid"];

   $uid = "";
   if(isset($_GET["uid"]))
      $uid =  $_GET["uid"];

   include "util.php";

   include "connection.php";

   $sql = "DELETE FROM PublicationMembers WHERE idPublication=$pid AND idUser=$uid";
   $exe = mysql_query($sql, $myrmconn) or print(mysql_error());

   header("Location: myrm.php");
?>
