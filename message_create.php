<?php
   ob_start();
   session_start();

   if ($_SESSION['logado'] != 1)
        header("Location: login.php");

   $id = $_SESSION['id'];

   $tid = -1;
   if(isset($_POST["tid"]))
      $tid = $_POST["tid"];

   if ($tid < 1)
        header("Location: myrm.php");


   $message = "";
   if(isset($_POST["message"]))
      $message = $_POST["message"];


   // ========================================
   // incluir varias checagens de seguranca!!!
   // ========================================

   include "connection.php";

   $sql = "INSERT TaskMessages (`idUser`, `idTask`, `when`, `message`) VALUES ('$id', '$tid', NOW(), '$message')";
   $exe = mysql_query($sql, $myrmconn) or print(mysql_error());

   header("Location: task_view.php?tid=$tid");
?>
