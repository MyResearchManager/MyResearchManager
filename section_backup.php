<?php
   ob_start();
   session_start();

   if ($_SESSION['logado'] != 1)
        header("Location: login.php");

   $id = $_SESSION['id'];

   $sid = -1;
   if(isset($_GET["sid"]))
      $sid = $_GET["sid"];


   include "util.php";

   $rid     = getResearchIdBySectionId($sid);   
   $area_id = getAreaIdByResearchId($rid);
   $sec_name= getSectionNameBySectionId($sid);

   include "connection.php";

   $dir = "./files/a$area_id/r$rid/s$sid/";

   $zip = new ZipArchive();
   $zipfile = "$sec_name.zip";

   if(file_exists($zipfile))
      unlink($zipfile);

   if ($zip->open($zipfile, ZIPARCHIVE::CREATE)!==TRUE)
      exit("cannot open <$zipfile>\n");


   $sql = "SELECT filename, visible, idSection FROM Files WHERE idSection = $sid AND (visible = $id OR visible = -1 OR
 visible = 0)"; // TODO: CHECK IF USER BELONG TO THIS SECTION!
   $exe = mysql_query( $sql, $myrmconn) or print(mysql_error());
   if($exe != null)
       while($line = mysql_fetch_array($exe))
       {
           $filename  = $line['filename'];
           if(!$zip->addFile($dir.$filename, $filename))
                die("Error adding file $filename to zip!");
           //$zip->addFromString("testfilephp.txt" . time(), "#1 This is a test string added as testfilephp.txt.\n");
       }
      
   if(!$zip->close())
      exit("cannot close <$zipfile>\n");


   if(file_exists($zipfile))
   {
      header('Content-Type: application/octet-stream');
      header('Content-Disposition: attachment; filename='.urlencode($zipfile));
      header('Content-Transfer-Encoding: binary');

      readfile($zipfile);

      unlink($zipfile);

      exit();
   }
   else
      exit("Error: $zipfile not created!");

   header("Location: myrm.php#s$sid");
?>
