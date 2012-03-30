<?php
   ob_start();
   session_start();

   $_SESSION['logado'] = 0;
   $_SESSION['id'] = -1;
   $_SESSION['gid'] = -1;
 
   header("Location: index.php");
?>
