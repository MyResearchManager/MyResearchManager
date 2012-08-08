<?php
   ob_start();
   session_start();

   if ($_SESSION['logado'] != 1)
        header("Location: login.php");

   $id  = $_SESSION['id'];

   $area_id = $_SESSION['gid'];

   if ($area_id < 1)
        header("Location: area_options.php");

   include "connection.php";

   $fullname = "*** no name ***";
   $email    = "*** no email ***";

   $sql = "SELECT name, email FROM Users WHERE idUser = $id";
  
   $exe = mysql_query( $sql, $myrmconn) or print(mysql_error());
   if($exe != null)
       if($line = mysql_fetch_array($exe)) // should be while!
       {
          $fullname  = $line['name'];
          $email     = $line['email'];
       }
?>

<html>
<head>
 <title> MyResearchManager - Welcome! </title>
</head>
<body>

<center> <a target="_blank" href="https://github.com/MyResearchManager/MyResearchManager"> <img src="myrm.jpg" width="350"> </a> 
</center>

<center> <h3> Welcome <i><u><?php echo $fullname; ?></u></i> </h3> </center>

<br>


<b>Edit profile</b><br>

<hr><hr>

<b>Email: <?php echo $email; ?> </b>

<br><br>

<b>Change password:</b>

<form name="frm_change_pwd" method="post" action="change_password.php">
Current password: <input type="password" name="pwd_current">
<br><br>
New password: <input type="password" name="pwd_new1"><br>
Repeat password: <input type="password" name="pwd_new2"><br>
<br>
<input type="submit" value="Change password" name="bt_change_pwd"> 
</form>

<br><br>

<a href="myrm.php">Back to main page</a><br>

<br><br>

<a href="logout.php">Logout</a><br>

</body>

</html>
