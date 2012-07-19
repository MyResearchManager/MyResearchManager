<?php
   ob_start();
   session_start();

   if ($_SESSION['logado'] != 1)
        header("Location: login.php");

   $id = $_SESSION['id'];

   $sid = -1;
   if(isset($_POST["sid"]))
      $sid = $_POST["sid"];


   include "util.php";

   $rid    = getResearchIdBySectionId($sid);   
   $gid    = getGroupIdByResearchId($rid);
   $gsname = getGroupNameByGroupId($gid);


   include "connection.php";

   $newdir = "./files/$gsname/r$rid/s$sid";

   if(!rmdir($newdir))
      die("Error! Failed to delete folder: $newdir");

   $sql = "DELETE FROM Sections WHERE idSection='$sid'";
   $exe = mysql_query($sql, $myrmconn) or print(mysql_error());

   header("Location: myrm.php");
}
?>
