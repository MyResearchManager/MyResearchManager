<?php
   ob_start();
   session_start();

   if ($_SESSION['logado'] != 1)
        header("Location: login.php");

   //include "util.php";

   $pid = "";
   if(isset($_POST["pid"]))
      $pid = $_POST["pid"];

   $fid = "";
   if(isset($_POST["fid"]))
      $fid = $_POST["fid"];

   include "connection.php";

   $sql = "UPDATE Files SET idPublication = $pid WHERE idFile = $fid";
   $exe = mysql_query($sql, $myrmconn) or print(mysql_error());
 
   //echo $sql;
   header("Location: myrm.php");
?>
