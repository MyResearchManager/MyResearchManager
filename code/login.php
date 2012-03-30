<?php
   ob_start();
   session_start();

   if ($_SESSION['logado'] == 1)
        header("Location: myrm.php");
?>

<html>
<head>
<title>MyResearchManager - Login</title>
</head>

<body>

<h1 align="left"> MyResearchManager </h1>
<b>Inform your username and password</b>
<br>

<form name="frm_login" method="post" action="validate.php">
<table>
   <tr> <td>email: </td><td><input type="text" value="" name="login"></td> </tr>
   <tr> <td>password:</td><td><input type="password" value="" name="senha"></td></tr>
   <tr> <td colspan='2' align="center"> <input type="submit" value="Login" name="bt_entrar"> </td></tr> 
</table>
</form>   


</body>

</html>
