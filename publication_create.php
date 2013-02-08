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

   $journal = "";
   if(isset($_POST["journal"]))
      $journal = $_POST["journal"];

   $pdate = "";
   if(isset($_POST["date"]))
      $pdate = $_POST["date"];

   include "connection.php";

   $sql = "INSERT INTO Publications (`idSection`, `title`, `date`, `journal`) VALUES ('$sid', '$title', '$pdate', '$journal')";
   $exe = mysql_query($sql, $myrmconn) or print(mysql_error());
   $pid = mysql_insert_id();

   $sql = "INSERT INTO Logs (`when`, `what`) VALUES (NOW(), 'uid=$id added pid=$pid in sid=$sid of rid=$rid of aid=$area_id.')";
   $exe = mysql_query($sql, $myrmconn) or print(mysql_error());

   header("Location: myrm.php");
?>
