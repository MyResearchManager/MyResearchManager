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



function getAreaNameByAreaId($area_id)
{
      include "connection.php";

      $gsname = "no-name";
      $sql = "SELECT smallName as gsname FROM Areas WHERE idArea = $area_id";
      $exe = mysql_query( $sql, $myrmconn) or print(mysql_error());
      if($exe != null)
          if($line = mysql_fetch_array($exe))
              $gsname = $line['gsname'];

      return $gsname;
}



?>
