<?php
   ob_start();
   session_start();

   if ($_SESSION['logado'] != 1)
        header("Location: login.php");

   $id = $_SESSION['id'];


   // Publication

   $sid = -1;
   if(isset($_POST["sid"]))
      $sid = $_POST["sid"];

   $title = "";
   if(isset($_POST["title"]))
      $title = $_POST["title"];

   $year = "";
   if(isset($_POST["year"]))
      $year = $_POST["year"];


   // JournalPublication

   $journal = "";
   if(isset($_POST["journal"]))
      $journal = $_POST["journal"];


   // ConferencePublication

   $conference = "";
   if(isset($_POST["conference"]))
      $conference = $_POST["conference"];

   $location = "";
   if(isset($_POST["location"]))
      $location = $_POST["location"];

   $date = "";
   if(isset($_POST["date"]))
      $date = $_POST["date"];

   // ---------------------

   include "connection.php";

   include "util.php";

   $sql = "INSERT INTO Publications (`idSection`, `title`, `year`) VALUES ('$sid', '$title', '$year')";
   $exe = mysql_query($sql, $myrmconn) or print(mysql_error());
   $pid = mysql_insert_id();

   $rid = getResearchIdBySectionId($sid);
   $aid = getAreaIdByResearchId($rid);

   $sql = "INSERT INTO Logs (`when`, `what`) VALUES (NOW(), 'uid=$id added pid=$pid in sid=$sid of rid=$rid of aid=$aid.')";
   $exe = mysql_query($sql, $myrmconn) or print(mysql_error());

   // -----------------
   // put specific data
   // -----------------

   if(isset($_POST["bt_journal_publication_create"]))
   {
      $sql = "INSERT INTO JournalPublications (`idPublication`, `journal`) VALUES ('$pid', '$journal')";
      $exe = mysql_query($sql, $myrmconn) or print(mysql_error());
   }
   else if(isset($_POST["bt_conference_publication_create"]))
   {
      $sql = "INSERT INTO ConferencePublications (`idPublication`, `conference`, `location`, `date`) VALUES ('$pid', '$conference', '$location', '$date')";
      $exe = mysql_query($sql, $myrmconn) or print(mysql_error());
   }
   else
   {
      die("NOT A CONFERENCE OR A JOURNAL PUBLICATION!");
   }


   //header("Location: myrm.php");
?>
