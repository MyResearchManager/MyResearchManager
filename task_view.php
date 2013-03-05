<?php
   ob_start();
   session_start();

   if ($_SESSION['logado'] != 1)
        header("Location: login.php");

   $id = $_SESSION['id'];

   $edit = $_SESSION['edit'];

   $tid = -1;

   if(isset($_GET["tid"]))
      $tid = $_GET["tid"];


?>

<html>
<head>
 <title> MyResearchManager </title> </head>
<body>

<center> <h3> MyResearchManager - View task </h3> </center>

<br>


<?php

      include "connection.php";

      $title = "*** no title ***";
      $begin = "";
      $end = "";

      $sql = "SELECT `title`, `begin`, `end` FROM `Tasks` WHERE idTask = $tid";
      $exe = mysql_query( $sql, $myrmconn) or print(mysql_error());
      if($exe != null)
          if($line = mysql_fetch_array($exe))
          {
              $title = $line['title'];
              $begin = $line['begin'];
              $end   = $line['end'];
          }

      echo "<b>Task title:</b> $title <br>";
      echo "<b>Begin:</b> $begin <br>";
      echo "<b>End:</b> $end <br>";

      echo "<b>Messages:</b>";
      $sql = "SELECT TM.idTaskMessage as mid, TM.`when` as `when`, TM.message as message, U.name as name, U.uhash as uhash FROM TaskMessages as TM, Users as U WHERE TM.idTask = $tid AND TM.idUser = U.idUser ORDER BY `when`";
      $exe = mysql_query( $sql, $myrmconn) or print(mysql_error());
      $nmsg = mysql_num_rows($exe);

      echo "($nmsg)<br>";

      if($exe != null)
          while($line = mysql_fetch_array($exe))
          {
              $mid     = $line['mid'];
              $when    = $line['when'];
              $message = $line['message'];
              $name    = $line['name'];
              $uhash   = $line['uhash'];

              echo "<br>User <a href=\"user.php?uhash=$uhash\">$name</a> wrote in $when:";
              if($edit==1)
                  echo " (<a href=\"message_delete.php?mid=$mid\">delete</a>) ";
              echo "<br>";
              echo nl2br($message);
              echo "<br><hr>";
          }

      if($edit==1)
      {
         echo "<form name=\"frm_message_create\" method=\"post\" action=\"message_create.php\">";
         echo "<input type=\"hidden\" value=\"$tid\" name=\"tid\">";
         echo "<textarea name=\"message\" rows=\"4\" cols=\"50\"></textarea><br>\n";
         echo "<input type=\"submit\" value=\"Add message\" name=\"bt_message_add\">";
         echo "</form>";
      }
      else
         echo "Not in edit mode<br>";

?>

<br><br>

<a href="myrm.php">Back</a>

</body>


</html>
