<?php
   ob_start();
   session_start();

   if ($_SESSION['logado'] != 1)
        header("Location: login.php");

   $id = $_SESSION['id'];

   $mid = -1;
   if(isset($_GET["mid"]))
      $mid = $_GET["mid"];

   require_once("util.php");

   $tid = getTaskIdByMessageId($mid);

   include "connection.php";

   $sql = "DELETE FROM TaskMessages WHERE idTaskMessage='$mid'";
   $exe = mysql_query($sql, $myrmconn) or print(mysql_error());

   header("Location: task_view.php?tid=$tid");
?>
