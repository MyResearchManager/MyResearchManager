<?php
    include "connection.php";

    require_once('captcha/recaptchalib.php');
    $resp = recaptcha_check_answer ($recaptcha_private_key,
                                $_SERVER["REMOTE_ADDR"],
                                $_POST["recaptcha_challenge_field"],
                                $_POST["recaptcha_response_field"]);

    if (!$resp->is_valid) 
    {
      die ("The reCAPTCHA wasn't entered correctly. <a href=\"login.php\">Go back</a> and try it again.");// . "(reCAPTCHA said: " . $resp->error . ")");
      //header("Location: login.php");
    }

    $email = "";
 
    if( isset($_POST["email"]) || isset($_GET["email"]))
    {
            if(isset($_POST["email"]))
                $email = $_POST["email"];
            else
                $email = $_GET["email"];

            $uid = -1;

            $code = md5(uniqid(rand(), true));

            $sql = "UPDATE Users SET confirmationCode = '$code' WHERE email = '$email'";
            //echo "SQL: $sql ";

            $exe = mysql_query( $sql, $myrmconn) or print(mysql_error());

            // ===========
            // BEGIN EMAIL
            // ===========

            require_once('phpmailer/class.phpmailer.php');

            $mail = new PHPMailer(); // defaults to using php "mail()"

            $mail->IsSMTP(); // telling the class to use SendMail transport
            $mail->Host       = $myrm_smtp_server; // SMTP server
            //$mail->SMTPDebug  = 2;                     // enables SMTP debug information (for testing)
                                           // 1 = errors and messages
                                           // 2 = messages only
            $mail->SMTPAuth   = true;
            $mail->Host       = $myrm_smtp_server;
            $mail->Port       = $myrm_smtp_port; 

            $mail->Username   = $myrm_originmail;
            $mail->Password   = $myrm_originmailpwd;

            //echo "$myrm_smtp_server<br>$myrm_smtp_port<br>$myrm_originmail<br>$myrm_originmailpwd<br>";


            $receive_script = $myrm_domain_name.'/myrm/receive_confirmation_email.php?email='.$email.'&code='.$code;

            //$body = //file_get_contents('contents.html');
            $body = "Greetings from MyResearchManager!<br><br>To confirm your email follow <a href=\"$receive_script\">this link</a>.<br><br>Or paste this link in your browser: $receive_script<br><br>Goodbye!";
            $body = eregi_replace("[\]",'',$body);

            $mail->AddReplyTo($myrm_originmail, "MyResearchManager $myrm_domain_name");
            $mail->SetFrom($myrm_originmail, "MyResearchManager $myrm_domain_name");

            $mail->AddAddress($email);

            $mail->Subject    = "Confirmation email from MyResearchManager";

            $mail->AltBody    = $receive_script; // without HTML

            $mail->MsgHTML($body);

            if(!$mail->Send())
            {
                echo "Mailer Error: " . $mail->ErrorInfo;
            }
            else
            {
                echo "Message sent!";
            }

/*
            //$from = "noreply@$domainname";
            $from = "igor.machado@yahoo.com.br";


            //$to      = $email;
            $to = "check-auth@verifier.port25.com";
            $subject = 'Confirmation email from MyResearchManager';
            //$message = 'Hello from MyResearchManager!\nTo confirm your email put this in your browser: '.$receive_script."\nGoodbye!";
            $message = "Hello!!!";

            $headers = 'From: noreply@'.$domainname. "\r\n" .
                       'Reply-To: noreply@'.$domainname. "\r\n" .
                       'X-Mailer: PHP/' . phpversion();
            $ip = getenv('REMOTE_ADDR');

            $headers = "MIME-Version: 1.0\r\n";
            $headers .= "Content-type: text/plain; charset=iso-8859-2\r\nContent-Transfer-Encoding: 8bit\r\nX-Priority: 1\r\nX-MSMail-  Priority: High\r\n";
            $headers .= "From: $from\r\n" . "Reply-To: $from\r\n" . "X-Mailer: PHP/" . phpversion() . "\r\n" . "X-originating-IP: " . $ip . "\r\n";

            // fix Gmail call!
            if (preg_match('/gmail/',$to))
                $headers = str_replace("\r\n","\n", $headers);

            $success = @mail ($to, $subject, $message, $headers);

            echo "<b>Confirmation email sent successfully to:</b> $email (with success code: $success)<br>";
            echo "FROM: $from TO: $to SUBJECT: $subject<br>";
            echo "HEADERS: $headers<br>";
            echo "BODY: $message<br>";
*/
    }
    else
        die("No such email address!");

?>
