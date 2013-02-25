<?php
   ob_start();
   session_start();

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


        if(isset($_POST["bt_forgot"])) // FORGOT PASSWORD!
        {
            $email = $login;

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


            $receive_script = $myrm_domain_name.'/myrm/receive_password_change.php?email='.$email.'&code='.$code;

            //$body = //file_get_contents('contents.html');
            $body = "Greetings from MyResearchManager!<br><br>Somebody asked to change your password! IF IT IS NOT YOU, IGNORE THIS MESSAGE!!<br>To change your password follow <a href=\"$receive_script\">this link</a>.<br><br>Or paste this link in your browser: $receive_script<br><br>Goodbye!";
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

            die("Check your email to update your password!");
        }


               $idUsuario = -1;

               $sql = "SELECT idUser, name, password, email FROM Users WHERE email = '$login' and password = 
MD5(CONCAT('$senha',salt))" ;

               //echo $sql;

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
        //header("Location: login.php");
        die ("Wrong email or password! <a href=\"login.php\">Go back</a> and try it again.");
   }

?>
