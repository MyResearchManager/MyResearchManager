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

?>
