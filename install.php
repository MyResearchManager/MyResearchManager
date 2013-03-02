<html>
<head>
 <title> MyResearchManager Installation </title>
 <link rel="shortcut icon" href="myrm.ico"/>
</head>

<body>

   <h1>INSTALLING MYRESEARCHMANAGER...</h1>
   <a href="version.php"><?php include "version.php"; ?></a>
   <br><br>

<?php
   include "connection.php";

   $sql = "CREATE TABLE IF NOT EXISTS Testes ( idTeste varchar(255) )";
   $exe = mysql_query( $sql, $myrmconn) or print(mysql_error());
   if(!$exe)
      die("Error (1) in MySQL Connection: first configure connection.php (copy from connection.sample.php)!");


   $sql = "DROP TABLE Testes";
   $exe = mysql_query( $sql, $myrmconn) or print(mysql_error());
   if(!$exe)
      die("Error (2) in MySQL Connection: first configure connection.php (copy from connection.sample.php)!");


   $newdir = "files";
   $allok = true;
   if (!file_exists($newdir))
      $allok = mkdir($newdir, 0777, true); 

   if (!$allok)
      die("Error (3)! Failed to create folder: $newdir");

   echo "<br><b>Okay! Will create tables</b><br>";

   // Temporary variable, used to store current query
   $templine = '';
   // Read in entire file
   $lines = file("./database/myrm.sql");
   // Loop through each line
   foreach ($lines as $line)
   {
      // Skip it if it's a comment
      if (substr($line, 0, 2) == '--' || $line == '')
          continue;
 
      // Add this line to the current segment
      $templine .= $line;
      // If it has a semicolon at the end, it's the end of the query
      if (substr(trim($line), -1, 1) == ';')
      {
          // Perform the query
          mysql_query($templine, $myrmconn) or print('Error performing query \'<strong>' . $templine . '\': ' . mysql_error() . '<br /><br />');
          // Reset temp variable to empty
          $templine = '';
      }
   }


   echo "<br><b>Okay! Will add a admin user</b><br>";

   $salt = substr(md5(uniqid(rand(), true)), 0, 4);
   $email = "admin@local";
   $pwd   = "12345";
   $sql = "INSERT INTO Users (`name`, `email`, `password`, `salt`, `confirmationCode`) VALUES ('$email', '$email', MD5(CONCAT('$pwd','$salt')), '$salt', MD5(RAND()))";
   $exe = mysql_query($sql, $myrmconn) or print(mysql_error());

   if(!$exe)
      die("Error (5) in MySQL Connection: first configure connection.php (copy from connection.sample.php)!");

   echo "Finished configuration with email: '$email' and password '$pwd'<br>";

   echo "<h1>PLEASE, install.php FROM YOUR SERVER!</h1>";
?>

</body>

</html>
