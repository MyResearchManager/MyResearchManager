<?php
   // rename this file to 'connection.php'

   $host = "localhost";
   $user = "user";
   $pass = "pwd";

   $myrmconn = mysql_connect($host, $user, $pass) or die ("Error connecting server");
   $db = mysql_select_db("myrm", $myrmconn) or mysql_close($myrmconn) and die ("Error selecting database");
 ?>
