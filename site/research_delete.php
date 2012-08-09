<?php
   ob_start();
   session_start();

   if ($_SESSION['logado'] != 1)
        header("Location: login.php");

   $id = $_SESSION['id'];

   $rid = -1;
   if(isset($_GET["rid"]))
      $rid = $_GET["rid"];


   include "util.php";

   $area_id = getAreaIdByResearchId($rid);
   $gsname  = getAreaNameByAreaId($area_id);


   include "connection.php";

   $newdir = "./files/$gsname/r$rid";

   if(!rmdir($newdir))
      die("Error! Failed to delete folder: $newdir");

   //TODO: check if research is REALLY clean!! 

   $sql = "DELETE FROM Conferences WHERE idResearch='$rid'";
   $exe = mysql_query($sql, $myrmconn) or print(mysql_error());

   $sql = "DELETE FROM ResearchMembers WHERE idResearch='$rid'";
   $exe = mysql_query($sql, $myrmconn) or print(mysql_error());

   $sql = "DELETE FROM Researches WHERE idResearch='$rid'";
   $exe = mysql_query($sql, $myrmconn) or print(mysql_error());

   header("Location: myrm.php");
?>
