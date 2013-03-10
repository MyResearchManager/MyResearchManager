<?php
   // rename this file to 'connection.php'

   $host = "localhost";
   $user = "user";
   $pass = "pwd";
   $myrm_database = "myrm";

   $myrm_domain_name = "host.com.br";
   $myrm_site = "$myrm_domain_name/myrm";

   // smtp data
   //$use_mail = false;
   $myrm_smtp_server = "mail.$myrm_domain_name";
   $myrm_smtp_port   = 26;
   $myrm_originmail = "noreply@$myrm_domain_name";
   $myrm_originmailpwd = "0000000000";

   
   $use_recaptcha = false;
   $recaptcha_public_key  = "blablabla1"; // (public) reCAPTCHA public key for your domain
   $recaptcha_private_key = "blablabla2"; // (secret) reCAPTCHA private key for your domain

   $use_mailhide = false;
   $mailhide_public_key   = "0123456789";
   $mailhide_private_key  = "9abcdef012";

   $myrmconn = mysql_connect($host, $user, $pass) or die ("Error connecting server");
   $db = mysql_select_db($myrm_database, $myrmconn) or mysql_close($myrmconn) and die ("Error selecting database");
 ?>
