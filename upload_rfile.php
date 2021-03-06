<?php
   ob_start();
   session_start();

   if ($_SESSION['logado'] != 1)
        header("Location: login.php");

   $id  = $_SESSION['id'];
   $area_id = $_SESSION['gid'];
   if ($area_id < 1)
        header("Location: logout.php");

   $sid = -1;

   if(isset($_POST["sid"]))
      $sid = $_POST["sid"];

   if ($sid < 1)
        header("Location: myrm.php");

   $description = "";
   if(isset($_POST["description"]))
      $description = $_POST["description"];

   include "util.php";

   $rid = getResearchIdBySectionId($sid);

   // =========================
   // add more security checks!
   // =========================

   include "connection.php";

   // Destination
   $_UP['dir'] = "./files/a$area_id/r$rid/s$sid/";

   // Max file size (Bytes)
   $_UP['size'] = 1024 * 1024 * 25; // 25 MB

   // Allowed extensions
   $_UP['extensions'] = array('jpg', 'png', 'gif', 'pdf', 'zip', 
'rar', 'xls', 'doc', 'ppt', 'pps', 'txt', 'tar', 'gz', 'tar.gz');

   // Rename file?
   $_UP['rename'] = false;

   // Error types
   $_UP['errors'][0] = 'No error!';
   $_UP['errors'][1] = 'Uploaded file bigger than PHP limit!';
   $_UP['errors'][2] = 'Uploaded file bigger than especified size in HTML!';
   $_UP['errors'][3] = 'Partially uploaded file!';
   $_UP['errors'][4] = 'Upload NOT finished successfully!';

   if ($_FILES['arquivo']['error'] != 0)
   {
      die("Upload error:<br />" . $_UP['errors'][$_FILES['arquivo']['error']]);
      return;
   }

   // ==========
   // NO ERROR!!
   // ==========

   
   $extension = strtolower(end(explode('.', $_FILES['arquivo']['name'])));
   if (array_search($extension, $_UP['extensions']) === false) // File extension verification
   {
      //echo "Allowed extensions: jpg, png, gif, pdf, xls, doc, ppt, pps, rar or zip";
      echo "Allowed extensions: ";
      for($i=0; $i<sizeof($_UP['extensions']); $i++)
         echo $_UP['extensions'][$i]." - ";
   }
   else if ($_UP['size'] < $_FILES['arquivo']['size']) // File size verification
   {
      echo "File too big! Limit is 25MB.";
   }
   else // file ok! check name and try to move!!
   {
      // Rename file?
      if ($_UP['rename'] == true)
      {
         $finalname = time().'.jpg';
      }
      else
      {
         $finalname = $_FILES['arquivo']['name'];
      }

      // check name
      $replace = 0;

      $sql = "SELECT count(*) as total FROM Files WHERE idSection='$sid' and filename='$finalname'";
      $exe = mysql_query( $sql, $myrmconn) or print(mysql_error());
      if($exe != null)
      {
          if($line = mysql_fetch_array($exe))
          {
             $mytotal = $line['total'];
             if($mytotal > 0)
             {
                $replace = 1;
                echo "<b>ERROR: File '$finalname' already exists!</b>";
                echo "<br><br><a href=\"myrm.php\">Back</a>";
                return;
             }
          }
      }

      // Move ok?
      if (move_uploaded_file($_FILES['arquivo']['tmp_name'], $_UP['dir'] . $finalname))
      {
         echo "File uploaded successfully!";
         echo '<br /><a href="' . $_UP['dir'] . $finalname . '">View uploaded file</a>';

         include "connection.php";

         $filesize = $_FILES['arquivo']['size'];
         $fullfile = $_UP['dir'].$finalname;
         $md5 = md5_file($fullfile);

         $sql = "INSERT INTO Files (`filename`, `description`, `size`, `checksum`, `creation`, `visible`, `idSection`) VALUES ('$finalname', '$description', '$filesize', '$md5', NOW(), '0', '$sid')";
         $exe = mysql_query($sql, $myrmconn) or print(mysql_error());
         $fid = mysql_insert_id();

         $sql = "INSERT INTO Logs (`when`, `what`) VALUES (NOW(), 'uid=$id added fid=$fid in sid=$sid of rid=$rid of aid=$area_id.')";
         $exe = mysql_query($sql, $myrmconn) or print(mysql_error());
      }
      else // Possibly wrong directory!
      {
         echo "Upload failed, try again!";
      }

      echo "<br><br><a href=\"myrm.php\">Back</a>";
   }
?>

