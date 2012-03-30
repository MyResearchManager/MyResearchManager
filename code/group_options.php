<?php
   ob_start();
   session_start();

   if ($_SESSION['logado'] != 1)
        header("Location: login.php");

   $id  = $_SESSION['id'];

   $gid = $_SESSION['gid'];
?>

<html>
<head>
 <title> MyResearchManager </title> </head>
<body>

<center> <h3> MyResearchManager - Group Options </h3> </center>

<br>

<h2 align="center"> Select your group </h2>
<form name="frm_group_select" method="post" action="group_select.php">
<SELECT NAME="idGroup">

<?php

      include "connection.php";

      $groupoptions = "";
      $selecionado = "SELECTED"; // select first
 
      $sql = "SELECT G.idGroup as gid, G.name as gname, G.smallName as gsname FROM Users as U, Groups as G, GroupMembers as GM WHERE U.idUser = $id and GM.idUser = U.idUser and GM.idGroup = G.idGroup";
      $exe = mysql_query( $sql, $myrmconn) or print(mysql_error());
      if($exe != null)
      {
          while($line = mysql_fetch_array($exe))
          {
              $groupoptions = $groupoptions. "<OPTION VALUE=\"$line[gid]\" $selecionado >$line[gname] \n";
              $selecionado = "";
          }
      }

      echo $groupoptions;
?>

</SELECT>
<input type="submit" value="Select group" name="bt_select"> 
</form>

<br>
Or... <a href="group_create.php"> Create a new group </a>
<br>

<br><br>

<a href="logout.php">Logout</a>

</body>

</html>
