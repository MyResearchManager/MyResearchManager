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

   $ssync = "";
   if(isset($_POST["ssync"]))
      $ssync = $_POST["ssync"];

   include "util.php";

   $area_id = getAreaIdByResearchId($rid);

   include "connection.php";

   $sql = "INSERT INTO Sections (`idResearch`, `shash`, `title`) VALUES ('$rid', SUBSTR(MD5(RAND()),1,10), '$stitle')";
   $exe = mysql_query($sql, $myrmconn) or print(mysql_error());
   $sid = mysql_insert_id();

   $newdir = "./files/a$area_id/r$rid/s$sid";
   $allok = mkdir($newdir, 0777, true); 

   if (!$allok)
      die("Error! Failed to create folder: $newdir");

   // ==============================================
   if($ssync != "") // import section data
   {
      $fh = fopen("section-data.txt", "w");
      echo "LOCATING: $ssync<br>";
      $ch = curl_init($ssync);
      curl_setopt($ch, CURLOPT_FILE, $fh);
      curl_exec($ch);
      curl_close($ch);

      $lines = count(file("section-data.txt"));
      $matchespre = file("section-data.txt");

      //echo "FOUND $lines LINES!<br>";
      //echo "$matchespre<br>ITERANDO!<br>";

      //foreach($matchespre as $line)
      //   echo "LINE: ".$line."<br>";

      $nfiles = $matchespre[2];
      echo "TOTAL FILES: $nfiles<br>";
      for($i=0; $i<$nfiles; $i++)
      {
         $file = $matchespre[ 3+2*$i ];
         $md5  = $matchespre[ 4+2*$i ];
         echo "Get file:$i ".$file." => $md5 <br>";

         $fname = basename($file);
         $localfile = "$newdir/".$fname;
         $fh = fopen($localfile, "wb");
         $ch = curl_init($file);
         curl_setopt($ch, CURLOPT_FILE, $fh);
         curl_exec($ch);
         curl_close($ch);

         $size = filesize($localfile);

         $sql = "INSERT INTO Files (`idSection`, `filename`, `checksum`, `size`) VALUES ('$sid', '$fname', '$md5', '$size')";
         $exe = mysql_query($sql, $myrmconn) or print(mysql_error());
      }

      echo "Finished!<br>";

      echo "<br><br><a href=\"myrm.php\">back</a>";
   }
   else
      header("Location: myrm.php");
?>
