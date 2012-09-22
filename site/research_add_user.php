<?php
   ob_start();
   session_start();

   if ($_SESSION['logado'] != 1)
        header("Location: login.php");

   $rid = "";
   if(isset($_POST["rid"]))
      $rid = $_POST["rid"];

   $email = "";
   if(isset($_POST["email"]))
      $email = strtolower(trim($_POST["email"]));

   if(($email == "") || ($email == "a@b.com")) // add more checks!
      header("Location: myrm.php");

   include "util.php";

   $uid = getUserIdByEmail($email);

   if($uid < 0) // new user
   {
      include "connection.php";

      $sql = "INSERT INTO Users (`name`, `email`, `password`) VALUES ('$email', '$email', MD5('123456'))";
      $exe = mysql_query($sql, $myrmconn) or print(mysql_error());
      $uid = mysql_insert_id();
   }

   $sql = "INSERT INTO ResearchMembers (`idResearch`, `idUser`) VALUES ('$rid', '$uid')";
   $exe = mysql_query($sql, $myrmconn) or print(mysql_error());

   header("Location: myrm.php");
?>
