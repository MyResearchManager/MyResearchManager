<?php
   // rename this file to 'connection.php'

   $host = "localhost";
   $user = "user";
   $pass = "pwd";

   $recaptcha_public_key  = "blablabla1"; // (public) reCAPTCHA public key for your domain
   $recaptcha_private_key = "blablabla2"; // (secret) reCAPTCHA private key for your domain

   $myrmconn = mysql_connect($host, $user, $pass) or die ("Error connecting server");
   $db = mysql_select_db("myrm", $myrmconn) or mysql_close($myrmconn) and die ("Error selecting database");
 ?>
