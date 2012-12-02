<?php
   ob_start();
   session_start();

   if ($_SESSION['logado'] != 1)
        header("Location: login.php");

   $rid = "";
   if(isset($_POST["rid"]))
      $rid = $_POST["rid"];

   $email = "";
   if(isset($_POST["email"]))
      $email = strtolower(trim($_POST["email"]));

   if((strcmp($email, "") == 0) || (strcmp($email, "a@b.com") == 0)) // add more checks!
   {
      echo "Invalid email address!<br>";
      echo "<a href=\"myrm.php\">Back</a><br>";
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

   $sql = "SELECT count(*) as c FROM ResearchMembers WHERE idResearch=$rid AND idUser=$uid";
   $exe = mysql_query($sql, $myrmconn) or print(mysql_error());
   if($exe != null)
      if($row = mysql_fetch_array($exe))
         $rmcount = $row['c'];

   if($rmcount < 1)
   {
      $sql = "INSERT INTO ResearchMembers (`idResearch`, `idUser`) VALUES ('$rid', '$uid')";
      $exe =  mysql_query($sql, $myrmconn) or print(mysql_error());
      header("Location: myrm.php");
   }
   else
   {
      echo "User already member of research!<br>";
      echo "<a href=\"myrm.php\">Back</a><br>";
   }
?>
