<?php
   ob_start();
   session_start();

   if ($_SESSION['logado'] == 1)
        header("Location: myrm.php");

   require_once("connection.php");
?>

<html>
<head>
<title>MyResearchManager - Login</title>
<link rel="shortcut icon" href="myrm.ico"/>
</head>

<body>

<br>
<img src="myrm.jpg" width="350"><br>
<br>
<b>Inform your username and password</b><br>
<br>

<form name="frm_login" method="post" action="validate.php">
<table>
   <tr> <td>email: </td><td><input type="text" value="" name="login"></td> </tr>
   <tr> <td>password:</td><td><input type="password" value="" name="senha"></td></tr>
   <?php
      if($use_recaptcha)
      {
         echo "<tr><td>challenge:</td><td>";
         require_once('captcha/recaptchalib.php');
         echo recaptcha_get_html($recaptcha_public_key);
         echo "</td></tr>";
      }
   ?>
   <tr> <td colspan='2' align="center"> <input type="submit" value="Login" name="bt_login"> <input type="submit" value="Forgot password" name="bt_forgot"> </td></tr> 
</table>
</form>   


</body>

</html>
