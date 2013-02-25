<?php
    include "connection.php";

    $email = "";
    $code = "";
 
    if(isset($_GET["email"]) && isset($_GET["code"]))
    {
        $email = $_GET["email"];
        $code  = $_GET["code"];

        $uid = -1;

        $sql = "UPDATE Users SET checkedEmail = '1' WHERE email = '$email' AND confirmationCode = '$code'";
        $exe = mysql_query( $sql, $myrmconn) or print(mysql_error());

        if(mysql_affected_rows()==1)
            echo "Email confirmed!";
        else
            echo "Error validating email or already validated!";
    }
    else
        die("No such email address!");
?>
