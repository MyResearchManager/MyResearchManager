<?php
   ob_start();
   session_start();

   if ($_SESSION['logado'] != 1)
        header("Location: login.php");

   $uid = $_SESSION['id'];

   $bname = "";
   if(isset($_POST["bname"]))
      $bname = $_POST["bname"];


   include "util.php";

   include "connection.php";


   $sql = "INSERT INTO Areas (`name`) VALUES ('$bname')";
   $exe = mysql_query($sql, $myrmconn) or print(mysql_error());
   $aid = mysql_insert_id();


   $sql = "INSERT INTO Researches (`title`, `idArea`) VALUES ('Default', '$aid')";
   $exe = mysql_query($sql, $myrmconn) or print(mysql_error());
   $rid = mysql_insert_id();


   $sql = "INSERT INTO ResearchMembers (`idResearch`, `idUser`) VALUES ('$rid', '$uid')";
   $exe = mysql_query($sql, $myrmconn) or print(mysql_error());

   header("Location: area_select.php?idArea=$aid");
?>
