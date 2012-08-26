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


?>
