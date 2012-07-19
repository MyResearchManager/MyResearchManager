<?php
   ob_start();
   session_start();

   if ($_SESSION['logado'] != 1)
        header("Location: login.php");

   $id = $_SESSION['id'];

   $rid = -1;
   if(isset($_POST["rid"]))
   {
      $rid = $_POST["rid"];
   }

   $stitle = "";
   if(isset($_POST["stitle"]))
   {
      $stitle = $_POST["stitle"];
   }

   include "util.php";

   $gid    = getGroupIdByResearchId($rid);
   $gsname = getGroupNameByGroupId($gid);


   include "connection.php";

   $sql = "INSERT INTO Sections (`idResearch`, `title`) VALUES ('$rid', '$stitle')";
   $exe = mysql_query($sql, $myrmconn) or print(mysql_error());
   $sid = mysql_insert_id();

   $newdir = "./files/$gsname/r$rid/s$sid";
   $allok = mkdir($newdir, 0777, true); 

   if (!$allok) {
      die("Error! Failed to create folder: $newdir");

   header("Location: myrm.php");
}
?>
