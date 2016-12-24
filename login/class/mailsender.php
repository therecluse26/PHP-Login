<?php
class MailSender extends MailConf
{
    public function sendMail($userarr, $type)
    {
        $resp = array();
        require $this->base_dir.'/login/config.php';
        require $this->base_dir.'/login/vendor/phpmailer/phpmailer/PHPMailerAutoload.php';

        /* ADD $_SERVER['SERVER_PORT'] TO $verifyurl STRING AFTER $_SERVER['SERVER_NAME'] FOR DEV URLS USING PORTS OTHER THAN 80 */

        //ADD sendmail_path = "env -i /usr/sbin/sendmail -t -i" to php.ini on UNIX servers

        $mail = new PHPMailer;
        $mail->isHTML(true);
        $mail->CharSet = "text/html; charset=UTF-8;";
        $mail->WordWrap = 80;
        $mail->setFrom($this->from_email, $this->from_name);
        $mail->AddReplyTo($this->from_email, $this->from_name);

        //SMTP Settings
        if ($mail_server_type == 'smtp') {

            $mail->IsSMTP(); //Enable SMTP
            $mail->SMTPAuth = true; //SMTP Authentication
            $mail->Host = $this->smtp_server; //SMTP Host
            //Defaults: Non-Encrypted = 25, SSL = 465, TLS = 587
            $mail->SMTPSecure = $this->smtp_security; // Sets the prefix to the server
            $mail->Port = $this->smtp_port; //SMTP Port
            //SMTP user auth
            $mail->Username = $this->smtp_user; //SMTP Username
            $mail->Password = $this->smtp_pw; //SMTP Password
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

            $verifyurl = $this->base_url . "/login/verifyuser.php?v=1&uid=" . $uid_64;

            if ($this->admin_verify == true) {
                    $mail->AddBCC($this->admin_email, $usr['username']);

            } else {
                    $mail->AddBCC($usr['email'], $usr['username']);
            }
        }

            if ($type == 'Verify') {

                include $this->base_dir."/login/partials/mailtemplates/verifyemail.php";

                    //Set the subject line
                    $mail->Subject = $usr['username']. ' Account Verification';

                    //Set the body of the message
                    $mail->Body = $verify_template;

                    $mail->AltBody  =  $this->verify_email . $verifyurl;

                } elseif ($type == 'Active') {

                    include $this->base_dir."/login/partials/mailtemplates/activeemail.php";

                    //Set the subject line
                    $mail->Subject = $this->site_name . ' Account Created!';

                    //Set the body of the message
                    $mail->Body = $active_template;

                    $mail->AltBody  =  $this->active_email . $this->signin_url;

                };

            try {

                $mail->Send();

                $resp['status'] = true;
                $resp['message'] = '';

                return $resp;

            } catch (phpmailerException $e) {

                $resp['status'] = false;
                $resp['message'] = $e->errorMessage();

                return $resp;

            }

    }

    public function sendResetMail($reset_url, $to_email, $username) {

        $resp = array();
        require $this->base_dir.'/login/config.php';
        require $this->base_dir.'/login/vendor/phpmailer/phpmailer/PHPMailerAutoload.php';

        try {

        $mail = new PHPMailer(true);
        $mail->isHTML(true);
        $mail->CharSet = "text/html; charset=UTF-8;";
        $mail->WordWrap = 80;
        $mail->setFrom($this->from_email, $this->from_name);
        $mail->AddReplyTo($this->from_email, $this->from_name);

        //SMTP Settings
        if ($mail_server_type == 'smtp') {

            $mail->IsSMTP(); //Enable SMTP
            $mail->SMTPAuth = true; //SMTP Authentication
            $mail->Host = $this->smtp_server; //SMTP Host
            //Defaults: Non-Encrypted = 25, SSL = 465, TLS = 587
            $mail->SMTPSecure = $this->smtp_security; // Sets the prefix to the server
            $mail->Port = $this->smtp_port; //SMTP Port
            //SMTP user auth
            $mail->Username = $this->smtp_user; //SMTP Username
            $mail->Password = $this->smtp_pw; //SMTP Password
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

        $mail->AddBCC($to_email, $username);


        //Set the subject line
        $mail->Subject = $site_name . ' Password Reset';

        //Set the body of the message
        $mail->Body = "Click the link below to reset your password:<br><a href='".$reset_url."'>".$reset_url."</a>";
        $mail->AltBody  =  $active_email . $signin_url;

        $mail->Send();

        $resp['status'] = true;
        $resp['message'] = "Password reset sent! Check your email";
        return $resp;


        } catch (phpmailerException $e) {
            $resp['status'] = false;
            $resp['message'] = $e->errorMessage();
            return $resp;

        } catch (Exception $e) {
            $resp['status'] = false;
            $resp['message'] = $e->getMessage();
            return $resp;
        }
    }
}
