<?php
   ob_start();
   session_start();

   if ($_SESSION['logado'] != 1)
        header("Location: login.php");

   //include "util.php";

   $pid = "";
   if(isset($_GET["pid"]))
      $pid = $_GET["pid"];

   $fid = "";
   if(isset($_GET["fid"]))
      $fid = $_GET["fid"];

   include "connection.php";

   $sql = "UPDATE Files SET idPublication = 0 WHERE idFile = $fid";
   $exe = mysql_query($sql, $myrmconn) or print(mysql_error());
 
   //echo $sql;
   header("Location: myrm.php");
?>
