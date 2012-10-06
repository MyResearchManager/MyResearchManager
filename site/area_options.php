<?php
   ob_start();
   session_start();

   if ($_SESSION['logado'] != 1)
        header("Location: login.php");

   $id  = $_SESSION['id'];

   $area_id = $_SESSION['gid'];

   include "connection.php";

   $fullname  = "*** no name ***";
   $email     = "*** no email ***";

   $sql = "SELECT name, email FROM Users WHERE idUser = $id";

   $exe = mysql_query( $sql, $myrmconn) or print(mysql_error());
   if($exe != null)
   {
       if($linha = mysql_fetch_array($exe)) // should be while!
       {
           $fullname  = $linha['name'];
           $email     = strtolower(trim($linha['email']));
       }
   }


?>

<html>
<head>
 <title> MyResearchManager </title>
 <link rel="shortcut icon" href="myrm.ico"/>
</head>

<body>

<h3 align="right">
  <?php echo $fullname; ?>
  <a href="http://www.gravatar.com" target=_blank >
    <img height="40" src="http://www.gravatar.com/avatar/<?php echo md5($email);?>">
  </a>&nbsp;<br>

  <a href="profile.php">edit profile</a>
  <a href="logout.php">logout</a>
</h3>


<center>
  <a target="_blank" href="https://github.com/MyResearchManager/MyResearchManager"><img src="myrm.jpg" width="350"></a><br><br>
</center>

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

<b>Things to remember</b><br>
<ul>

<?php
      $sql = "SELECT A.`name` as aname, I.`description` as description, S.`title` as title, I.`datetime` as dt FROM
Areas as A, Researches as R, ResearchMembers as RM, Sections as S, ImportantDates as I  WHERE
R.idArea=A.idArea and RM.idUser='$id' and RM.idResearch = R.idResearch and 
S.idResearch = R.idResearch and I.idSection = S.idSection and I.`datetime` > NOW() ORDER BY I.`datetime`";

      $exe = mysql_query( $sql, $myrmconn) or print(mysql_error());
      if($exe != null)
          while($line = mysql_fetch_array($exe)) // should be while!
          {
              $description = $line['description'];
              $dt          = $line['dt'];
              $title	   = $line['title'];
              $aname       = $line['aname'];

              $event = new DateTime("$dt");
              $today = new DateTime();
              $days = round(abs($event->format('U') - $today->format('U')) / (60*60*24));

              echo "<li>";
              if($days == 0)
                 echo "<font color=\"#FF0000\"><b>$aname/<i>$title</i></b> - $description (<i>Today!</i>)</font>\n";
              else if($days == 1)
                 echo "<font color=\"#FF0000\"><b>$aname/<i>$title</i></b> - $description (<i>Tomorrow</i>)</font>\n";
              else
                 echo "<b>$aname/<i>$title</i></b> - $description (<i>In $days days</i>)";
          }
?>
</ul>

<br><br>
<i>Or...</i> <b>create a new research area:</b><br>
<br>

<form name="frm_area_create" method="post" action="area_create.php">
Title: <input type="text" value="Research Area" name="bname" size="60"><br>
<input type="submit" value="Create research area" name="bt_area_create">
</form>


<br><br>

<center>
   <a href="version.php"><?php include "version.php"; ?></a><br>
</center>

</body>

</html>
