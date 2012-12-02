<?php
   ob_start();
   session_start();

   if ($_SESSION['logado'] != 1)
        header("Location: login.php");

   $rid = "";
   if(isset($_GET["rid"]))
      $rid = $_GET["rid"];

   $uid = "";
   if(isset($_GET["uid"]))
      $uid =  $_GET["uid"];

   include "util.php";

   include "connection.php";

   $sql = "DELETE FROM ResearchMembers WHERE idResearch=$rid AND idUser=$uid";
   $exe = mysql_query($sql, $myrmconn) or print(mysql_error());

   header("Location: myrm.php");
?>
