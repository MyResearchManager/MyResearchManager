<?php

//if(function_exists('getUserIdByEmail'))
//{
//   exit;
//}

function getUserIdByEmail($email)
{
      include "connection.php";

      $uid = -1;
      $sql = "SELECT idUser as uid FROM Users WHERE email = '$email'";
      $exe = mysql_query( $sql, $myrmconn) or print(mysql_error());
      if($exe != null)
          if($line = mysql_fetch_array($exe))
              $uid = $line['uid'];

      return $uid;
}

function getUserIdByHash($hash)
{
      include "connection.php";

      $uid = -1;
      $sql = "SELECT idUser as uid FROM Users WHERE uhash = '$hash'";
      $exe = mysql_query( $sql, $myrmconn) or print(mysql_error());
      if($exe != null)
          if($line = mysql_fetch_array($exe))
              $uid = $line['uid'];

      return $uid;
}

function getUserHashById($uid)
{
      include "connection.php";

      $uhash = "";
      $sql = "SELECT uhash FROM Users WHERE idUser = '$uid'";
      $exe = mysql_query( $sql, $myrmconn) or print(mysql_error());
      if($exe != null)
          if($line = mysql_fetch_array($exe))
              $uhash = $line['uhash'];

      return $uhash;
}


function getResearchIdBySectionId($sid)
{
      include "connection.php";

      $rid = -1;
      $sql = "SELECT idResearch as rid FROM Sections WHERE idSection = $sid";
      $exe = mysql_query( $sql, $myrmconn) or print(mysql_error());
      if($exe != null)
          if($line = mysql_fetch_array($exe))
              $rid = $line['rid'];

      return $rid;
}



function getAreaIdByResearchId($rid)
{
      include "connection.php"; 

      $area_id = -1;
      $sql = "SELECT idArea as aid FROM Researches WHERE idResearch = $rid";
      $exe = mysql_query( $sql, $myrmconn) or print(mysql_error());
      if($exe != null)
          if($line = mysql_fetch_array($exe))
              $area_id = $line['aid'];

      return $area_id;
}

function getSectionIdByPublicationId($pid)
{
      include "connection.php"; 

      $sid = -1;
      $sql = "SELECT idSection as sid FROM Publications WHERE idPublication = $pid";
      $exe = mysql_query( $sql, $myrmconn) or print(mysql_error());
      if($exe != null)
          if($line = mysql_fetch_array($exe))
              $sid = $line['sid'];

      return $sid;
}

function getUserNameByUserId($user_id)
{
      include "connection.php";

      $uname = "no-name";
      $sql = "SELECT name FROM Users WHERE idUser = $user_id";
      $exe = mysql_query( $sql, $myrmconn) or print(mysql_error());
      if($exe != null)
          if($line = mysql_fetch_array($exe))
              $uname = $line['name'];

      return $uname;
}

function getUserCitationByUserId($user_id)
{
      include "connection.php";

      $ucitation = "no-citation";
      $sql = "SELECT citation FROM Users WHERE idUser = $user_id";
      $exe = mysql_query( $sql, $myrmconn) or print(mysql_error());
      if($exe != null)
          if($line = mysql_fetch_array($exe))
              $ucitation = $line['citation'];

      return $ucitation;
}


function getAreaIdByAreaName($area_sname)
{
      include "connection.php";

      $area_id = -1;
      $sql = "SELECT idArea FROM Areas WHERE smallName = '$area_sname'";
      $exe = mysql_query( $sql, $myrmconn) or print(mysql_error());
      if($exe != null)
          if($line = mysql_fetch_array($exe))
              $area_id = $line['idArea'];

      return $area_id;
}

function getSectionNameBySectionId($sid)
{
      include "connection.php";

      $sname = "no-name";
      $sql = "SELECT title FROM Sections WHERE idSection = $sid";
      $exe = mysql_query( $sql, $myrmconn) or print(mysql_error());
      if($exe != null)
          if($line = mysql_fetch_array($exe))
              $sname = $line['title'];

      return $sname;
}


function getResearchNameByResearchId($rid)
{
      include "connection.php";

      $rname = "no-name";
      $sql = "SELECT title FROM Researches WHERE idResearch = $rid";
      $exe = mysql_query( $sql, $myrmconn) or print(mysql_error());
      if($exe != null)
          if($line = mysql_fetch_array($exe))
              $rname = $line['title'];

      return $rname;
}


function getAreaNameByAreaId($aid)
{
      include "connection.php";

      $aname = "no-name";
      $sql = "SELECT name FROM Areas WHERE idArea = $aid";
      $exe = mysql_query( $sql, $myrmconn) or print(mysql_error());
      if($exe != null)
          if($line = mysql_fetch_array($exe))
              $aname = $line['name'];

      return $aname;
}


function getFilenameByFileId($fid)
{
      include "connection.php";

      $filename = "no-name";
      $sql = "SELECT filename FROM Files WHERE idFile = $fid";
      $exe = mysql_query( $sql, $myrmconn) or print(mysql_error());
      if($exe != null)
          if($line = mysql_fetch_array($exe))
              $filename = $line['filename'];

      return $filename;
}


function getTitleByPublicationId($pid)
{
      include "connection.php";

      $title = "no-name";
      $sql = "SELECT title FROM Publications WHERE idPublication = $pid";
      $exe = mysql_query( $sql, $myrmconn) or print(mysql_error());
      if($exe != null)
          if($line = mysql_fetch_array($exe))
              $title = $line['title'];

      return $title;
}

// ==================
//     Schedules
// ==================

function getScheduleIdByTaskId($tid)
{
      include "connection.php"; 

      $hid = -1;
      $sql = "SELECT idSchedule as hid FROM Tasks WHERE idTask = $tid";
      $exe = mysql_query( $sql, $myrmconn) or print(mysql_error());
      if($exe != null)
          if($line = mysql_fetch_array($exe))
              $hid = $line['hid'];
      return $hid;
}

function getResearchIdByScheduleId($hid)
{
      include "connection.php"; 

      $rid = -1;
      $sql = "SELECT idResearch as hid FROM Schedules WHERE idSchedule = $hid";
      $exe = mysql_query( $sql, $myrmconn) or print(mysql_error());
      if($exe != null)
          if($line = mysql_fetch_array($exe))
              $rid = $line['rid'];
      return $rid;
}


function getTaskIdByMessageId($mid)
{
      include "connection.php"; 

      $tid = -1;
      $sql = "SELECT idTask as tid FROM TaskMessages WHERE idTaskMessage = $mid";
      $exe = mysql_query( $sql, $myrmconn) or print(mysql_error());
      if($exe != null)
          if($line = mysql_fetch_array($exe))
              $tid = $line['tid'];
      return $tid;
}


function getNumMessagesByTaskId($tid)
{
      include "connection.php"; 

      $count = 0;
      $sql = "SELECT count(*) as `count` FROM TaskMessages WHERE idTask = $tid";
      $exe = mysql_query( $sql, $myrmconn) or print(mysql_error());
      if($exe != null)
          if($line = mysql_fetch_array($exe))
              $count = $line['count'];
      return $count;
}

?>
