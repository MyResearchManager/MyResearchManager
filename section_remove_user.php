<?php
   ob_start();
   session_start();

   if ($_SESSION['logado'] != 1)
        header("Location: login.php");

   $sid = "";
   if(isset($_GET["sid"]))
      $sid = $_GET["sid"];

   $uid = "";
   if(isset($_GET["uid"]))
      $uid =  $_GET["uid"];

   include "util.php";

   include "connection.php";

   $sql = "DELETE FROM SectionMembers WHERE idSection=$sid AND idUser=$uid";
   $exe = mysql_query($sql, $myrmconn) or print(mysql_error());

   header("Location: myrm.php#s$sid");
?>
