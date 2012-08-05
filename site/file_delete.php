<?php
   ob_start();
   session_start();

   if ($_SESSION['logado'] != 1)
        header("Location: login.php");

   $id  = $_SESSION['id'];
   $area_id = $_SESSION['gid'];
   if ($area_id < 1)
        header("Location: logout.php");

   $fid = -1;
   if(isset($_GET["fid"]))
      $fid = $_GET["fid"];

   if ($fid < 1)
        header("Location: myrm.php");


   // =========================
   // add more security checks!
   // =========================

   include "util.php";

   $gsname = getAreaNameByAreaId($area_id);

   if ($gsname == "")
        header("Location: myrm.php");

   include "connection.php";

   $filename = "";
   $sid = -1;

   $sql = "SELECT filename, idSection as sid FROM Files WHERE idFile=$fid";
   $exe = mysql_query( $sql, $myrmconn) or print(mysql_error());
   if($exe != null)
   {
       if($line = mysql_fetch_array($exe))
       {
           $filename = $line['filename'];
           $sid      = $line['sid'];
       }
   }

   if ($filename == "")
        header("Location: myrm.php");

   $rid = getResearchIdBySectionId($sid);

   $deletefile = "./files/$gsname/r$rid/s$sid/$filename";

   $ok = unlink($deletefile);
     
   if($ok==1)
   {
         $sql = "DELETE FROM Files WHERE idFile='$fid'";
         $exe = mysql_query($sql, $myrmconn) or print(mysql_error());
         echo "<b>File '$filename' deleted successfully!</b>";
         echo "<br><br><a href=\"myrm.php\">Back</a>"; 
   }
   else
   {
       echo "<b>ERROR: Cannot delete file '$filename'</b>";
       echo "<br><br><a href=\"myrm.php\">Back</a>"; 
   }

?>

