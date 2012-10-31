<?php
   ob_start();
   session_start();

   if ($_SESSION['logado'] != 1)
        header("Location: login.php");

   $id = $_SESSION['id'];

   $rid = -1;
   if(isset($_GET["rid"]))
      $rid = $_GET["rid"];

   // ========================================
   // incluir varias checagens de seguranca!!!
   // ========================================

   if(!isset($_SESSION['rexpanded']))
      $_SESSION['rexpanded'] = array();

   $_SESSION['rexpanded'][$rid] = 1;

   header("Location: myrm.php#r$rid");
?>
