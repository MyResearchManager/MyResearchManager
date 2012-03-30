<?php
   ob_start();
   session_start();

   if ($_SESSION['logado'] != 1)
        header("Location: login.php");

   $id = $_SESSION['id'];

   $rid = -1;

   if(isset($_GET["rid"]))
   {
      $rid = $_GET["rid"];
   }
?>

<html>
<head>
 <title> MyResearchManager </title> </head>
<body>

<center> <h3> MyResearchManager - Research </h3> </center>

<br>


<?php

      include "connection.php";

      // incluir checagens!!

      $title    = "*** no title ***";
      $abstract = "(...)";
 
      $sql = "SELECT title, abstract FROM Researches WHERE idResearch = $rid";
  
      $exe = mysql_query( $sql, $myrmconn) or print(mysql_error());
      if($exe != null)
      {
          if($linha = mysql_fetch_array($exe))
          {
              $title     = $linha['title'];
              $abstract  = $linha['abstract'];
          }
      }

      echo "<h2> $title (#$rid) </h2>";
      echo "$abstract";
?>

<br><br>

<a href="myrm.php">Back</a>

</body>


</html>
