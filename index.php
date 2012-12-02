<?php
   ob_start();
   session_start();

   if ($_SESSION['logado'] == 1)
        header("Location: myrm.php");
   else
        header("Location: login.php");
?>

<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF8" />
 <title> MyResearchManager </title> </head>
 <?php

function getRealIpAddr()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
    {
      $ip=$_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
    {
      $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
      $ip=$_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}
  ?>


    <style type="text/css" media="all">
         ul
         {
            list-style-position: outside;
            list-style-type: circle;
         }
         .style2
         {
            font-family: Verdana, Arial, Helvetica, sans-serif;
                font-size: 11px;
                line-height: 16px;
         }
         .style3
         {
                font-family: Verdana, Arial, Helvetica, sans-serif;
                font-size: 12px;
                font-weight: bold;
                color: #FFFFFF;
         }
         #edt a:link
         {
            color:#0000ff;
            text-decoration: none;
         }
         #edt a:hover
         {
            color:#0000ff;
            #text-decoration: none;
         }
         #edt a:visited
         {
            color:#0000ff;
            #text-decoration: none;
         }
         #edt a:active
         {
            color:#090;
            #text-decoration: none;
         }
         a:link
         {
                color: #0000ff;
             #   text-decoration: none;
         }
         a:hover
         {
                color: #006600;
              #  text-decoration: none;
         }
         a:visited
         {
            color:#0000ff;
            #text-decoration: none;
         }
         a:active
         {
                color: #0000ff;
             #   text-decoration: none;
         }
      </style>
<body>

<h1 align="left"> MyResearchManager </h1>
<h2 align="left"> Inform your username and password</h2>

<form name="frm_login" method="post" action="login.php">
<table>
   <tr> <td>email: </td><td><input type="text" value="" name="login"></td> </tr>
   <tr> <td>password: </td><td><input type="password" value="" name="senha"></td> </tr>   
   <tr> <td colspan='2' align="center"> <input type="submit" value="Login" name="bt_entrar"> </td></tr> 
</table>
</form>

<i> Your ip address is: <?php echo getRealIpAddr(); ?> </i> <br>


</body>


</html>
