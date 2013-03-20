<?php
//   ob_start();
//   session_start();

//   if ($_SESSION['logado'] != 1)
//        header("Location: login.php");

   require_once("util.php");

//   $id = $_SESSION['id'];

   $uid = -1;

   $uhash = "";

   if(isset($_GET["uhash"]))
      $uhash = $_GET["uhash"];

   $uid = getUserIdByHash($uhash);
   //echo "UID: $uid";
?>

<html>
<head>
 <title> MyResearchManager </title> </head>
<body>

<center> <h3> MyResearchManager - User </h3> </center>

<br>


<?php

      include "connection.php";
      require_once("captcha/recaptchalib.php");

      // incluir checagens!!

      $name   = "*** no title ***";
      $name   = "*** no citation ***";
      $email  = "a@b.com";
      $active = 0;
 
      $sql = "SELECT name, citation, email FROM Users WHERE idUser = $uid";
  
      $exe = mysql_query( $sql, $myrmconn) or print(mysql_error());
      if($exe != null)
          if($line = mysql_fetch_array($exe))
          {
              $name   = $line['name'];
              $citation = $line['citation'];
              $email  = $line['email'];
          }

      echo "<h2> $citation </h2>";
      echo "<h3> " . recaptcha_mailhide_html($mailhide_public_key, $mailhide_private_key, $email) . " </h3>";
      echo "<br><br>";
      echo "<b>Publications</b><br>";

      $sql = "SELECT P.idPublication as pid, P.`title` as title, P.`date` as pdate, P.`journal` as journal FROM Publications as P, PublicationMembers as PM WHERE P.idPublication=PM.idPublication and PM.idUser = $uid ORDER BY pdate DESC";

      echo "<ul>\n";  
      $exe = mysql_query( $sql, $myrmconn) or print(mysql_error());
      if($exe != null)
          while($line = mysql_fetch_array($exe))
          {
              $pid     = $line['pid'];
              $title   = $line['title'];
              $pdate   = $line['pdate'];
              $journal = $line['journal'];

              echo "<li>$title ($pdate) - <i>$journal</i><br>\n";
              echo "<b>Authors:</b> ";

              $sql2 = "SELECT `idPublicationMember`, `order`, `idUser`, `idPublication` FROM PublicationMembers WHERE idPublication = $pid ORDER BY `order`";
              $exe2 = mysql_query( $sql2, $myrmconn) or print(mysql_error());

              $num_authors = mysql_num_rows($exe2);

              if($exe2 != null)
                       while($lauthors = mysql_fetch_array($exe2))
                       {
                          $uid1  = $lauthors['idUser'];
                          $ucitation = getUserCitationByUserId($uid1);
                          $order = $lauthors['order'];
                          echo "$ucitation";
                          if($order != $num_authors)
                             echo " & ";
                       }

            $sql2 = "SELECT F.`filename` as filename, F.`idSection` as idSection FROM PublicationFiles as PF, Files as F WHERE PF.idPublication = $pid AND PF.idFile = F.idFile ORDER BY F.`filename`";
            $exe2 = mysql_query( $sql2, $myrmconn) or print(mysql_error());

            $num_files = mysql_num_rows($exe2);
            if($num_files>0)
                echo "<br><b>Files:</b> ";
            $count = 0;
            if($exe2 != null)
                       while($lfiles = mysql_fetch_array($exe2))
                       {
                          $filename = $lfiles['filename'];
                          $sid = $lfiles['idSection'];
                          $rid = getResearchIdBySectionId($sid);
                          $aid = getAreaIdByResearchId($rid);
                          echo "<a href=\"files/a$aid/r$rid/s$sid/$filename\">$filename</a>";
                          $count++;
                          if($count != $num_files)
                             echo ", ";
                       }
            if($num_files>0)
                echo "<br>";

          }
      echo "</ul>\n";

?>

<br><br>

</body>

</html>
