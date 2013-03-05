<?php
   ob_start();
   session_start();

   if ($_SESSION['logado'] != 1)
        header("Location: login.php");

   $id = $_SESSION['id'];

   $tid = -1;
   if(isset($_GET["tid"]))
      $tid = $_GET["tid"];

   include "connection.php";

   $sql = "DELETE FROM Tasks WHERE idTask='$tid'";
   $exe = mysql_query($sql, $myrmconn) or print(mysql_error());

   header("Location: myrm.php");
?>
