<?php
   ob_start();
   session_start();

   if ($_SESSION['logado'] != 1)
        header("Location: login.php");

   $id  = $_SESSION['id'];

   $area_id = $_SESSION['gid'];
?>

<html>
<head>
 <title> MyResearchManager </title> </head>
<body>

<center> <h3> MyResearchManager - Research Area </h3> </center>

<br>

<h2 align="center"> Select the research area </h2>
<form name="frm_area_select" method="post" action="area_select.php">
<SELECT NAME="idArea">

<?php

      include "connection.php";

      $areaoptions = "";
      $selecionado = "SELECTED"; // select first
 
      $sql = "SELECT A.idArea as aid, A.name as gname, A.smallName as gsname FROM Users as U, Areas as A, AreaMembers as AM 
WHERE U.idUser = $id and AM.idUser = U.idUser and AM.idArea = A.idArea";
      $exe = mysql_query( $sql, $myrmconn) or print(mysql_error());
      if($exe != null)
      {
          while($line = mysql_fetch_array($exe))
          {
              $areaoptions = $areaoptions. "<OPTION VALUE=\"$line[aid]\" $selecionado >$line[gname] \n";
              $selecionado = "";
          }
      }

      echo $areaoptions;
?>

</SELECT>
<input type="submit" value="Select research area" name="bt_select"> 
</form>

<br>
Or... <a href="group_create.php">Create a new research area</a> (In construction!)
<br>

<br><br>

<a href="logout.php">Logout</a>

</body>

</html>
