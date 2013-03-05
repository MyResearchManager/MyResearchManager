<?php
    include "connection.php";

    require_once("util.php");

    if(isset($_GET["usercode"]))
    {
        $usercode = $_GET["usercode"];

        $email = "NO_EMAIL";
        $sql = "SELECT email FROM Users WHERE confirmationCode = '$usercode'";
        $exe = mysql_query( $sql, $myrmconn) or print(mysql_error());

        if($row = mysql_fetch_array($exe))
            $email = $row['email'];

        echo $email;
    }
    else
        die("NO_EMAIL");
?>
