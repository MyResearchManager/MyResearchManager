<?php
   ob_start();
   session_start();

   if ($_SESSION['logado'] != 1)
        header("Location: login.php");

   $idArea = -1;

   if(isset($_POST["idArea"]))
   {
      $idArea = $_POST["idArea"];
   }

   if($idArea < 1)
        header("Location: logout.php");

   $id  = $_SESSION['id'];
   $area_id = -1;

   // Security stuff!!

   include "connection.php";

   $sql = "SELECT idArea as aid FROM AreaMembers WHERE idUser=$id and idArea=$idArea";
   $exe = mysql_query( $sql, $myrmconn) or print(mysql_error());
   if($exe != null)
   {
       if($line = mysql_fetch_array($exe))
       {
           $area_id = $line['aid'];
       }
   }

   if($area_id < 1)
        header("Location: logout.php");

   $_SESSION['gid'] = $area_id;

   header("Location: myrm.php");
?>
