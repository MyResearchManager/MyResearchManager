<?php
    include "connection.php";

    $email = "";
    $code = "";
 
    if(isset($_GET["email"]) && isset($_GET["code"]))
    {
        $email = $_GET["email"];
        $code  = $_GET["code"];
        $new_code = md5(uniqid(rand(), true));

        $new_pwd = substr(md5(uniqid(rand(), true)), 0, 5);

        $sql = "UPDATE Users SET password = MD5(CONCAT('$new_pwd', salt)) WHERE email = '$email' AND confirmationCode = '$code'";
        $exe = mysql_query( $sql, $myrmconn) or print(mysql_error());

        //echo $sql;

        if(mysql_affected_rows()==1)
        {
            echo "Email confirmed! Your new password is: $new_pwd";
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
