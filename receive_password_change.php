<?php
    include "connection.php";

    $email = "";
    $code = "";
 
    if(isset($_GET["email"]) && isset($_GET["code"]))
    {
        $email = $_GET["email"];
        $code  = $_GET["code"];

        $sql = "SELECT * FROM Users WHERE email = '$email' AND confirmationCode = '$code'";
        $exe = mysql_query( $sql, $myrmconn) or print(mysql_error());

        if(mysql_num_rows($exe)==1)
        {

            echo "<form name=\"frm_update_pwd\" method=\"post\" action=\"receive_password_change_update.php\">
                  <table>
                     <tr> <td><input type=\"hidden\" value=\"$email\" name=\"email\"></td> </tr>
                     <tr> <td><input type=\"hidden\" value=\"$code\" name=\"code\"></td> </tr>
                     <tr> <td>new password: </td><td><input type=\"password\" value=\"\" name=\"password1\"></td> </tr>
                     <tr> <td>repeat:</td><td><input type=\"password\" value=\"\" name=\"password2\"></td></tr>
                     <tr> <td>challenge:</td><td>";

            require_once('captcha/recaptchalib.php');
            echo recaptcha_get_html($recaptcha_public_key);

            echo "   </td></tr>
                     <tr> <td colspan='2' align=\"center\"> <input type=\"submit\" value=\"Update\" name=\"bt_update_password\"> </td></tr> 
                  </table>
                  </form>";
        }
        else
            echo "Error changing password! Request a new 'forgot password' in login.";

        echo "<br><br><a href=\"login.php\">Go to login</a>";
    }
    else
        die("No such email address!");
?>
