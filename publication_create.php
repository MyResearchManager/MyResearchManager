<?php
   ob_start();
   session_start();

   if ($_SESSION['logado'] != 1)
        header("Location: login.php");

   $id = $_SESSION['id'];

   $sid = -1;
   if(isset($_POST["sid"]))
      $sid = $_POST["sid"];

   $title = "";
   if(isset($_POST["title"]))
      $title = $_POST["title"];

   $pdate = "";
   if(isset($_POST["date"]))
      $pdate = $_POST["date"];

   include "connection.php";

   $sql = "INSERT INTO Publications (`idSection`, `title`, `date`) VALUES ('$sid', '$title', '$pdate')";
   $exe = mysql_query($sql, $myrmconn) or print(mysql_error());

   header("Location: myrm.php");
?>
