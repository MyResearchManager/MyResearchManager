<?php
   ob_start();
   session_start();

   if ($_SESSION['logado'] != 1)
        header("Location: login.php");

   $id  = $_SESSION['id'];

   $area_id = $_SESSION['gid'];

   if ($area_id < 1)
        header("Location: area_options.php");

   $edit = $_SESSION['edit']; 

   $edit = $edit + 1; // turn integer
   $edit = $edit - 1; // turn integer

   include "util.php";

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
 <title> MyResearchManager - Welcome! </title>

<script type="text/javascript">
<!--
function deletefile(fid)
{
   var answer = confirm("Deleting file. Are you sure?")
   if(answer)
      window.location = "file_delete.php?fid="+fid;
}
//-->
<!--
function deletesection(sid)
{
   var answer = confirm("Deleting section. Are you sure?")
   if(answer)
      window.location = "section_delete.php?sid="+sid;
}
//-->
<!--
function deleteresearch(rid)
{
   var answer = confirm("Deleting research. Are you sure?")
   if(answer)
      window.location = "research_delete.php?rid="+rid;
}
//-->
</script>

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


<center>
<?php
   if($edit == 0)
      echo "<b>In view mode</b> - <a href=\"go_edit.php\">go to edit mode</a>";
   else
      echo "<b>In edit mode</b> - <a href=\"go_view.php\">go to view mode</a>";
?>
</center>


<br>


<?php

      $areaname  = "*** no name ***";

      // Security stuff!!! TODO Improve

      $sql = "SELECT name FROM Areas WHERE idArea = $area_id";
  
      $exe = mysql_query( $sql, $myrmconn) or print(mysql_error());
      if($exe != null)
      {
          if($linha = mysql_fetch_array($exe)) // should be while!
          {
              $gname  = $linha['name'];
          }
      }

      echo "<h2> $gname (<a href=\"area_options.php\">Change area</a>) </h2>";
?>

<br>

<b>Things to remember</b><br>
<ul>
<?php

      $sql = "SELECT I.`description` as description, S.`title` as title, I.`datetime` as dt FROM Researches as R, ResearchMembers as RM, Sections as S, ImportantDates as I  WHERE RM.idUser='$id' and R.idArea='$area_id' and RM.idResearch = R.idResearch and S.idResearch = R.idResearch and I.idSection = S.idSection and I.`datetime` > NOW() ORDER BY I.`datetime`";

      $exe = mysql_query( $sql, $myrmconn) or print(mysql_error());
      if($exe != null)
          while($line = mysql_fetch_array($exe)) // should be while!
          {
              $description = $line['description'];
              $dt          = $line['dt'];
              $title       = $line['title'];

              $event = new DateTime("$dt");
              $today = new DateTime();
              $days = round(abs($event->format('U') - $today->format('U')) / (60*60*24));

              echo "<li>";
              if($days == 0)
                 echo "<font color=\"#FF0000\"><b>$title</b> - $description (<i>Today!</i>)</font>\n";
              else if($days == 1)
                 echo "<font color=\"#FF0000\"><b>$title</b> - $description (<i>Tomorrow</i>)</font>\n";
              else
                 echo "<b>$title</b> - $description (<i>In $days days</i>)";
          }
?>
</ul>

<br>

<b>Researches</b>

<br><br>

<hr><hr>

<ul>

<?php

      $sql_research = "SELECT R.idResearch as rid, R.title as title FROM 
Users 
as U, Researches as R, ResearchMembers as RM WHERE U.idUser = $id and R.idArea = $area_id and RM.idUser = U.idUser and 
RM.idResearch = R.idResearch ORDER BY title";
      $exe_research = mysql_query( $sql_research, $myrmconn) or 
print(mysql_error());
      if($exe_research != null)
      {
          $num_research = mysql_num_rows($exe_research);

          while($line_research = mysql_fetch_array($exe_research))
          {
              $rid = $line_research['rid'];
              echo "<li> <b>$line_research[title] (<a href=\"research.php?rid=$rid\">read more</a>) </b>";
              if(($num_research > 1) && ($edit==1))
                 echo "(<a href=\"#\" onclick=\"deleteresearch($rid)\">delete</a>)";
              echo "<br>\n";
              echo "with ";
              $firstnocomma = 1;      
              
              $sql = "SELECT U.idUser as uid, U.name as name, U.email as email FROM Users as U, ResearchMembers as RM WHERE RM.idResearch = $rid and U.idUser = RM.idUser order by U.name, U.email";
              $exe = mysql_query( $sql, $myrmconn) or print(mysql_error());
              if($exe != null)
                 while($line2 = mysql_fetch_array($exe))
                 {
                    $uid    = $line2['uid'];
                    $name   = $line2['name'];
                    $email  = $line2['email'];

                    if($firstnocomma==1)
                       $firstnocomma = 0;
                    else
                       echo ", ";

                    echo "<a href=\"user.php?uid=$uid\">$name</a>";
                 }

                 if($edit==1)
                 {
                     echo "<form name=\"frm_research_add_user\" method=\"post\" action=\"research_add_user.php\">";
                     echo "<input type=\"submit\" value=\"Add user to this research\" name=\"bt_research_add_user\">";
                     echo "<input type=\"hidden\" value=\"$rid\" name=\"rid\">";
                     echo "<input type=\"text\" value=\"a@b.com\" name=\"email\">";
                     echo "<i>New users will have password '123456'</i>";
                     echo "</form>";
                 }


              // ========================================================================
              // begin sections
              // ========================================================================

              $sql_sec = "SELECT `idSection` as sid, `title` FROM Sections WHERE idResearch = $rid ORDER BY `title`";
              $exe_sec = mysql_query( $sql_sec, $myrmconn) or print(mysql_error());
              if($exe_sec != null)
                 while($line_sec = mysql_fetch_array($exe_sec))
                 { 
                    $sid    = $line_sec['sid'];
                    $stitle = $line_sec['title'];

                    echo "<br>";
                    echo "<hr>";
                    echo "<b>Section: </b> $stitle ";
                    if($edit==1)
                       echo "(<a href=\"#\" onclick=\"deletesection($sid)\">delete</a>)"; 
                    echo "<br>";


                    // ------------------------------------------------------------------------
                    // IMPORTANT DATES
                    // ------------------------------------------------------------------------

                    echo "<br><b>Important Dates</b><br>";
                    echo "<ul>";
                    $sql_imp = "SELECT * FROM ImportantDates WHERE idSection = $sid order by `datetime` ASC";
                    $exe_imp = mysql_query( $sql_imp, $myrmconn) or print(mysql_error());

                    $num_impdates = mysql_num_rows($exe_imp);
                    if($num_impdates==0)
                       echo "<i>Empty</i><br>";

                    if($exe_imp != null)
                       while($line_imp = mysql_fetch_array($exe_imp))
                       {
                          $iid   = $line_imp['idImportantDate'];
                          $desc  = $line_imp['description'];
                          $dtime = $line_imp['datetime'];

                          $numdatetime = strtotime($dtime);
                          $fdtime = date("Y-m-d H:i", $numdatetime);


                          if($fdtime < date('Y-m-d H:i', time()))
                             echo "<li><s>$desc</s> - $fdtime ";
                          else
                             echo "<li>$desc - $fdtime ";

                          if($edit==1)
                             echo "(<a href=\"important_delete.php?iid=$iid\">delete</a>)";
                       }
                    echo "</ul>";

                    if($edit==1)
                    {
                       echo "<form name=\"frm_impdate_create\" method=\"post\" action=\"important_create.php\">";
                       echo "<input type=\"submit\" value=\"Create important date\" name=\"bt_impdate_create\">";
                       echo "<input type=\"hidden\" value=\"$sid\" name=\"sid\">";
                       echo "<input type=\"text\" value=\"Remember\" name=\"description\">";
                       echo "Date: <input type=\"text\" value=\"".date("Y-m-d")."\"name=\"idate\" size='10'>";
                       echo "Time: <input type=\"text\" value=\"23:59\" name=\"itime\" size='8'>";
                       echo "</form>";
                    }


                    // ------------------------------------------------------------------------
                    // LINKS
                    // ------------------------------------------------------------------------

                    echo "<br><b>Links</b><br>";
                    echo "<ul>";
                    $sql_links = "SELECT `idLink`, `name`, `url`, `idSection` FROM Links WHERE idSection = $sid";
                    $exe_links = mysql_query( $sql_links, $myrmconn) or print(mysql_error());

                    $num_links = mysql_num_rows($exe_links);
                    if($num_links==0)
                       echo "<i>Empty</i><br>";

                    if($exe_links != null)
                       while($line_links = mysql_fetch_array($exe_links))
                       {
                          $lid    = $line_links['idLink'];
                          $name   = $line_links['name'];
                          $url    = $line_links['url'];

                          echo "<li> <a href=\"$url\" target=_blank>$name</a> ";
                          if($edit==1)
                             echo "(<a href=\"link_delete.php?lid=$lid\">delete</a>)";

                       }

                    if($edit==1)
                    {
                       echo "<form name=\"frm_link_create\" method=\"post\" action=\"link_create.php\">";
                       echo "<input type=\"submit\" value=\"Create link\" name=\"bt_link_create\">";
                       echo "<input type=\"hidden\" value=\"$sid\" name=\"sid\">";
                       echo "<input type=\"text\" value=\"Name\" name=\"cname\">";
                       echo "<input type=\"text\" value=\"http://...\" name=\"curl\">";
                       echo "</form>";
                    }

                    echo "</ul>"; // Links


              // ------------------------------------------------------------------------
              // FILES
              // ------------------------------------------------------------------------

              echo "<br><b>Files</b><br>";
              echo "<ul>";
              $sql = "SELECT `idFile` as fid, `filename`, `size`, `uploadDateTime` as uploaddt, `uploadUser` as uploadu, `public` FROM Files WHERE idSection = $sid ORDER BY `filename`";
              $exe = mysql_query( $sql, $myrmconn) or print(mysql_error());

              $num_files = mysql_num_rows($exe);
       	      if($num_files==0)
       	       	 echo "<i>Empty</i><br><br>";

              if($exe != null)
                 while($linha2 = mysql_fetch_array($exe))
                 { 
                    $fid      = $linha2['fid'];
                    $filename = $linha2['filename'];
                    $filesize = $linha2['size'];
                    $uploaddt = $linha2['uploaddt'];
                    $uploadu  = $linha2['uploadu'];
                    $public   = $linha2['public'];

                    echo "<li> <a href=\"./files/a$area_id/r$rid/s$sid/$filename\">$filename</a> - <i>$filesize bytes</i> ";
                    if($edit==1)
                       echo "(<a href=\"#\" onclick=\"deletefile($fid)\">delete</a>)";
                    echo "<br>";

                    $numdatetime = strtotime($uploaddt);
                    $fdtime = date("Y-m-d H:i", $numdatetime);

                    echo "<i>Uploaded by <b>".getUserNameByUserId($uploadu)."</b> on <b>$fdtime</b></i> (";
                    if($public==1)
                      echo "<b>public</b>";
                    else
                      echo "<b>not public</b>";
                    echo ") ";
                    echo "<br style=\"margin-bottom: 1em;\" />";
                 }

              if($edit==1)
              {
                 echo "<form method=\"post\" action=\"upload_rfile.php\" enctype=\"multipart/form-data\">";
                 echo "<label>Send File:</label>";
                 echo "<input type=\"hidden\" value=\"$sid\" name=\"sid\">";
                 echo "<input type=\"file\" name=\"arquivo\">";
                 echo "<input type=\"submit\" value=\"Send file\" name=\"bt_send_file\">"; 
                 echo "</form>";
              }
              echo "</ul>"; // Files


              // ------------------------------------------------------------------------
         

              echo "<b>Dynamic Tables</b><br>";
              echo "<ul>";
              $sql = "SELECT `idDynamicTable`, `description`, `key`, `locked`, `idSection` FROM DynamicTables WHERE 
idSection = $sid";
              $exe = mysql_query( $sql, $myrmconn) or print(mysql_error());

              $num_dyntables = mysql_num_rows($exe);
              if($num_dyntables==0)
                 echo "<i>Empty</i><br>";

              if($exe != null)
                 while($linha2 = mysql_fetch_array($exe))
                 { 
                    $tid    = $linha2['idDynamicTable'];
                    $desc   = $linha2['description'];
                    $key    = $linha2['key'];
                    $locked = $linha2['locked'];

                    echo "<li> <a href=\"dtableview.php?tid=$tid\">$desc</a> (key: $key)";
                    if($edit==1)
                    {
                       if($locked!=0)
                          echo " locked (<a href=\"dtablesafeunlock.php?tid=$tid\">unlock</a>)"; // needs session security
                       else
                          echo " unlocked (<a href=\"dtablesafelock.php?tid=$tid\">lock</a>)";   // needs only key value
                    }
                    echo "<br>";
                 }

              if($edit==1)
              {
                 echo "<form name=\"frm_dtable_create\" method=\"post\" action=\"dtablecreate.php\">";
                 echo "<input type=\"submit\" value=\"Create table\" name=\"bt_dtable_create\">"; 
                 echo "<input type=\"hidden\" value=\"$rid\" name=\"rid\">";
                 echo "<input type=\"text\" value=\"Description\" name=\"desc\">";
                 echo "</form>";
              }
              echo "</ul>"; // Dynamic Tables

              // ------------------------------------------------------------------------

                 } // end sections

              echo "<br>\n";
              if($edit==1)
              {
                 echo "<form name=\"frm_sec_create\" method=\"post\" action=\"section_create.php\">";
                 echo "<input type=\"submit\" value=\"Create a new section\" name=\"bt_sec_create\">";
                 echo "<input type=\"hidden\" value=\"$rid\" name=\"rid\">";
                 echo "<input type=\"text\" value=\"Title\" name=\"stitle\">";
                 echo "<i> (create a new section for a conference, journal or group of technical reports)</i>";
                 echo "</form>\n";
              }
              // end sections
              // ========================================================================

              echo "\n</ul><hr><hr><ul>\n"; // space for new research
          }

      }

      echo "</ul>\n";
      echo "<br>";
      if($edit==1)
      {
         echo "<form name=\"frm_res_create\" method=\"post\" action=\"research_create.php\">";
         echo "<input type=\"submit\" value=\"Create a new research\" name=\"bt_res_create\">";
         echo "<input type=\"hidden\" value=\"$gid\" name=\"gid\">";
         echo "<input type=\"text\" value=\"Title\" name=\"rname\">";
         echo "<i> (create a new research related to this research area)</i>";
         echo "</form>";
      }
?>

</ul>

<a href="https://github.com/MyResearchManager/MyResearchManager/blob/master/myrmtable/MyRMTable.java">Download 
MyRMTable.java</a> (to work with dynamic tables)<br>
<br>
<br>
<center>
   <a href="version.php"><?php include "version.php"; ?></a><br>
</center>

</body>


</html>
