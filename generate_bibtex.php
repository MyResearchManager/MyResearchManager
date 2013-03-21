<?php
//   ob_start();
//   session_start();

//   if ($_SESSION['logado'] != 1)
//        header("Location: login.php");

   require_once("util.php");

//   $id = $_SESSION['id'];

   $pid = -1;
   if(isset($_GET["pid"]))
      $pid = $_GET["pid"];

   if($pid <= 0)
      die("NO SUCH PUBLICATION!");
?>

<?php
      include "connection.php";

      $sql = "SELECT P.idPublication as pid, P.`title` as title, P.`year` as pyear FROM Publications as P WHERE P.idPublication=$pid ORDER BY pyear DESC";
      $exe = mysql_query( $sql, $myrmconn) or print(mysql_error());
      if($exe != null)
          if($line = mysql_fetch_array($exe))
          {
              $pid     = $line['pid'];
              $title   = $line['title'];
              $pyear   = $line['pyear'];
              $cpid = getConferencePublicationIdByPublicationId($pid);
              $jpid = getJournalPublicationIdByPublicationId($pid);

              if($cpid > 0)
                  echo "@INPROCEEDINGS{<br>";
              else if($jpid > 0)
                  echo "@ARTICLE{<br>";
              else
                  echo "@ERROR-NO-PUBLICATION-TYPE{";

              echo "authors = \"";

              $sql2 = "SELECT `idPublicationMember`, `order`, `idUser`, `idPublication` FROM PublicationMembers WHERE idPublication = $pid ORDER BY `order`";
              $exe2 = mysql_query( $sql2, $myrmconn) or print(mysql_error());

              $num_authors = mysql_num_rows($exe2);

              if($exe2 != null)
                       while($lauthors = mysql_fetch_array($exe2))
                       {
                          $uid1  = $lauthors['idUser'];
                          $ucitation = getUserCitationByUserId($uid1);
                          $order = $lauthors['order'];
                          echo "$ucitation";
                          if($order != $num_authors)
                             echo " & ";
                       }

              echo "\",<br>";

              echo "title = \"$title\",<br>";

              echo "year = \"$year\"<br>";

              echo "}<br>";
          }
?>

