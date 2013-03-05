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

   $autoexpandresearch = true; // put in configuration file later

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
 <link rel="shortcut icon" href="myrm.ico"/>

<script type="text/javascript">
//<!--
function deletefile(fid, check)
{
   var answer = confirm("Deleting file. Are you sure?")
   if(answer)
      window.location = "file_delete.php?fid="+fid+"&check="+check;
}
//-->
//<!--
function deletesection(sid)
{
   var answer = confirm("Deleting section. Are you sure?")
   if(answer)
      window.location = "section_delete.php?sid="+sid;
}
//-->
//<!--
function deleteresearch(rid)
{
   var answer = confirm("Deleting research. Are you sure?")
   if(answer)
      window.location = "research_delete.php?rid="+rid;
}
//-->
//<!--
function deleteschedule(hid)
{
   var answer = confirm("Deleting schedule. Are you sure?")
   if(answer)
      window.location = "schedule_delete.php?hid="+hid;
}
//-->
//<!--
function deletetask(tid)
{
   var answer = confirm("Deleting task. Are you sure?")
   if(answer)
      window.location = "task_delete.php?tid="+tid;
}
//-->
//<!--
function deletepublication(pid)
{
   var answer = confirm("Deleting publication. Are you sure?")
   if(answer)
      window.location = "publication_delete.php?pid="+pid;
}
//-->
//<!--
function removeuserfrompublication(pid, uid)
{
   var answer = confirm("Removing user from publication. Are you sure?")
   if(answer)
      window.location = "publication_remove_user.php?pid="+pid+"&uid="+uid;
}
//<!--
function removefilefrompublication(pid, fid)
{
   var answer = confirm("Removing file from publication. Are you sure?")
   if(answer)
      window.location = "publication_remove_file.php?pid="+pid+"&fid="+fid;
}
//-->
//<!--
function removeuserfromresearch(rid, uid)
{
   var answer = confirm("Removing user from this research. Are you sure?")
   if(answer)
      window.location = "research_remove_user.php?rid="+rid+"&uid="+uid;
}
//-->
//<!--
function removeuserfromsection(sid, uid)
{
   var answer = confirm("Removing user from this section. Are you sure?")
   if(answer)
      window.location = "section_remove_user.php?sid="+sid+"&uid="+uid;
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
      $num_remember = mysql_num_rows($exe);

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

     if($num_remember==0)
         echo "<i>None</i>\n";

?>
</ul>

<br>

<b>Researches</b>

<br>

<ul>

<?php

      if(!isset($_SESSION['rexpanded']))
         $_SESSION['rexpanded'] = array();

      $rexpanded = $_SESSION['rexpanded'];

      if(!isset($_SESSION['sexpanded']))
         $_SESSION['sexpanded'] = array();

      $sexpanded = $_SESSION['sexpanded'];


      $sql_research = "SELECT R.idResearch as rid, R.title as title FROM Users as U, Researches as R, ResearchMembers as RM WHERE U.idUser = $id and R.idArea = $area_id and RM.idUser = U.idUser and RM.idResearch = R.idResearch ORDER BY title";
      $exe_research = mysql_query( $sql_research, $myrmconn) or print(mysql_error());
      if($exe_research != null)
      {
          $num_research = mysql_num_rows($exe_research);

          $userInResearch = true;
          if($num_research == 0)
             $userInResearch = false;

          if(!$userInResearch) // look for users in sections only
          {
             $sql_research = "SELECT R.idResearch as rid, R.title as title FROM Users as U, Researches as R, Sections as S, SectionMembers as SM WHERE
U.idUser = $id and R.idArea = $area_id and SM.idUser = U.idUser and SM.idSection = S.idSection and S.idResearch = R.idResearch ORDER 
BY title";
             $exe_research = mysql_query( $sql_research, $myrmconn) or print(mysql_error());
             $num_research = mysql_num_rows($exe_research);
          }

          while($line_research = mysql_fetch_array($exe_research))
          {
              $rid = $line_research['rid'];

              $re = 0;
              $rdefined = false;

              foreach($rexpanded as $key=>$value)
              {
                 if($key == $rid)
                 {
                    $rdefined = true;
                    if($value == 1) 
                       $re = 1;
                 }
              }

              if(!$rdefined && $autoexpandresearch)
                 $re=1;


              if(($re==1) && ($edit==1))
              {
                   echo "<li>";
                   echo "<form name=\"frm_research_rename\" method=\"post\" action=\"research_rename.php\">";
                   echo "<input type=\"hidden\" value=\"$rid\" name=\"rid\">";
                   echo "<input type=\"text\" value=\"".$line_research['title']."\" name=\"title\">";
                   echo "<input type=\"submit\" value=\"Rename\" name=\"bt_research_rename\">";
              }
              else
                  echo "<li> <b> <a href=\"research.php?rid=$rid\"> $line_research[title]</a> </b>";


              if($re == 0)
                 echo "[<a name=\"r$rid\" href=\"research_expand.php?rid=$rid\">expand</a>]";
              else
                 echo "[<a name=\"r$rid\" href=\"research_collapse.php?rid=$rid\">collapse</a>]";

              if(($edit==1) && $userInResearch) // if(($num_research > 1) && ($edit==1) && $userInResearch)
                 echo "(<a href=\"#\" onclick=\"deleteresearch($rid)\">delete</a>)";
              echo "<br>\n";
              echo "<i><b>with</b></i> ";
              $firstnocomma = 1;      
              
              $sql = "SELECT U.idUser as uid, uhash, U.name as name, U.email as email FROM Users as U, ResearchMembers as RM WHERE RM.idResearch = $rid and U.idUser = RM.idUser order by U.name, U.email";
              $exe = mysql_query( $sql, $myrmconn) or print(mysql_error());
              if($exe != null)
                 while($line2 = mysql_fetch_array($exe))
                 {
                    $uid    = $line2['uid'];
                    $uhash  = $line2['uhash'];
                    $name   = $line2['name'];
                    $email  = $line2['email'];

                    if($firstnocomma==1)
                       $firstnocomma = 0;
                    else
                       echo ", ";

                    echo "<a href=\"user.php?uhash=$uhash\">$name</a>";
                    if( ($edit==1) && ($id != $uid) && $userInResearch )
                       echo "(<a href=\"#\" onclick=\"removeuserfromresearch($rid, '$uid')\">X</a>)";
                 }

                 if($re==0)
                    echo "<br>(...)";

                 if(($re==1) && ($edit==1) && $userInResearch)
                 {
                     echo "<form name=\"frm_research_add_user\" method=\"post\" action=\"research_add_user.php\">";
                     echo "<input type=\"submit\" value=\"Add user to this research\" name=\"bt_research_add_user\">";
                     echo "<input type=\"hidden\" value=\"$rid\" name=\"rid\">";
                     echo "<input type=\"text\" value=\"a@b.com\" name=\"email\">";
                     echo "</form>";
                 }
                 else
                    echo "<br>";


              // ========================================================================
              // begin schedules
              // ========================================================================

              if($userInResearch)
              {
                 $sql_sch = "SELECT `idSchedule` as hid, `title`, `description` FROM Schedules WHERE idResearch = $rid ORDER BY `title`";
                 $exe_sch = mysql_query( $sql_sch, $myrmconn) or print(mysql_error());
                 if(($re==1) && ($exe_sch != null) ) // if research is expanded!
                 {
                    while($line_sch = mysql_fetch_array($exe_sch))
                    { 
                       $hid    = $line_sch['hid'];
                       $htitle = $line_sch['title'];
                       $hdesc  = $line_sch['description'];

                       echo "<a name=\"h$hid\"><hr></a>";

                       if($edit==0)
                       {
                          echo "<b>Schedule:</b> $htitle ";
                       }
                       else
                       {
                          echo "<form name=\"frm_schedule_rename\" method=\"post\" action=\"schedule_rename.php\">";
                          echo "<input type=\"hidden\" value=\"$hid\" name=\"hid\">";
                          echo "<b>Schedule:</b><input type=\"text\" value=\"$htitle\" name=\"title\">";
                          echo "<input type=\"submit\" value=\"Rename\" name=\"bt_schedule_rename\">";

                          echo "(<a href=\"#\" onclick=\"deleteschedule($hid)\">delete</a>)";

                          echo "</form>";
                       }

                       echo "<table border=\"1\">\n";
                       echo "<tr><td><b>Title</b></td> <td><b>#Messages</b></td> <td><b>Period</b></td></tr>\n";
                       $sql_task = "SELECT `idTask` as tid, `title`, `begin`, `end` FROM Tasks WHERE idSchedule = $hid ORDER BY `begin`";
                       $exe_task = mysql_query( $sql_task, $myrmconn) or print(mysql_error());

                       if($exe_task)
                       {
                          while($line_task = mysql_fetch_array($exe_task))
                          {
                             $tid    = $line_task['tid'];
                             $ttitle = $line_task['title'];
                             $begin  = $line_task['begin'];
                             $end    = $line_task['end'];

                             require_once("util.php");

                             echo "<tr><td><a href=\"task_view.php?tid=$tid\">$ttitle</a>";
                             if($edit==1)
                                echo " (<a href=\"#\" onclick=\"deletetask($tid)\">delete</a>) ";
                             echo "</td> <td>".getNumMessagesByTaskId($tid)."</td> <td>$begin - $end</td></tr>\n";
                          }
                       }
                       echo "</table><br>\n";


                       if($edit==1)
                       {
                          echo "<form name=\"frm_task_create\" method=\"post\" action=\"task_create.php\">";
                          echo "<input type=\"submit\" value=\"Create a new task\" name=\"bt_task_create\">";
                          echo "<input type=\"hidden\" value=\"$hid\" name=\"hid\">";
                          echo "<input type=\"text\" value=\"Title\" name=\"ttitle\">";
                          echo "begin: <input type=\"text\" value=\"".date("Y-m-d")."\"name=\"begin\" size='10'>";
                          echo "end: <input type=\"text\" value=\"".date("Y-m-d")."\"name=\"end\" size='10'>";
                          echo "</form>\n";
                       }


                    } 
                 }



                 if($edit==1)
                 {
                    echo "<form name=\"frm_sch_create\" method=\"post\" action=\"schedule_create.php\">";
                    echo "<input type=\"submit\" value=\"Create a new schedule\" name=\"bt_sch_create\">";
                    echo "<input type=\"hidden\" value=\"$rid\" name=\"rid\">";
                    echo "<input type=\"text\" value=\"Title\" name=\"htitle\">";
                    echo "</form>\n";
                 }

              } // finish schedules

              // ========================================================================
              // begin sections
              // ========================================================================

              $sql_sec = "";
              if($userInResearch)
                 $sql_sec = "SELECT `idSection` as sid, `title`, `shash` FROM Sections WHERE idResearch = $rid ORDER BY `title`";
              else
                 $sql_sec = "SELECT S.`idSection` as sid, S.`title` as title, S.shash as shash FROM Sections as S, SectionMembers as SM WHERE S.idResearch = $rid and SM.idSection = S.idSection and SM.idUser = $id ORDER BY `title`";
            
              $exe_sec = mysql_query( $sql_sec, $myrmconn) or print(mysql_error());
              if(($re==1) && ($exe_sec != null) ) // if research is expanded!
                 while($line_sec = mysql_fetch_array($exe_sec))
                 { 
                    $sid    = $line_sec['sid'];
                    $stitle = $line_sec['title'];
                    $shash  = $line_sec['shash'];

                    $se = 0;

                    foreach($sexpanded as $key=>$value)
                    {
               	       if( ($key == $sid) && ($value == 1) )
                       $se = 1;
                    }


                    echo "<a name=\"s$sid\"><hr></a>";

                    $edit_this = $edit;

                    if($se==0)
                       $edit_this=0;

                    if($edit_this==0)
                    {
                       echo "<b>Section:</b> $stitle (<a href=\"section_backup.php?sid=$sid\">BACKUP</a>) <a href=\"javascript:alert(prompt('Section $stitle sync is:', '$myrm_site/sync.php?shash=$shash&usercode=YOUR-CODE'));\">get sync</a> ";

                       if($se == 0)
                          echo "[<a href=\"section_expand.php?sid=$sid\">expand</a>]";
                       else
                          echo "[<a href=\"section_collapse.php?sid=$sid\">collapse</a>]";
                    }
                    else
                    {
                       echo "<form name=\"frm_section_rename\" method=\"post\" action=\"section_rename.php\">";
                       echo "<input type=\"hidden\" value=\"$sid\" name=\"sid\">";
                       echo "<b>Section:</b><input type=\"text\" value=\"$stitle\" name=\"title\">";
                       echo "<input type=\"submit\" value=\"Rename\" name=\"bt_section_rename\">";

                       if($se == 0)
                          echo "[<a href=\"section_expand.php?sid=$sid\">expand</a>]";
                       else
                          echo "[<a href=\"section_collapse.php?sid=$sid\">collapse</a>]";

                       echo "(<a href=\"#\" onclick=\"deletesection($sid)\">delete</a>)";

                       echo "</form>";
                    }

                    if($edit_this==0)
                       echo "<br>";

                    $firstnocomma = 1;
                    $sql_s_u = "SELECT U.idUser as uid, uhash, U.name as name, U.email as email FROM Users as U, SectionMembers as SM WHERE SM.idSection = $sid and U.idUser = SM.idUser order by U.name, U.email";
                    $exe_s_u = mysql_query( $sql_s_u, $myrmconn) or print(mysql_error());
                    if($exe_s_u != null)
                       while($line3 = mysql_fetch_array($exe_s_u))
                       {
                          $uid_sec    = $line3['uid'];
                          $uhash_sec  = $line3['uhash'];
                          $name_sec   = $line3['name'];
                          $email_sec  = $line3['email'];

                          if($firstnocomma==1)
                          {
                             echo "<i>Other users: </i>";
                             $firstnocomma = 0;
                          }
                          else
                             echo ", ";

                          echo "<a href=\"user.php?uhash=$uhash_sec\">$name_sec</a>";
  
                          if( ($edit_this==1) && ($id != $uid_sec) )
                          echo "(<a href=\"#\" onclick=\"removeuserfromsection($sid, '$uid_sec')\">X</a>)";
                       }

                    if($se==0)
                    {
                       echo " (...)<br>";
                       if($edit_this==1)
                          echo "<br>";
                       continue;
                    }

                    if(($se==1) && ($edit_this==1))
                    {
                       echo "<form name=\"frm_section_add_user\" method=\"post\" action=\"section_add_user.php\">";
                       echo "<input type=\"submit\" value=\"Add user to this section\" name=\"bt_section_add_user\">";
                       echo "<input type=\"hidden\" value=\"$sid\" name=\"sid\">";
                       echo "<input type=\"text\" value=\"a@b.com\" name=\"email\">";
                       echo "</form>";
                    }
                    else
                       echo "<br>";
                        
                    // ------------------------------------------------------------------------
                    // IMPORTANT DATES
                    // ------------------------------------------------------------------------

                    $sql_imp = "SELECT * FROM ImportantDates WHERE idSection = $sid order by `datetime` ASC";
                    $exe_imp = mysql_query( $sql_imp, $myrmconn) or print(mysql_error());

                    $num_impdates = mysql_num_rows($exe_imp);
                    if( ($num_impdates > 0) || ($edit==1) )
                    {
                       echo "<br><b>Important Dates</b><br>";
                       echo "<ul>";
                    }

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

                    if( ($num_impdates > 0) || ($edit==1) )
                       echo "</ul>";

                    if($edit==1)
                    {
                       echo "<form name=\"frm_impdate_create\" method=\"post\" action=\"important_create.php\">";
                       echo "<input type=\"submit\" value=\"Create important date\" name=\"bt_impdate_create\">";
                       echo "<input type=\"hidden\" value=\"$sid\" name=\"sid\">";
                       echo "Remember:<input type=\"text\" value=\"Remember\" name=\"description\">";
                       echo "Date: <input type=\"text\" value=\"".date("Y-m-d")."\"name=\"idate\" size='10'>";
                       echo "Time: <input type=\"text\" value=\"23:59\" name=\"itime\" size='8'>";
                       echo "</form>";
                    }


                    // ------------------------------------------------------------------------
                    // LINKS
                    // ------------------------------------------------------------------------

                    $sql_links = "SELECT `idLink`, `name`, `url`, `idSection` FROM Links WHERE idSection = $sid";
                    $exe_links = mysql_query( $sql_links, $myrmconn) or print(mysql_error());

                    $num_links = mysql_num_rows($exe_links);
                    if(($num_links > 0) || ($edit==1) )
                    {
                       echo "<br><b>Links</b><br>";
                       echo "<ul>";
                    }

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

                    if(($num_links > 0) || ($edit==1) )
                       echo "</ul>"; // Links


              // ------------------------------------------------------------------------
              // FILES
              // ------------------------------------------------------------------------

              $sql_files = "SELECT `idFile` as fid, `filename`, SUBSTRING(MD5(`filename`),1,5) as `check`, `size`, `creation`, `visible`, `description` FROM Files WHERE idSection = $sid ORDER BY `filename`";
              $exe_files = mysql_query( $sql_files, $myrmconn) or print(mysql_error());

              $num_files = mysql_num_rows($exe_files);
       	      if( ($num_files > 0) || ($edit==1) )
              {
                 echo "<br><b>Files</b><br>";
                 echo "<ul>";
              }

              if($exe_files != null)
                 while($linha2 = mysql_fetch_array($exe_files))
                 { 
                    $fid      = $linha2['fid'];
                    $filename = $linha2['filename'];
                    $filesize = $linha2['size'];
                    $creation = $linha2['creation'];
                    $visible  = $linha2['visible']; // -1 is public, 0 is section visible, other values are private user id
                    $check    = $linha2['check'];
                    $description  = $linha2['description'];

                    echo "<li> <a href=\"./files/a$area_id/r$rid/s$sid/$filename\">";
                    if($description != "")
                       echo "$description";
                    else
                       echo "$filename";
                    echo "</a> ";
 
                    if (file_exists("./files/a$area_id/r$rid/s$sid/$filename"))
                    {
                       echo "- <i><b>last modified:</b> " . date("F d Y H:i", strtotime($creation)) . " <b>size:</b> ";
                    
                       if($filesize > 1024*1024)
                         printf("%.1f MB", ($filesize/(1024*1024)));
                       else if($filesize > 1024)
                         printf("%.1f KB", ($filesize/1024));
                       else
                         echo "$filesize bytes";
                       echo "</i> ";
                    }

                    if($edit==1)
                       echo "(<a href=\"#\" onclick=\"deletefile($fid, '$check')\">delete</a>)";
                    echo "<br>";

                    $numdatetime = strtotime($uploaddt);
                    $fdtime = date("Y-m-d H:i", $numdatetime);

                    //echo "<i>Uploaded by <b>".getUserNameByUserId($uploadu)."</b> on <b>$fdtime</b></i> (";
                    if($description != "")
                       echo "<b>filename:</b><i>$filename</i> ";
                    else
                       echo "<b>description:</b><i>\"$description\"</i> ";                       
                    echo "<i><b>visible to:</b> ";
                    if($visible==-1)
                      echo "public";
                    else if($visible==0)
                      echo "members";
                    else
                      echo "user $visible";
                    echo "</i>";
                    echo "<br style=\"margin-bottom: 1em;\" />";
                 }

              if($edit==1)
              {
                 echo "<form method=\"post\" action=\"upload_rfile.php\" enctype=\"multipart/form-data\">";
                 echo "<label>Send File:</label>";
                 echo "<input type=\"hidden\" value=\"$sid\" name=\"sid\">";
                 echo "<input type=\"file\" name=\"arquivo\">";
                 echo "Description: <input type=\"text\" value=\"description of file\" name=\"description\">";
                 echo "<input type=\"submit\" value=\"Send file\" name=\"bt_send_file\">"; 
                 echo "</form>";
              }

       	      if( ($num_files > 0) || ($edit==1) )
                 echo "</ul>"; // Files



              // ------------------------------------------------------------------------
              // PUBLICATIONS
              // ------------------------------------------------------------------------

              $sql = "SELECT `idPublication` as pid, `title`, `date`, `journal`, `visible` FROM Publications WHERE idSection = $sid ORDER BY `date`";
              $exe = mysql_query( $sql, $myrmconn) or print(mysql_error());

              $num_publications = mysql_num_rows($exe);
       	      if( ($num_publications > 0) || ($edit==1) )
              {
                 echo "<br><b>Publications</b><br>";
                 echo "<ul>";
              }

              if($exe != null)
                 while($line2 = mysql_fetch_array($exe))
                 { 
                    $pid     = $line2['pid'];
                    $title   = $line2['title'];
                    $journal = $line2['journal'];
                    $pdate   = $line2['date'];
                    $ndt = strtotime($pdate);
                    $fdtime = date("F d Y", $ndt);
                    $visible = $line2['visible']; // -1 is public, 0 is section visible, other values are private user id

                    echo "<li> $title ($fdtime) - <i>$journal</i> ";
                    if($edit==1)
                       echo "(<a href=\"#\" onclick=\"deletepublication($pid)\">delete</a>)";

                    echo "<br><b>Authors:</b> ";

                    $sql2 = "SELECT `idPublicationMember`, `order`, `idUser`, `idPublication` FROM PublicationMembers WHERE idPublication = $pid ORDER BY `order`";
                    $exe2 = mysql_query( $sql2, $myrmconn) or print(mysql_error());

                    $num_authors = mysql_num_rows($exe2);
  
                    if($exe2 != null)
                       while($lauthors = mysql_fetch_array($exe2))
                       {
                          $uid1   = $lauthors['idUser'];
                          $uhash1 = getUserHashById($uid1);
                          $uname  = getUserNameByUserId($uid1);
                          $order  = $lauthors['order'];
                          echo "<a href=\"user.php?uhash=$uhash1\">$uname</a>";
                          if($edit==1)
                             echo " (#$order)";
                          if($order != $num_authors)
                             echo ", ";
                          if(($edit==1) && ($order==$num_authors))
                             echo "(<a href=\"#\" onclick=\"removeuserfrompublication($pid, $uid1)\">delete last</a>)";

                       }

                    if($edit==1)
                    {
                        echo "<form method=\"post\" action=\"publication_add_user.php\">";
                        $num_authors++;
                        echo "<label>add author #$num_authors (email):</label>";
                        echo "<input type=\"hidden\" value=\"$num_authors\" name=\"order\">";
                        echo "<input type=\"hidden\" value=\"$pid\" name=\"pid\">";
                        echo "<input type=\"text\" name=\"email\">";
                        echo "<input type=\"submit\" value=\"Add user\" name=\"bt_publication_add_user\">"; 
                        echo "</form>";
                    }

                    $sql_pub_files = "SELECT F.`idFile` as idFile, F.`filename` as filename FROM PublicationFiles as PF, Files as F WHERE PF.idPublication = '$pid' AND PF.idFile = F.idFile ORDER BY F.filename";
                    $exe_pub_files = mysql_query( $sql_pub_files, $myrmconn) or print(mysql_error());
                    $num_pub_files = mysql_num_rows($exe_pub_files);

                    if($num_pub_files > 0)
                                echo "<ul>"; 
                    if($exe_pub_files)
                           while($row_pf = mysql_fetch_array($exe_pub_files))
                           {
                                    $filename = $row_pf['filename'];
                                    $fid      = $row_pf['idFile'];

                                    echo "<li>$filename";
                                    if($edit==1)
                                        echo "(<a href=\"#\" onclick=\"removefilefrompublication($pid, $fid)\">remove file</a>)";
                            }
   
                    if($num_pub_files > 0)
                            echo "</ul>";


                    if($edit==1)
                    {
                                echo "<form method=\"post\" action=\"publication_add_file.php\">";
                                echo "<label>add file:</label>";
                                echo "<SELECT name=\"fid\">\n";
                                $sql_files = "SELECT `idFile` as fid, `filename` FROM Files WHERE idSection = $sid ORDER BY `filename`";
                                $exe_files = mysql_query( $sql_files, $myrmconn) or print(mysql_error());
                                while($row_f = mysql_fetch_array($exe_files))
                                {
                                    $filename = $row_f['filename'];
                                    $fid2     = $row_f['fid'];
                                    echo "<OPTION value=\"$fid2\">$filename</OPTION>\n";
                                }
                                echo "</SELECT>\n";
                                echo "<input type=\"hidden\" value=\"$pid\" name=\"pid\">";
                                echo "<input type=\"submit\" value=\"Add file\" name=\"bt_publication_add_file\">"; 
                                echo "</form>";
                    }


                    echo "<br>";

                    echo "<i><b>visible to:</b> ";
                    if($visible==-1)
                      echo "public";
                    else if($visible==0)
                      echo "members";
                    else
                      echo "user $visible";
                    echo "</i>";
                    echo "<br style=\"margin-bottom: 1em;\" />";
                 }

              if($edit==1)
              {
                 echo "<form method=\"post\" action=\"publication_create.php\">";
                 echo "<label>Create publication:</label>";
                 echo "<input type=\"hidden\" value=\"$sid\" name=\"sid\">";
                 echo "<input type=\"text\" name=\"title\" value=\"Title\">";
                 echo "<input type=\"text\" name=\"journal\" value=\"Journal\">";
                 echo "<input type=\"text\" name=\"date\" value=\"".date("Y-m-d")."\">";
                 echo "<input type=\"submit\" value=\"Create publication\" name=\"bt_publication_create\">"; 
                 echo "</form>";
              }

       	      if( ($num_publications > 0) || ($edit==1) )
                 echo "</ul>"; // Publications



              // ------------------------------------------------------------------------
              // DYNAMIC TABLES
              // ------------------------------------------------------------------------

              $sql = "SELECT `idDynamicTable`, `description`, `key`, `locked`, `idSection` FROM DynamicTables WHERE idSection = $sid ORDER BY description";
              $exe = mysql_query( $sql, $myrmconn) or print(mysql_error());

              $num_dyntables = mysql_num_rows($exe);
              if( ($num_dyntables > 0) || ($edit==1) )
              {
                 echo "<b>Dynamic Tables</b><br>";
                 echo "<ul>";
              }

              if($exe != null)
                 while($linha2 = mysql_fetch_array($exe))
                 { 
                    $tid    = $linha2['idDynamicTable'];
                    $desc   = $linha2['description'];
                    $key    = $linha2['key'];
                    $locked = $linha2['locked'];

                    echo "<li> <a href=\"dtableview.php?tid=$tid\">$desc</a> - <b>key:</b> <i>$key</i> ";

                    if($edit==1)
                       echo "(delete) ";

                    echo "<br>";

                    if($locked!=0)
                       echo "<i><b>Status:</b> locked</i> ";
                    else
                       echo "<i><b>Status:</b> unlocked</i> ";

                    if($edit==1)
                    {
                       if($locked!=0)
                          echo "(<a href=\"dtablesafeunlock.php?tid=$tid\">unlock</a>)"; // needs session security
                       else
                          echo "(<a href=\"dtablesafelock.php?tid=$tid\">lock</a>)";     // needs only key value
                    }
                    echo "<br>";
                 }

              if($edit==1)
              {
                 echo "<form name=\"frm_dtable_create\" method=\"post\" action=\"dtablecreate.php\">";
                 echo "<input type=\"submit\" value=\"Create table\" name=\"bt_dtable_create\">"; 
                 echo "<input type=\"hidden\" value=\"$sid\" name=\"sid\">";
                 echo "<input type=\"text\" value=\"Description\" name=\"desc\">";
                 echo "</form>";
              }

              if( ($num_dyntables > 0) || ($edit==1) )
                 echo "</ul>"; // Dynamic Tables

              // ------------------------------------------------------------------------

              //echo "<hr>";

                 } // end sections

              if($edit==1)
                 echo "<br>\n";

              if(($re==1) && ($edit==1) && $userInResearch)
              {
                 echo "<form name=\"frm_sec_create\" method=\"post\" action=\"section_create.php\">";
                 echo "<input type=\"submit\" value=\"Create a new section\" name=\"bt_sec_create\">";
                 echo "<input type=\"hidden\" value=\"$rid\" name=\"rid\">";
                 echo "<input type=\"text\" value=\"Title\" name=\"stitle\">";
                 echo "Sync path:<input type=\"text\" value=\"\" name=\"ssync\">";
                 echo "<i> (create a new section for a conference, journal or group of technical reports)</i>";
                 echo "</form>\n";
              }

              echo "<hr>\n";

              // end sections
              // ========================================================================

              echo "</ul>\n";
              echo "<br style=\"margin-bottom: -2em;\" />";
              echo "<ul>\n"; // space for new research
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

<br>
<br>
<center>
   <i>Install MyResearchManager on your own server: </i> <a href="https://github.com/MyResearchManager/MyResearchManager/archive/master.zip">download latest version</a><br><br>
   Download script to synchronize your researches (<i>for Linux only</i>): <a target=_blank href="myrm-sync-all.sh">Part1</a>, <a target=_blank href="myrm-sync-research.sh">Part2</a> and <a target=_blank href="myrm-sync-section.sh">Part3</a><br>
  (get your <i>usercode</i> at <a target=_blank href="profile.php">profile</a> and use this link to the server: <i><?php echo "\"$myrm_domain_name/myrm\""; ?></i>)<br><br>
   <a target=_blank href="https://github.com/MyResearchManager/MyResearchManager/blob/master/myrmtable/MyRMTable.java">Download 
MyRMTable.java</a> (to work with dynamic tables)<br><br><br>

   <a href="version.php"><?php include "version.php"; ?></a><br>
</center>

</body>


</html>
