<?php
   ob_start();
   session_start();

   if ($_SESSION['logado'] != 1)
        header("Location: login.php");

   $id  = $_SESSION['id'];
   $gid = $_SESSION['gid'];
   if ($gid < 1)
        header("Location: logout.php");

   $fid = -1;

   if(isset($_GET["fid"]))
   {
      $fid = $_GET["fid"];
   }

   if ($fid < 1)
        header("Location: myrm.php");


   // =========================
   // add more security checks!
   // =========================

   include "connection.php";

   $gsname = "";

   $sql = "SELECT smallName as gsname FROM Groups WHERE idGroup=$gid";
   $exe = mysql_query( $sql, $myrmconn) or print(mysql_error());
   if($exe != null)
   {
       if($line = mysql_fetch_array($exe))
       {
           $gsname = $line['gsname'];
       }
   }

   if ($gsname == "")
        header("Location: myrm.php");

   $filename = "";
   $rid = -1;

   $sql = "SELECT filename, idResearch as rid FROM Files WHERE idFile=$fid";
   $exe = mysql_query( $sql, $myrmconn) or print(mysql_error());
   if($exe != null)
   {
       if($line = mysql_fetch_array($exe))
       {
           $filename = $line['filename'];
           $rid      = $line['rid'];
       }
   }

   if ($filename == "")
        header("Location: myrm.php");


   $deletefile = "./$gsname/r$rid/$filename";

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

