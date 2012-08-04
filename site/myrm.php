<?php
   ob_start();
   session_start();

   if ($_SESSION['logado'] != 1)
        header("Location: login.php");

   $id  = $_SESSION['id'];

   $gid = $_SESSION['gid'];

   if ($_SESSION['gid'] < 1)
        header("Location: group_options.php");

   include "connection.php";

   $fullname  = "*** no name ***";

   $sql = "SELECT name FROM Users WHERE idUser = $id";
  
   $exe = mysql_query( $sql, $myrmconn) or print(mysql_error());
   if($exe != null)
   {
       if($linha = mysql_fetch_array($exe)) // should be while!
       {
           $fullname  = $linha['name'];
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


</script>

</head>
<body>

<center> <img src="myrm.jpg" width="350"> </center>

<center> <h3> Welcome <i><u><?php echo $fullname; ?></u></i> </h3> </center>

<br>


<?php

      $gname  = "*** no name ***";
      $gsname = "";

      // Security stuff!!! TODO Improve

      $sql = "SELECT name as gname, smallName as gsname FROM Groups WHERE idGroup = $gid";
  
      $exe = mysql_query( $sql, $myrmconn) or print(mysql_error());
      if($exe != null)
      {
          if($linha = mysql_fetch_array($exe)) // should be while!
          {
              $gname  = $linha['gname'];
              $gsname = $linha['gsname'];
          }
      }

      echo "<h2> $gname [<a href=\"./$gsname/\">$gsname</a>] (<a href=\"group_options.php\">Change area</a>) </h2>";
?>

<b>Researches</b><br>

<?php echo "<hr>\n"; ?>

<ul>

<?php

      $sql_research = "SELECT R.idResearch as rid, R.title as title FROM 
Users 
as U, Researches as R, ResearchMembers as RM WHERE U.idUser = $id and R.idGroup = $gid and RM.idUser = U.idUser and RM.idResearch = R.idResearch";
      $exe_research = mysql_query( $sql_research, $myrmconn) or 
print(mysql_error());
      if($exe_research != null)
      {
          while($line_research = mysql_fetch_array($exe_research))
          {
              $rid = $line_research['rid'];
              echo "<li> <b>$line_research[title] (<a 
href=\"research.php?rid=$rid\">read more</a>) (#$rid) </b> <br>";
              echo "with ";
              $firstnocomma = 1;      
              
              $sql = "SELECT U.idUser as uid, U.name as name, U.email as email, U.active as 
active FROM Users as U, ResearchMembers as RM WHERE RM.idResearch = $rid and U.idUser = 
RM.idUser order by U.name, U.email";
              $exe = mysql_query( $sql, $myrmconn) or print(mysql_error());
              if($exe != null)
                 while($line2 = mysql_fetch_array($exe))
                 {
                    $uid    = $line2['uid'];
                    $name   = $line2['name'];
                    $email  = $line2['email'];
                    $active = $line2['active'];

                    if($firstnocomma==1)
                       $firstnocomma = 0;
                    else
                       echo ", ";

                    if($active==0)
                       echo "$email";
                    else
                       echo "<a href=\"user.php?uid=$uid\">$name</a>";
                 }

              // ========================================================================

              echo "<br><br><b>Intended Conferences</b><br>";
              echo "<ul>";
              $sql = "SELECT `idConference`, `name`, `url`, `idResearch` FROM Conferences WHERE idResearch = $rid";
              $exe = mysql_query( $sql, $myrmconn) or print(mysql_error());
              if($exe != null)
                 while($linha2 = mysql_fetch_array($exe))
                 { 
                    $cid    = $linha2['idConference'];
                    $name   = $linha2['name'];
                    $url    = $linha2['url'];

                    echo "<li> <a href=\"$url\">$name</a> (<a href=\"conference_delete.php?cid=$cid\">delete</a>)";
                 }


              echo "<form name=\"frm_conf_create\" method=\"post\" action=\"conference_create.php\">";
              echo "<input type=\"submit\" value=\"Create conference\" name=\"bt_conf_create\">"; 
              echo "<input type=\"hidden\" value=\"$rid\" name=\"rid\">";
              echo "<input type=\"text\" value=\"Name\" name=\"cname\">";
              echo "<input type=\"text\" value=\"http://...\" name=\"curl\">";
              echo "</form>";

              echo "</ul>"; // Conferences

              // ========================================================================
              // begin sections
              // ========================================================================

              $sql_sec = "SELECT `idSection` as sid, `title` FROM Sections WHERE idResearch = $rid";
              $exe_sec = mysql_query( $sql_sec, $myrmconn) or print(mysql_error());
              if($exe_sec != null)
                 while($line_sec = mysql_fetch_array($exe_sec))
                 { 
                    $sid    = $line_sec['sid'];
                    $stitle = $line_sec['title'];

                    echo "<hr>";
                    echo "<b>Section: </b> $stitle (<a href=\"#\" onclick=\"deletesection($sid)\">delete</a>) 
<br>";


              // ------------------------------------------------------------------------
              // FILES
              // ------------------------------------------------------------------------

              echo "<br><b>Files</b><br>";
              echo "<ul>";
              $sql = "SELECT `idFile` as fid, `filename`, `uploadDateTime` as uploaddt, `uploadUser` as uploadu, `public` FROM Files WHERE idSection = $sid";
              $exe = mysql_query( $sql, $myrmconn) or print(mysql_error());
              if($exe != null)
                 while($linha2 = mysql_fetch_array($exe))
                 { 
                    $fid      = $linha2['fid'];
                    $filename = $linha2['filename'];
                    $uploaddt = $linha2['uploaddt'];
                    $uploadu  = $linha2['uploadu'];
                    $public   = $linha2['public'];

                    echo "<li> <a href=\"./files/$gsname/r$rid/s$sid/$filename\">$filename</a> - ";
                    echo "uploaded by user #$uploadu at $uploaddt (";
                    if($public==1)
                      echo "<b>public</b>";
                    else
                      echo "<b>not public</b>";
                    echo ") <a href=\"#\" onclick=\"deletefile($fid)\">delete</a><br>";
                 }


              echo "<form method=\"post\" action=\"upload_rfile.php\" enctype=\"multipart/form-data\">";
              echo "<label>Send File:</label>";
              echo "<input type=\"hidden\" value=\"$sid\" name=\"sid\">";
              echo "<input type=\"file\" name=\"arquivo\">";
              echo "<input type=\"submit\" value=\"Send file\" name=\"bt_send_file\">"; 
              echo "</form>";

              echo "</ul>"; // Files


              // ------------------------------------------------------------------------
         

              echo "<br><b>Dynamic Tables</b> (<a 
href=\"https://github.com/MyResearchManager/MyResearchManager/blob/master/myrmtable/MyRMTable.java\">download 
MyRMTable.java</a>) <br>";
              echo "<ul>";
              $sql = "SELECT `idDynamicTable`, `description`, `key`, `locked`, `idSection` FROM DynamicTables WHERE 
idSection = $sid";
              $exe = mysql_query( $sql, $myrmconn) or print(mysql_error());
              if($exe != null)
                 while($linha2 = mysql_fetch_array($exe))
                 { 
                    $tid    = $linha2['idDynamicTable'];
                    $desc   = $linha2['description'];
                    $key    = $linha2['key'];
                    $locked = $linha2['locked'];

                    echo "<li> <a href=\"dtableview.php?tid=$tid\">$desc</a> (key: $key)";
                    if($locked!=0)
                       echo " locked (<a href=\"dtablesafeunlock.php?tid=$tid\">unlock</a>) <br>"; // needs session security
                    else
                       echo " unlocked (<a href=\"dtablesafelock.php?tid=$tid\">lock</a>) <br>";   // needs only key value                                     
                 }


              echo "<form name=\"frm_dtable_create\" method=\"post\" action=\"dtablecreate.php\">";
              echo "<input type=\"submit\" value=\"Create table\" name=\"bt_dtable_create\">"; 
              echo "<input type=\"hidden\" value=\"$rid\" name=\"rid\">";
              echo "<input type=\"text\" value=\"Description\" name=\"desc\">";
              echo "</form>";

              echo "</ul>"; // Dynamic Tables

              // ------------------------------------------------------------------------

                 } // end sections

              echo "<br>\n";
              echo "<form name=\"frm_sec_create\" method=\"post\" action=\"section_create.php\">";
              echo "<input type=\"submit\" value=\"Create a new section\" name=\"bt_sec_create\">";
              echo "<input type=\"hidden\" value=\"$rid\" name=\"rid\">";
              echo "<input type=\"text\" value=\"Title\" name=\"stitle\">";
              echo "</form>\n";
              // end sections
              // ========================================================================

              echo "\n</ul><hr><ul>\n"; // space for new research
          }

      }

      echo "</ul>\n";
      echo "<br>";
      echo "<form name=\"frm_res_create\" method=\"post\" action=\"research_create.php\">";
      echo "<input type=\"submit\" value=\"Create a new research\" name=\"bt_res_create\">";
      echo "<input type=\"hidden\" value=\"$gid\" name=\"gid\">";
      echo "<input type=\"text\" value=\"Title\" name=\"rtitle\">";
      echo "</form>";

?>

</ul>

<br>
<br>
<br>
<br>

<a href="version.php">Version 0.3-alpha</a><br>
<a href="http://www.gnu.org/licenses/agpl-3.0.html">License AGPLv3</a><br>
<br>
<br>
<a href="logout.php">Logout</a><br>
<br>

</body>


</html>
