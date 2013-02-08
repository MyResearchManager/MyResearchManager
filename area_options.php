<?php
   ob_start();
   session_start();

   if ($_SESSION['logado'] != 1)
        header("Location: login.php");

   $id  = $_SESSION['id'];

   $area_id = $_SESSION['gid'];

   include "connection.php";

   include "util.php";

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
 
      $sql = "SELECT DISTINCT A.idArea as aid, A.name as name FROM Areas as A, Researches as R, ResearchMembers as RM, 
SectionMembers as SM, Sections as S WHERE (R.idResearch = RM.idResearch and RM.idUser = $id and R.idArea = A.idArea) OR 
(R.idResearch = S.idResearch and S.idSection = SM.idSection and SM.idUser = $id and R.idArea = A.idArea) ORDER BY name";
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
<b>User log</b>
<?php
   $limit = 10;
   echo " (<i>LIMIT=$limit</i>)<br>";
?>
<table>
<tr><td><b>Date</b></td><td><b>What</b></td></tr>
<?php

   $sql = "SELECT `when`, `what` FROM Logs WHERE what LIKE '%uid=$id%' ORDER BY `when` DESC LIMIT $limit";
   $exe = mysql_query( $sql, $myrmconn) or print(mysql_error());
   if($exe != null)
   {
       while($line = mysql_fetch_array($exe)) // should be while!
       {
           echo "<tr>";
           $when  = $line['when'];
           echo "<td>$when</td>";
           $what  = $line['what'];

           $uid   = preg_replace('/(.*)(uid)=(\d+)(.*)/i', '${3}', $what); // uid
           $uid++; $uid--; // test number
           if($uid != 0)
           {
              $u_name = getUserNameByUserId($uid);
              $what  = preg_replace("/(uid)=($uid)/i", "<a href=\"user.php?uid=$uid\">User:$u_name</a>", $what); // sid
           }

           $fid   = preg_replace('/(.*)(fid)=(\d+)(.*)/i', '${3}', $what); // fid
           $fid++; $fid--; // test number
           if($fid != 0)
           {
              //$f_name = getUserNameByUserId($fid);
              $what  = preg_replace("/(fid)=($fid)/i", "FILE=$fid", $what); // fid
           }

           $sid   = preg_replace('/(.*)(sid)=(\d+)(.*)/i', '${3}', $what); // sid
           $sid++; $sid--; // test number
           if($sid != 0)
           {
              $s_name = getSectionNameBySectionId($sid);
              $what  = preg_replace("/(sid)=($sid)/i", "<a href=\"myrm.php#sid=$sid\">Section:$s_name</a>", $what); // sid
           }
           $rid   = preg_replace('/(.*)(rid)=(\d+)(.*)/i', '${3}', $what); // rid
           $rid++; $rid--; // test number
           if($rid != 0)
           {
              $r_name = getResearchNameByResearchId($rid);
              $what  = preg_replace("/(rid)=($rid)/i", "<a href=\"myrm.php#rid=$rid\">Research:$r_name</a>", $what); // rid
           }
           $aid   = preg_replace('/(.*)(aid)=(\d+)(.*)/i', '${3}', $what); // aid
           $aid++; $aid--; // test number
           if($aid != 0)
           {
              $a_name = getAreaNameByAreaId($aid);
              $what  = preg_replace("/(aid)=($aid)/i", "<a href=\"myrm.php#aid=$aid\">Area:$a_name</a>", $what); // rid
           }
           echo "<td>$what</td>";
           echo "</tr>";
       }
   }
?>
</table>

<br><br>

<center>
   <a href="version.php"><?php include "version.php"; ?></a><br>
</center>

</body>

</html>
