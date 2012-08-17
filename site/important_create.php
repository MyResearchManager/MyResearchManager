<?php
   ob_start();
   session_start();

   if ($_SESSION['logado'] != 1)
        header("Location: login.php");

   $id = $_SESSION['id'];

   $cid = -1;
   if(isset($_POST["cid"]))
      $cid = $_POST["cid"];

   $description = "";
   if(isset($_POST["description"]))
      $description = $_POST["description"];

   $idate = "";
   if(isset($_POST["idate"]))
      $idate = $_POST["idate"];

   $itime = "";
   if(isset($_POST["itime"]))
      $itime = $_POST["itime"];


   include "connection.php";

   $sql = "INSERT INTO ImportantDates (`idConference`, `description`, `datetime`) VALUES ('$cid', '$description', '$idate $itime')";
   $exe = mysql_query($sql, $myrmconn) or print(mysql_error());

   header("Location: myrm.php");
?>
