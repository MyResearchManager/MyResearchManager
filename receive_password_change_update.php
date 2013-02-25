<?php
    include "connection.php";

    $email = "";
    $code = "";

    require_once('captcha/recaptchalib.php');
    $resp = recaptcha_check_answer ($recaptcha_private_key,
                                $_SERVER["REMOTE_ADDR"],
                                $_POST["recaptcha_challenge_field"],
                                $_POST["recaptcha_response_field"]);

    if (!$resp->is_valid) 
    {
        die ("The reCAPTCHA wasn't entered correctly. Use the BACK button in your navigator.");
    }

 
    if(isset($_POST["email"]) && isset($_POST["code"]) && isset($_POST["password1"]) && isset($_POST["password2"]))
    {
        $password1 = $_POST["password1"];
        $password2 = $_POST["password2"];

        if ($password1 != $password2) 
        {
            die ("The passwords are different! Use the BACK button in your navigator.");
        }

        $email = $_POST["email"];
        $code  = $_POST["code"];

        $sql = "UPDATE Users SET password = MD5(CONCAT('$password1', salt)) WHERE email = '$email' AND confirmationCode = '$code'";
        $exe = mysql_query( $sql, $myrmconn) or print(mysql_error());

        //echo $sql;

        if(mysql_affected_rows()==1)
        {
            echo "Password changed!";
            $sql = "UPDATE Users SET confirmationCode = 'MD5(RAND())' WHERE email = '$email'";
            $exe = mysql_query( $sql, $myrmconn) or print(mysql_error());
        }
        else
            echo "Error changing password! Request a new 'forgot password' in login.";

        echo "<br><br><a href=\"login.php\">Go to login</a>";
    }
    else
        die("No such email address!");
?>
