<?php
   ob_start();
   session_start();

   if ($_SESSION['logado'] != 1)
        header("Location: login.php");

   $sid = "";
   if(isset($_POST["sid"]))
      $sid = $_POST["sid"];

   $email = "";
   if(isset($_POST["email"]))
      $email = strtolower(trim($_POST["email"]));

   if((strcmp($email, "") == 0) || (strcmp($email, "a@b.com") == 0)) // add more checks!
   {
      echo "Invalid email address!<br>";
      echo "<a href=\"myrm.php#s$sid\">Back</a><br>";
      die("");
   }
   
   include "util.php";

   $uid = getUserIdByEmail($email);

   include "connection.php";

   if($uid < 0) // new user
   {
      $salt = substr(md5(uniqid(rand(), true)), 0, 4);
      $sql = "INSERT INTO Users (`name`, `email`, `password`, `salt`) VALUES ('$email', '$email', MD5(CONCAT('12345','$salt')), 
'$salt')";
      $exe = mysql_query($sql, $myrmconn) or print(mysql_error());
      $uid = mysql_insert_id();
   }

   $rmcount = 0; 

   $sql = "SELECT count(*) as c FROM SectionMembers WHERE idSection=$sid AND idUser=$uid";
   $exe = mysql_query($sql, $myrmconn) or print(mysql_error());
   if($exe != null)
      if($row = mysql_fetch_array($exe))
         $rmcount = $row['c'];

   if($rmcount < 1)
   {
      $sql = "INSERT INTO SectionMembers (`idSection`, `idUser`) VALUES ('$sid', '$uid')";
      $exe =  mysql_query($sql, $myrmconn) or print(mysql_error());
      header("Location: myrm.php#s$sid");
   }
   else
   {
      echo "User already member of section!<br>";
      echo "<a href=\"myrm.php#s$sid\">Back</a><br>";
   }
?>
