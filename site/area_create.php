<?php
   ob_start();
   session_start();

   if ($_SESSION['logado'] != 1)
        header("Location: login.php");

   $uid = $_SESSION['id'];

   $sname = "";
   if(isset($_POST["sname"]))
      $sname = $_POST["sname"];

   $bname = "";
   if(isset($_POST["bname"]))
      $bname = $_POST["bname"];


   include "util.php";

   include "connection.php";

   // check if research area already exists
   $area_id = getAreaIdByAreaName($sname);
   if($area_id > 0)
      die("Error! A Research Area with this name already exists: $sname");
   

   $sql = "INSERT INTO Areas (`name`, `smallName`) VALUES ('$bname', '$sname')";
   $exe = mysql_query($sql, $myrmconn) or print(mysql_error());
   $aid = mysql_insert_id();


   $sql = "INSERT INTO Researches (`title`, `idArea`) VALUES ('Default', '$aid')";
   $exe = mysql_query($sql, $myrmconn) or print(mysql_error());
   $rid = mysql_insert_id();


   $sql = "INSERT INTO ResearchMembers (`idResearch`, `idUser`) VALUES ('$rid', '$uid')";
   $exe = mysql_query($sql, $myrmconn) or print(mysql_error());

   header("Location: area_select.php?idArea=$aid");
?>
