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
  
   $sql = "UPDATE DynamicTables SET locked = '1' WHERE idDynamicTable = '$idTable'";
   $exe = mysql_query($sql, $myrmconn) or print(mysql_error());

   echo "OK";
?>
