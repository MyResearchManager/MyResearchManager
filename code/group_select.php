<?php
   ob_start();
   session_start();

   if ($_SESSION['logado'] != 1)
        header("Location: login.php");

   $idGroup = -1;

   if(isset($_POST["idGroup"]))
   {
      $idGroup = $_POST["idGroup"];
   }

   if($idGroup < 1)
        header("Location: logout.php");

   $id  = $_SESSION['id'];
   $gid = -1;

   // Security stuff!!

   include "connection.php";

   $sql = "SELECT idGroup as gid FROM GroupMembers WHERE idUser=$id and idGroup=$idGroup";
   $exe = mysql_query( $sql, $myrmconn) or print(mysql_error());
   if($exe != null)
   {
       if($line = mysql_fetch_array($exe))
       {
           $gid = $line['gid'];
       }
   }

   if($gid < 1)
        header("Location: logout.php");

   $_SESSION['gid'] = $gid;

   header("Location: myrm.php");
?>
