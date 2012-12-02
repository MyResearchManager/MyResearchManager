<?php
   ob_start();
   session_start();

   if ($_SESSION['logado'] != 1)
        header("Location: login.php");

   $uid = $_SESSION['id'];
   $aid = $_SESSION['gid'];

   $rname = "";
   if(isset($_POST["rname"]))
      $rname = $_POST["rname"];

   include "util.php";

   include "connection.php";

   $sql = "INSERT INTO Researches (`title`, `idArea`) VALUES ('$rname', '$aid')";
   $exe = mysql_query($sql, $myrmconn) or print(mysql_error());
   $rid = mysql_insert_id();


   $sql = "INSERT INTO ResearchMembers (`idResearch`, `idUser`) VALUES ('$rid', '$uid')";
   $exe = mysql_query($sql, $myrmconn) or print(mysql_error());

   header("Location: myrm.php");
?>
