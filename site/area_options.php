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
 <title> MyResearchManager </title>

<script type='text/javascript'> 
// JavaScript code is compatible to XHTML. <![CDATA[
function checktext(box)
{ 
      var letters = /^[0-9a-zA-Z]+$/;
      if(box.value.match(letters))
      {
         return true;
      }
      else
      {
         alert('Please do not input spaces or special chars!');
         document.frm_area_create.sname.focus(); 
         return false;
      }
}
//]]>
</script>

</head>

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
 
      $sql = "SELECT DISTINCT A.idArea as aid, A.name as name FROM Areas as A, Researches as R, ResearchMembers as RM 
WHERE R.idResearch = RM.idResearch and RM.idUser = $id and R.idArea = A.idArea ORDER BY name";
      $exe = mysql_query( $sql, $myrmconn) or print(mysql_error());
      if($exe != null)
      {
          while($line = mysql_fetch_array($exe))
          {
              $areaoptions = $areaoptions. "<OPTION VALUE=\"$line[aid]\" $selecionado >$line[name] \n";
              $selecionado = "";
          }
      }

      echo $areaoptions;
?>

</SELECT>
<input type="submit" value="Select research area" name="bt_select"> 
</form>

<br><br>
<i>Or...</i> <b>create a new research area:</b><br>
<br>

<form name="frm_area_create" method="post" action="area_create.php" onSubmit="return checktext(document.frm_area_create.sname)">
Title: <input type="text" value="Research Area" name="bname" size="60"><br>
Small name (no spaces or special chars): <input type="text" value="researcharea" name="sname" size="24"><br>
<input type="submit" value="Create research area" name="bt_area_create">
</form>


<br><br>

<a href="logout.php">Logout</a>

</body>

</html>
