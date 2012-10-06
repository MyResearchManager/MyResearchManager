<?php
   ob_start();
   session_start();

   if ($_SESSION['logado'] != 1)
        header("Location: login.php");

   $id = $_SESSION['id'];

   $sid = -1;
   if(isset($_GET["sid"]))
      $sid = $_GET["sid"];

   // ========================================
   // incluir varias checagens de seguranca!!!
   // ========================================

   if(!isset($_SESSION['sexpanded']))
      $_SESSION['sexpanded'] = array();

   $_SESSION['sexpanded'][$sid] = 1;

   header("Location: myrm.php");
?>
