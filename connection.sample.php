<?php
   // rename this file to 'connection.php'

   $host = "localhost";
   $user = "user";
   $pass = "pwd";

   $myrm_domain_name = "host.com.br";

   // smtp data
   $myrm_smtp_server = "mail.$myrm_domain_name";
   $myrm_smtp_port   = 26;
   $myrm_originmail = "noreply@$myrm_domain_name";
   $myrm_originmailpwd = "0000000000";

   $recaptcha_public_key  = "blablabla1"; // (public) reCAPTCHA public key for your domain
   $recaptcha_private_key = "blablabla2"; // (secret) reCAPTCHA private key for your domain

   $mailhide_public_key   = "0123456789";
   $mailhide_private_key  = "9abcdef012";

   $myrmconn = mysql_connect($host, $user, $pass) or die ("Error connecting server");
   $db = mysql_select_db("myrm", $myrmconn) or mysql_close($myrmconn) and die ("Error selecting database");
 ?>