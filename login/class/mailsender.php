<?php
class MailSender
{
    public function sendMail($userarr, $type)
    {
        if (file_exists('config.php') && file_exists('vendor/phpmailer/phpmailer/PHPMailerAutoload.php')) {
            require 'config.php';
            require 'vendor/phpmailer/phpmailer/PHPMailerAutoload.php';
        } else {
            require '../config.php';
            require '../vendor/phpmailer/phpmailer/PHPMailerAutoload.php';
        }

        // ADD $_SERVER['SERVER_PORT'] TO $verifyurl STRING AFTER $_SERVER['SERVER_NAME'] FOR DEV URLS USING PORTS OTHER THAN 80
        // substr() trims "createuser.php" off of the current URL and replaces with verifyuser.php
        // Can pass 1 (verified) or 0 (unverified/blocked) into url for "v" parameter

        // Create a new PHPMailer object
        // ADD sendmail_path = "env -i /usr/sbin/sendmail -t -i" to php.ini on UNIX servers
        $mail = new PHPMailer;
        $mail->isHTML(true);
        $mail->CharSet = "text/html; charset=UTF-8;";
        $mail->WordWrap = 80;
        $mail->setFrom($from_email, $from_name);
        $mail->AddReplyTo($from_email, $from_name);

        //SMTP Settings
        if ($mail_server_type == 'smtp') {

            $mail->IsSMTP(); //Enable SMTP
            $mail->SMTPAuth = true; //SMTP Authentication
            $mail->Host = $smtp_server; //SMTP Host
            //Defaults: Non-Encrypted = 25, SSL = 465, TLS = 587
            $mail->SMTPSecure = $smtp_security; // Sets the prefix to the server
            $mail->Port = $smtp_port; //SMTP Port
            //SMTP user auth
            $mail->Username = $smtp_user; //SMTP Username
            $mail->Password = $smtp_pw; //SMTP Password
            //********************
            $mail->SMTPDebug = 0; //Set to 0 to disable debugging (for production)

            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
        }
        /****
        * Set who the message is to be sent to
        * CAN BE SET TO AddBCC(youremail@website.com, 'Your Name') FOR PRIVATE USER APPROVAL BY MODERATOR
        * SET TO AddBCC($email, $user) FOR USER SELF-VERIFICATION
        *****/
       
        foreach($userarr as $usr){

            $uid_64 = base64_encode($usr['id']);
            
            $verifyurl = $base_url . "/login/verifyuser.php?v=1&uid=" . $uid_64;

            if($admin_verify == true){
                    $mail->AddBCC($admin_email, $usr['username']);

            } else {
                    $mail->AddBCC($usr['email'], $usr['username']);
            }
        }

            if ($type == 'Verify') {

                    //Set the subject line
                    $mail->Subject = $usr['username']. ' Account Verification';

                    //Set the body of the message
                    $mail->Body = $verifymsg . '<br><a href="'.$verifyurl.'">'.$verifyurl.'</a>';

                    $mail->AltBody  =  $verifymsg . $verifyurl;

                } elseif ($type == 'Active') {

                    //Set the subject line
                    $mail->Subject = $site_name . ' Account Created!';

                    //Set the body of the message
                    $mail->Body = $active_email . '<br><a href="'.$signin_url.'">'.$signin_url.'</a>';
                    $mail->AltBody  =  $active_email . $signin_url;

            };
        
            try {

                $mail->Send();

            } catch (phpmailerException $e) {

                echo $e->errorMessage();// Error messages from PHPMailer

            }
        
    }
}