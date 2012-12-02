<?php

   // ========================================
   // incluir varias checagens de seguranca!!!
   // ========================================

   include "connection.php";

   $key = "";

   if(isset($_GET["key"]))
   {
      $key = $_GET["key"];
   }

   $idTable = -1;

   $sql = "SELECT `idDynamicTable`, `key`, `locked` FROM DynamicTables WHERE `key` = '$key'";
   $exe = mysql_query( $sql, $myrmconn) or print(mysql_error());
   if($exe != null)
      if($line = mysql_fetch_array($exe))
      {  
         $idTable = $line['idDynamicTable'];
         $locked  = $line['locked'];
      }

   if($idTable < 1)
   {
      echo "TABLE_DOESNT_EXIST";
      return;
   }

   if($locked != 0)
   {
      echo "LOCKED_TABLE";
      return;
   }

   if(isset($_GET["col"]))
   {
      echo "POPULATE_BY_COLUMN_NOT_SUPPORTED";
      return;
   }
  
   $row = -1;

   if(isset($_GET["row"]))
   {
      $row = $_GET["row"];
      $row++;
      $row--;
   }
   else // get last row number from database
   {
      $row = 1; // default is one (for empty tables)

      $sql = "SELECT max(row) as mrow FROM DynamicCells WHERE `idDynamicTable` = '$idTable'";
      $exe = mysql_query( $sql, $myrmconn) or print(mysql_error());
      if($exe != null)
         if($line = mysql_fetch_array($exe))
         {  
            $row = $line['mrow'] + 1;
         }
   }

   if($row < 1)
   {
      echo "ROW_DOESNT_EXIST";
      return;
   }

   $col = 1;

   $ok = 0;

   while(true)
   {
      $c = "c".$col;
      if(isset($_GET[$c]))
      {
         $value = $_GET[$c];

         $sql = "INSERT INTO DynamicCells (`idDynamicTable`, `row`, `column`, `value`) VALUES ('$idTable', '$row', '$col', '$value')";
         $exe = mysql_query($sql, $myrmconn) or print(mysql_error());

         $ok = 1;
       
         $col++;
      }
      else
         break;
   }

   if($ok == 1)
   {
      $sql = "UPDATE DynamicTables SET lastUpdate=NOW() WHERE idDynamicTable = $idTable";
      $exe = mysql_query($sql, $myrmconn) or print(mysql_error());

      echo "OK";
   }
   else
      echo "ERROR";
?>
