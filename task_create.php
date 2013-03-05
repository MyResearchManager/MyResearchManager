<?php
   ob_start();
   session_start();

   if ($_SESSION['logado'] != 1)
        header("Location: login.php");

   $id = $_SESSION['id'];

   $hid = -1;
   if(isset($_POST["hid"]))
      $hid = $_POST["hid"];

   $title = "";
   if(isset($_POST["ttitle"]))
      $title = $_POST["ttitle"];

   $begin = "";
   if(isset($_POST["begin"]))
      $begin = $_POST["begin"];

   $end = "";
   if(isset($_POST["end"]))
      $end = $_POST["end"];


   include "connection.php";

   $sql = "INSERT INTO Tasks (`idSchedule`, `title`, `begin`, `end`) VALUES ('$hid', '$title', '$begin', '$end')";
   $exe = mysql_query($sql, $myrmconn) or print(mysql_error());

   header("Location: myrm.php");
?>
