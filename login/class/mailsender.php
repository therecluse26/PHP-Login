<?php
/**
* Blah blah
*/
class MailSender extends AppConfig
{
    public function sendMail($userarr, $type)
    {
        $resp = array();
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
        if ($this->mail_server_type == 'smtp') {

            $mail->IsSMTP();
            $mail->SMTPAuth = true;
            $mail->Host = $this->mail_server;
            $mail->SMTPSecure = $this->mail_security;
            $mail->Port = $this->mail_port;
            $mail->Username = $this->mail_user;
            $mail->Password = $this->mail_pw;
            $mail->SMTPDebug = 0; //Set to 0 to disable debugging (for production)

            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
        }

        if ($type == 'Verify') {

            $admins = UserData::adminEmailList();

            foreach($userarr as $usr){

                $uid_64 = base64_encode($usr['id']);

                $verifyurl = $this->base_url . "/login/verifyuser.php?v=1&uid=" . $uid_64;

                $mail->AddBCC($usr['email'], $usr['username']);

                if ($this->admin_verify == 'true') {

                    foreach ($admins as $admin) {
                        $mail->AddBCC($admin['email'], $usr['username']);
                    }
                }
            }

            include $this->base_dir."/login/partials/mailtemplates/verifyemail.php";

                //Set the subject line
                $mail->Subject = $usr['username']. ' Account Verification';

                //Set the body of the message
                $mail->Body = $verify_template;

                if ($this->admin_verify == true) {
                    $mail->AltBody = $this->verify_email_admin;
                } else {

                    $mail->AltBody = $this->verify_email_noadmin . $verifyurl;
                }

            } elseif ($type == 'Active') {


                foreach($userarr as $usr){

                    $mail->AddBCC($usr['email'], $usr['username']);

                }

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
        require $this->base_dir.'/login/vendor/phpmailer/phpmailer/PHPMailerAutoload.php';

        try {

        $mail = new PHPMailer(true);
        $mail->isHTML(true);
        $mail->CharSet = "text/html; charset=UTF-8;";
        $mail->WordWrap = 80;
        $mail->setFrom($this->from_email, $this->from_name);
        $mail->AddReplyTo($this->from_email, $this->from_name);

        //SMTP Settings
        if ($this->mail_server_type == 'smtp') {

            $mail->IsSMTP(); //Enable SMTP
            $mail->SMTPAuth = true; //SMTP Authentication
            $mail->Host = $this->mail_server; //SMTP Host
            //Defaults: Non-Encrypted = 25, SSL = 465, TLS = 587
            $mail->SMTPSecure = $this->mail_security; // Sets the prefix to the server
            $mail->Port = $this->mail_port; //SMTP Port
            //SMTP user auth
            $mail->Username = $this->mail_user; //SMTP Username
            $mail->Password = $this->mail_pw; //SMTP Password
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
        $mail->Subject = $this->site_name . ' Password Reset';

        include $this->base_dir."/login/partials/mailtemplates/resetemail.php";


        //Set the body of the message
        $mail->Body = $reset_template;
        $mail->AltBody = $this->reset_email . $this->signin_url;

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

    public function testMail(){

        $resp = array();
        /**
         * This uses the SMTP class alone to check that a connection can be made to an SMTP server,
         * authenticate, then disconnect
         */
        //SMTP needs accurate times, and the PHP time zone MUST be set
        //This should be done in your php.ini, but this is how to do it if you don't have access to that

        if ($this->mail_server_type == 'smtp') {

            date_default_timezone_set('Etc/UTC');

            require $this->base_dir.'/login/vendor/phpmailer/phpmailer/PHPMailerAutoload.php';
            //Create a new SMTP instance
            $smtp = new SMTP;
            //Enable connection-level debug output
            //$smtp->do_debug = SMTP::DEBUG_CONNECTION;
            try {
                //Connect to an SMTP server
                if (!$smtp->connect($this->mail_server, $this->mail_port)) {
                    throw new Exception('Connect failed');
                }
                //Say hello
                if (!$smtp->hello(gethostname())) {
                    throw new Exception('EHLO failed: ' . $smtp->getError()['error']);
                }
                //Get the list of ESMTP services the server offers
                $e = $smtp->getServerExtList();
                //If server can do TLS encryption, use it
                if (is_array($e) && array_key_exists('STARTTLS', $e)) {
                    $tlsok = $smtp->startTLS();
                    if (!$tlsok) {
                        throw new Exception('Failed to start encryption: ' . $smtp->getError()['error']);
                    }
                    //Repeat EHLO after STARTTLS
                    if (!$smtp->hello(gethostname())) {
                        throw new Exception('EHLO (2) failed: ' . $smtp->getError()['error']);
                    }
                    //Get new capabilities list, which will usually now include AUTH if it didn't before
                    $e = $smtp->getServerExtList();
                }
                //If server supports authentication, do it (even if no encryption)
                if (is_array($e) && array_key_exists('AUTH', $e)) {
                    if ($smtp->authenticate($this->mail_user, $this->mail_pw)) {

                        $resp['status'] = true;
                        $resp['message'] = "Successful Connection!";
                        return $resp;

                    } else {
                        throw new Exception('Authentication failed: ' . $smtp->getError()['error']);
                    }
                }

            } catch (Exception $e) {

                $resp['status'] = false;
                $resp['message'] = 'SMTP error: ' . $e->getMessage();

                return $resp;
            }
            //Whatever happened, close the connection.
            $smtp->quit(true);

        }
    }
}
