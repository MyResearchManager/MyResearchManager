<?php


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



function getGroupIdByResearchId($rid)
{
      include "connection.php"; 

      $gid = -1;
      $sql = "SELECT idGroup as gid FROM Researches WHERE idResearch = $rid";
      $exe = mysql_query( $sql, $myrmconn) or print(mysql_error());
      if($exe != null)
          if($line = mysql_fetch_array($exe))
              $gid = $line['gid'];

      return $gid;
}



function getGroupNameByGroupId($gid)
{
      include "connection.php";

      $gsname = "no-name";
      $sql = "SELECT smallName as gsname FROM Groups WHERE idGroup = $gid";
      $exe = mysql_query( $sql, $myrmconn) or print(mysql_error());
      if($exe != null)
          if($line = mysql_fetch_array($exe))
              $gsname = $line['gsname'];

      return $gsname;
}



?>
