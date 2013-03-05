<?php
   ob_start();
   session_start();

   if ($_SESSION['logado'] != 1)
        header("Location: login.php");

   $id = $_SESSION['id'];

   $rid = -1;
   if(isset($_POST["rid"]))
   {
      $rid = $_POST["rid"];
   }

   $htitle = "";
   if(isset($_POST["htitle"]))
   {
      $htitle = $_POST["htitle"];
   }

   include "connection.php";

   $sql = "INSERT INTO Schedules (`idResearch`, `title`) VALUES ('$rid', '$htitle')";
   $exe = mysql_query($sql, $myrmconn) or print(mysql_error());
   $hid = mysql_insert_id();

   header("Location: myrm.php#h$hid");
?>
