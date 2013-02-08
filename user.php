<?php
   ob_start();
   session_start();

   if ($_SESSION['logado'] != 1)
        header("Location: login.php");

   include "util.php";

   $id = $_SESSION['id'];

   $uid = -1;

   if(isset($_GET["uid"]))
   {
      $uid = $_GET["uid"];
   }
?>

<html>
<head>
 <title> MyResearchManager </title> </head>
<body>

<center> <h3> MyResearchManager - User </h3> </center>

<br>


<?php

      include "connection.php";

      // incluir checagens!!

      $name   = "*** no title ***";
      $email  = "*** no email ***";
      $active = 0;
 
      $sql = "SELECT name, email FROM Users WHERE idUser = $uid";
  
      $exe = mysql_query( $sql, $myrmconn) or print(mysql_error());
      if($exe != null)
          if($line = mysql_fetch_array($exe))
          {
              $name   = $line['name'];
              $email  = $line['email'];
          }

      echo "<h2> $name </h2>";
      echo "<h3> $email </h3>";
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
                          $uname = getUserNameByUserId($uid1);
                          $order = $lauthors['order'];
                          echo "$uname";
                          if($order != $num_authors)
                             echo ", ";
                       }
          }
      echo "</ul>\n";

?>

<br><br>

<a href="myrm.php">Back</a>

</body>


</html>
