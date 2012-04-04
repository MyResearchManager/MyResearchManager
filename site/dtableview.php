<?php
   ob_start();
   session_start();

   if ($_SESSION['logado'] != 1)
        header("Location: login.php");

   $id = $_SESSION['id'];

   $tid = -1;

   if(isset($_GET["tid"]))
   {
      $tid = $_GET["tid"];
   }
?>

<html>
<head>
 <title> MyResearchManager </title> </head>
<body>

<center> <h3> MyResearchManager - DynamicTable </h3> </center>

<br>


<?php

      include "connection.php";

      // incluir checagens!!

      $description = "*** no title ***";
      $key = "*** no key ***";
      $locked = 1;

      $sql = "SELECT `description`, `key`, `locked` FROM `DynamicTables` WHERE idDynamicTable = $tid";
      $exe = mysql_query( $sql, $myrmconn) or print(mysql_error());
      if($exe != null)
          if($line = mysql_fetch_array($exe))
          {
              $description = $line['description'];
              $key = $line['key'];
              $locked = $line['locked'];
          }

      echo "<b>Description:</b> $description <br>";
      echo "<b>Key:</b> $key <br>";

      if($locked == 0)
        echo "<b>Status:</b> unlocked <br>";
      else
        echo "<b>Status:</b> locked <br>";

      $sql = "SELECT count(*) AS total FROM `DynamicCells` WHERE idDynamicTable = $tid";
      $exe = mysql_query( $sql, $myrmconn) or print(mysql_error());
      if($exe != null)
          if($line = mysql_fetch_array($exe))
              $total = $line['total'];

      if($total==0) // empty table
      {
         echo "<b>Number of columns:</b> 0 <br>";
         echo "<b>Number of rows:</b> 0 <br>";
         echo "<br><br><a href=\"myrm.php\">Back</a>";
         echo "</body></html>";
         return;
      }


      $maxcol = -1;
      $maxrow = -1;

      $sql = "SELECT max( `column` ) AS maxcol FROM `DynamicCells` WHERE idDynamicTable = $tid";
      $exe = mysql_query( $sql, $myrmconn) or print(mysql_error());
      if($exe != null)
          if($line = mysql_fetch_array($exe))
              $maxcol = $line['maxcol'];

      $sql = "SELECT max( `row` ) AS maxrow FROM `DynamicCells` WHERE idDynamicTable = $tid";
      $exe = mysql_query( $sql, $myrmconn) or print(mysql_error());
      if($exe != null)
          if($line = mysql_fetch_array($exe))
              $maxrow = $line['maxrow'];

      echo "<b>Number of columns:</b> $maxcol <br>";
      echo "<b>Number of rows:</b> $maxrow <br>";

      echo "<br>";

      $vcolumn = array_fill(1, $maxcol, '');
      $table   = array_fill(1, $maxrow, $vcolumn);

      $duplicate = 0;

      $sql = "SELECT `column`, `row`, `value` FROM DynamicCells WHERE idDynamicTable = $tid";
      $exe = mysql_query( $sql, $myrmconn) or print(mysql_error());
      if($exe != null)
          while($line = mysql_fetch_array($exe))
          {
              $column = $line['column'];
              $row    = $line['row'];
              $value  = $line['value'];
              if($table[$row][$column]!='')
                 $duplicate = 1;
              $table[$row][$column] = $value;
          }

      echo "<table border=1>";
      for($i=1;$i<=$maxrow;$i++)
      {
         echo "<tr>";
         for($j=1;$j<=$maxcol;$j++)
         {
            $v = $table[$i][$j];
            echo "<td>$v</td>";
         }
         echo "</tr>";
      }
      echo "</table>";

      if($duplicate==1)
         echo "<br><b>ERROR: multiple values!</b><br>";

      echo "<br><br>";
      echo "<a href=\"dtableexportxls.php?tid=$tid\">Export as .xls</a> (TODO)";
?>

<br><br>

<a href="myrm.php">Back</a>

</body>


</html>
