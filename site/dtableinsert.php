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
      if($linha = mysql_fetch_array($exe))
      {  
         $idTable = $linha['idDynamicTable'];
         $locked  = $linha['locked'];
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
  
   $row = -1;

   if(isset($_GET["row"]))
   {
      $row = $_GET["row"];
      $row++;
      $row--;
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
      echo "OK";
   else
      echo "ERROR";
?>
