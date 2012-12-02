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

   include "util.php";

   $rid = getResearchIdBySectionId($sid);

   // =========================
   // add more security checks!
   // =========================

   include "connection.php";

   // Destination
   $_UP['dir'] = "./files/a$area_id/r$rid/s$sid/";

   // Max file size (Bytes)
   $_UP['size'] = 1024 * 1024 * 12; // 12 MB

   // Allowed extensions
   $_UP['extensions'] = array('jpg', 'png', 'gif', 'pdf', 'zip', 
'rar', 'xls', 'doc', 'ppt', 'pps');

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
      echo "Allowed extensions: jpg, png, gif, pdf, xls, doc, ppt, pps, 
rar or zip";
   }
   else if ($_UP['size'] < $_FILES['arquivo']['size']) // File size verification
   {
      echo "File too big! Limit is 12MB.";
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
         $sql = "INSERT INTO Files (`filename`, `size`, `creation`, `visible`, `idSection`) VALUES ('$finalname', '$filesize', NOW(), '0', '$sid')";
         $exe = mysql_query($sql, $myrmconn) or print(mysql_error());

         $sql = "INSERT INTO Logs (`idUser`, `when`, `what`) VALUES ('$id', NOW(), 'File $filename created.')";
         $exe = mysql_query($sql, $myrmconn) or print(mysql_error());
      }
      else // Possibly wrong directory!
      {
         echo "Upload failed, try again!";
      }

      echo "<br><br><a href=\"myrm.php\">Back</a>";
   }
?>

