<?php
   ob_start();
   session_start();

   //include 'functions.php';
   //sec_session_start();

   session_destroy();

   header("Location: index.php");
?>
