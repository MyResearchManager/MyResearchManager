<?php
   ob_start();
   session_start();

   if ($_SESSION['logado'] != 1)
        header("Location: login.php");

   //include "util.php";

   $pid = "";
   if(isset($_GET["pid"]))
      $pid = $_GET["pid"];
   $pid++;
   $pid--;

   $fid = "";
   if(isset($_GET["fid"]))
      $fid = $_GET["fid"];
   $fid++;
   $fid--;

   include "connection.php";

   $sql = "DELETE FROM PublicationFiles WHERE idPublication = '$pid' AND idFile = '$fid'";
   $exe = mysql_query($sql, $myrmconn) or print(mysql_error());
 
   //echo $sql;
   header("Location: myrm.php");
?>
