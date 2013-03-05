<?php
   ob_start();
   session_start();

   if ($_SESSION['logado'] != 1)
        header("Location: login.php");

   $id = $_SESSION['id'];

   $hid = -1;
   if(isset($_GET["hid"]))
      $hid = $_GET["hid"];

   include "connection.php";

   $sql = "DELETE FROM Schedules WHERE idSchedule='$hid'";
   $exe = mysql_query($sql, $myrmconn) or print(mysql_error());

   header("Location: myrm.php");
?>
