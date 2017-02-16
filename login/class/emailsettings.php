<?php
class EmailSettings extends AppConfig {

    public function testMailSettings(){

        if ($this->mail_server == ''){

            $resp['status'] = 'false';
            $resp['message'] = "No mail server specified!";

            $this->updateMultiSettings(array('email_working'=>'false'));

            return $resp;

        } else {

            $resp = array();
            /**
             * This uses the SMTP class alone to check that a connection can be made to an SMTP server,
             * authenticate, then disconnect
             */

            if ($this->mail_server_type == 'smtp') {

                date_default_timezone_set('Etc/UTC');

                require_once $this->base_dir.'/login/vendor/phpmailer/phpmailer/PHPMailerAutoload.php';

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

                            $resp['status'] = 'true';
                            $resp['message'] = "Successful Connection!";

                            $this->updateMultiSettings(array('email_working'=>'false'));

                            return $resp;

                        } else {
                            throw new Exception('Authentication failed: ' . $smtp->getError()['error']);
                        }
                    }

                } catch (Exception $e) {

                    $resp['status'] = 'false';
                    $resp['message'] = 'SMTP error: ' . $e->getMessage();

                    $this->updateMultiSettings(array('email_working'=>'false'));

                    return $resp;
                }
                //Whatever happened, close the connection.
                $smtp->quit(true);

            }
        }
    }
}
