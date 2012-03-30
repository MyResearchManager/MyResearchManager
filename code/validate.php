<?php
   ob_start();
   session_start();

   include "connection.php";

   $login = "";
   $senha = "";
 
   if( isset($_POST["login"]) || isset($_GET["login"]))
   {
                if(isset($_POST["login"]))
		  $login = $_POST["login"];
                else
		  $login = $_GET["login"];

                if(isset($_POST["senha"]))
		  $senha = $_POST["senha"];
                else
		  $senha = $_GET["senha"];

               $idUsuario = -1;

               $sql = "SELECT idUser, name, password, email FROM Users WHERE active='1' and email= '$login' and password = MD5('$senha')" ;

               echo $sql;

               $exe = mysql_query( $sql, $myrmconn) or print(mysql_error());

               if($exe != null)
                 if($linha = mysql_fetch_array($exe))
                 {
                    $idUsuario = $linha[idUser];
                 }

               if($idUsuario >= 0)
               {
                  $_SESSION['logado'] = 1;
               }
               else
                  $_SESSION['logado'] = 0;
               
               $_SESSION['id'] = $idUsuario;
      }


   if ($_SESSION['logado'] == 1)
        header("Location: myrm.php");
   else
   {
        $_SESSION['id'] = -1;
        $_SESSION['gid'] = -1;
        header("Location: login.php");
   }

?>
