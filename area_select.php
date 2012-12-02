<?php
   ob_start();
   session_start();

   if ($_SESSION['logado'] != 1)
        header("Location: login.php");

   $idArea = -1;

   if(isset($_POST["idArea"]))
      $idArea = $_POST["idArea"];
   else
   if(isset($_GET["idArea"]))
      $idArea = $_GET["idArea"];

   // turn into integer (security check!)
   $idArea += 1;
   $idArea -= 1;

   if($idArea < 1)
        header("Location: logout.php");

   $id  = $_SESSION['id'];
   $area_id = -1;

   // Security stuff!!

   include "connection.php";

   $sql = "SELECT A.idArea as aid FROM Areas as A, Researches as R, ResearchMembers as RM, SectionMembers as SM, Sections as S WHERE 
A.idArea = R.idArea AND ((R.idResearch = RM.idResearch and RM.idUser = '$id' and R.idArea = '$idArea') OR
 (R.idResearch = S.idResearch and S.idSection = SM.idSection and SM.idUser = '$id' and R.idArea = '$idArea')) ORDER BY name";

   $exe = mysql_query( $sql, $myrmconn) or print(mysql_error());
   if($exe != null)
       if($line = mysql_fetch_array($exe))
           $area_id = $line['aid'];

   if($area_id < 1)
        header("Location: logout.php");

   $_SESSION['gid'] = $area_id;

   header("Location: myrm.php");
?>
