<?php
   ob_start();
   session_start();

   if ($_SESSION['logado'] != 1)
        header("Location: login.php");

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
?>

<br><br>

<a href="myrm.php">Back</a>

</body>


</html>
