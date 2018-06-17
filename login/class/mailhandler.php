<?php
/**
* PHPLogin\MailHandler extends AppConfig
*/
namespace PHPLogin;

/**
* Handles all email-related logic
*/
class MailHandler extends AppConfig
{
    private $mail_config;
    public $mail;

    /**
     * Class constructor, instantiates PHPMailer object and sets basic config
     * @param array $config Array of configuration values, any values not set will revert to defaults
     */
    public function __construct(array $config = [])
    {
        parent::__construct();

        $default_config = [
                            'isHTML'=>true,
                            'CharSet'=>'text/html; charset=UTF-8;',
                            'WordWrap'=>80,
                            'fromEmail'=>$this->from_email,
                            'fromName'=>$this->from_name,
                            'Host'=>$this->mail_server,
                            'sendType'=>$this->mail_sendtype,
                            'authType'=>$this->mail_authtype,
                            'SMTPAuth'=>true,
                            'SMTPSecure'=>$this->mail_security,
                            'Port'=>$this->mail_port,
                            'Username'=>$this->mail_user,
                            'Password'=>$this->mail_pw,
                            'SMTPOptions'=>[
                                              'ssl'=>[
                                                        'verify_peer'=>false,
                                                        'verify_peer_name'=>false,
                                                        'allow_self_signed'=>true
                                                      ]
                                            ],
                            'oauthUserEmail'=>$this->oath_user_email,
                            'oauthClientId'=>$this->oauth_client_id,
                            'oauthClientSecret'=>$this->oauth_client_secret,
                            'oauthRefreshToken'=>$this->oauth_refresh_token
                          ];

        $this->mail_config = array_merge($default_config, $config);

        $this->mail = new \PHPMailer;

        if ($this->mail_config['sendType'] == 'SMTP') {
            $this->mail->IsSMTP();
            $this->mail->SMTPAuth = $this->mail_config['SMTPAuth'];
            $this->mail->Host = $this->mail_config['Host'];
            $this->mail->SMTPSecure = $this->mail_config['SMTPSecure'];
            $this->mail->Port = $this->mail_config['Port'];
            $this->mail->SMTPDebug = 2; //Leave this set to 2; mail debug gets logged to mail_log db table
            $this->mail->SMTPOptions = $this->mail_config['SMTPOptions'];
        } elseif ($this->mail_config['sendType'] == 'mail()') {
            $this->mail->isSendmail();
        }

        switch ($this->mail_authtype) {
          case "Basic":
            $this->mail->Username = $this->mail_config['Username'];
            $this->mail->Password = CryptoHandler::decryptString($this->mail_config['Password']);
            break;

        }



        $this->mail->isHTML($this->mail_config['isHTML']);
        $this->mail->CharSet = $this->mail_config['CharSet'];
        $this->mail->WordWrap = $this->mail_config['WordWrap'];
        $this->mail->setFrom($this->mail_config['fromEmail'], $this->mail_config['fromName']);
        $this->mail->AddReplyTo($this->mail_config['fromEmail'], $this->mail_config['fromName']);
    }

    /**
     * Sends email to given users
     *
     * @param  array $userarr Array of users to send email to
     * @param  string $type    Type of email to send
     *
     * @return array
     */
    public function sendMail($userarr, $type)
    {
        $resp = array();

        if ($type == 'Verify') {
            $af = new AdminFunctions;
            $admins = $af->adminEmailList();

            foreach ($userarr as $usr) {
                $uid_64 = base64_encode($usr['id']);

                $verifyurl = $this->base_url . "/login/verifyuser.php?v=1&uid=" . $uid_64;

                $this->mail->AddBCC($usr['email'], $usr['username']);

                if ($this->admin_verify == 'true') {
                    foreach ($admins as $admin) {
                        $this->mail->AddBCC($admin['email'], $usr['username']);
                    }
                }
            }

            include $this->base_dir."/login/partials/mailtemplates/verifyemail.php";

            //Set the subject line
            $this->mail->Subject = $usr['username']. ' Account Verification';

            //Set the body of the message
            $this->mail->Body = $verify_template;

            if ($this->admin_verify == true) {
                $this->mail->AltBody = $this->verify_email_admin;
            } else {
                $this->mail->AltBody = $this->verify_email_noadmin . $verifyurl;
            }
        } elseif ($type == 'Active') {
            foreach ($userarr as $usr) {
                $this->mail->AddBCC($usr['email'], $usr['username']);
            }

            include $this->base_dir."/login/partials/mailtemplates/activeemail.php";

            //Set the subject line
            $this->mail->Subject = $this->site_name . ' Account Created!';

            //Set the body of the message
            $this->mail->Body = $active_template;
            $this->mail->AltBody  =  $this->active_email . $this->signin_url;
        };

        try {

          //Sends email and logs the response to mail_log table
            ob_start();
            $status = $this->mail->Send();
            $debugMsg = ob_get_contents();
            ob_get_clean();
            self::logResponse($debugMsg, $usr, $type, $status);

            $resp['status'] = true;
            $resp['message'] = '';

            return $resp;
        } catch (\phpmailerException $e) {
            $resp['status'] = false;
            $resp['message'] = $e->errorMessage();

            return $resp;
        }
    }

    /**
     * Sends password reset email
     *
     * @param string $reset_url Url for user to click on to reset password
     * @param string $to_email  Email address to send to
     * @param string $username  Username
     *
     * @return array
     */
    public function sendResetMail($reset_url, $to_email, $username)
    {
        $resp = array();
        require_once $this->base_dir.'/vendor/phpmailer/phpmailer/PHPMailerAutoload.php';

        try {
            $this->mail->AddBCC($to_email, $username);
            //Set the subject line
            $this->mail->Subject = $this->site_name . ' Password Reset';

            include $this->base_dir."/login/partials/mailtemplates/resetemail.php";

            //Set the body of the message
            $this->mail->Body = $reset_template;
            $this->mail->AltBody = $this->reset_email . $this->signin_url;

            //Sends email and logs the response to mail_log table
            ob_start();
            $status = $this->mail->Send();

            $debugMsg = ob_get_contents();
            ob_get_clean();

            $emailToLog = array("email"=>$to_email);

            self::logResponse($debugMsg, $emailToLog, 'Password Reset', $status);

            if (!$status) {
                throw new \Exception("Email failed to send, please contact $this->admin_email to resolve this issue");
            }

            $resp['status'] = true;
            $resp['message'] = "Password reset sent! Check your email";
            return $resp;
        } catch (\phpmailerException $e) {
            $resp['status'] = false;
            $resp['message'] = $e->errorMessage();
            return $resp;
        } catch (\Exception $e) {
            $resp['status'] = false;
            $resp['message'] = $e->getMessage();
            return $resp;
        }
    }

    /**
    * Returns list of all unread logs
    *
    * @param array $request $_GET request from ajax call made by DataTables
    * @param array $columns Array of columns to return
    *
    * @return array Array of email logs
    */
    public static function getUnreadLogs($request, $columns)
    {
        $bindings = array();

        try {
            $db = new DbConn;
            $tbl_mail_log = $db->tbl_mail_log;

            $where_sql = MiscFunctions::dt_filter($request, $columns, $bindings);
            $order_sql = MiscFunctions::dt_order($request, $columns);
            $limit_sql = MiscFunctions::dt_limit($request, $columns);

            if ($where_sql == '') {
                $where_sql = "WHERE";
            } else {
                $where_sql .= " AND";
            }

            $stmt = $db->conn->prepare("SELECT id, type, status, recipient, response, isread, timestamp FROM ".$tbl_mail_log." m
                                          $where_sql m.isread = 0
                                          $order_sql
                                          $limit_sql");

            $records_total = $db->conn->prepare("SELECT count(m.id) as count FROM ".$tbl_mail_log." m
                                          WHERE m.isread = 0");

            $records_filtered = $db->conn->prepare("SELECT count(m.id) as count FROM ".$tbl_mail_log." m
                                          $where_sql m.isread = 0");

            // Bind parameters
            if (is_array($bindings)) {
                for ($i=0, $ien=count($bindings) ; $i<$ien ; $i++) {
                    $binding = $bindings[$i];
                    $stmt->bindValue($binding['key'], $binding['val'], $binding['type']);
                    $records_filtered->bindValue($binding['key'], $binding['val'], $binding['type']);
                }
            }

            $records_total->execute();
            $records_filtered->execute();
            $stmt->execute();

            $active_user_count = $records_total->fetchColumn();
            $filtered_user_count = $records_filtered->fetchColumn();

            $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $return_data = array(
                "draw"            => $request['draw'],
                "recordsTotal"    => $active_user_count,
                "recordsFiltered" => $filtered_user_count,
                "data" => MiscFunctions::data_output($columns, $data)
              );

            $result = json_encode($return_data);

            return $return_data;
        } catch (\PDOException $e) {
            $result = "Error: " . $e->getMessage();
            return $result;
        }
    }

    /**
     * Delete email log
     *
     * @param  string  $logid       Log ID
     * @param  boolean $hard_delete Permanent delete
     *
     * @return boolean
     */
    public static function deleteLog($logid, $hard_delete = false)
    {
        try {
            $db = new DbConn;
            $err = null;

            if ($hard_delete) {
                $stmt = $db->conn->prepare('DELETE FROM '.$db->tbl_mail_log.' WHERE id = :id');
                $stmt->bindParam(':id', $logid);
            } else {
                $stmt = $db->conn->prepare('UPDATE '.$db->tbl_mail_log.' SET isread = 1 WHERE id = :id');
                $stmt->bindParam(':id', $logid);
            }

            $stmt->execute();
        } catch (\PDOException $e) {
            $err = 'Error: ' . $e->getMessage();
        }

        $resp = ($err == null) ? true : $err;

        return $resp;
    }

    /**
     * Logs email response to `mail_log` database table
     *
     * @param  string $emailresp  Email response message
     * @param  string $recipient  Email recipient
     * @param  string $type       Type of email sent
     * @param  $mailstatus        Mail status
     *
     * @return mixed
     */
    public static function logResponse(string $emailresp, array $recipient, string $type, $mailstatus)
    {
        try {
            $toString = '';

            //If there is an array of multiple recipients
            if (count($recipient) != count($recipient, COUNT_RECURSIVE)) {
                foreach ($recipient as $rec) {
                    $toString = $toString . $rec['email'].', ';
                }
            } else {
                $toString = $recipient['email'];
            }

            if ($mailstatus != false) {
                $status = 'Success';
                $response = 'Mail sent successfully to: ' . $toString;
            } else {
                $status = 'Error';
                $response = $emailresp;

                if (AppConfig::pullSetting('email_working') == 'true') {
                    $emailSettings = new self();
                    $emailSettings->testMailSettings();
                }
            };

            $db = new DbConn;
            $tbl_mail_log = $db->tbl_mail_log;

            $stmt = $db->conn->prepare("INSERT INTO ".$tbl_mail_log." (type, recipient, status, response) VALUES (:type, :recipient, :status, :response)");
            $stmt->bindParam(':type', $type);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':recipient', $toString);
            $stmt->bindParam(':response', $response);
            $stmt->execute();
            unset($stmt);
        } catch (\PDOException $e) {
            $err = "Error: " . $e->getMessage();
        }
        //Determines returned value ('true' or error code)
        if (!isset($err)) {
            $resp = true;
        } else {
            $resp = $err;
        };

        return $resp;
    }

    /**
     * Tests email settings in config panel
     *
     * @return string
     */
    public function testMailSettings()
    {
        if ($this->mail_server == '') {
            $resp['status'] = 'false';
            $resp['message'] = "No mail server specified!";

            $this->updateMultiSettings(array('email_working'=>'false'));

            return $resp;
        } else {
            $resp = array();

            /*
             * This uses the SMTP class alone to check that a connection can be made to an SMTP server,
             * authenticate, then disconnect
             */

            if ($this->mail_server_type == 'smtp') {
                date_default_timezone_set('Etc/UTC');

                require_once $this->base_dir.'/vendor/phpmailer/phpmailer/PHPMailerAutoload.php';

                $smtp = new \SMTP;
                //Enable connection-level debug output
                //$smtp->do_debug = SMTP::DEBUG_CONNECTION;
                try {
                    //Connect to an SMTP server
                    if (!$smtp->connect($this->mail_server, $this->mail_port)) {
                        throw new \Exception('Connect failed');
                    }
                    //Say hello
                    if (!$smtp->hello(gethostname())) {
                        throw new \Exception('EHLO failed: ' . $smtp->getError()['error']);
                    }
                    //Get the list of ESMTP services the server offers
                    $e = $smtp->getServerExtList();
                    //If server can do TLS encryption, use it
                    if (is_array($e) && array_key_exists('STARTTLS', $e)) {
                        $tlsok = $smtp->startTLS();
                        if (!$tlsok) {
                            throw new \Exception('Failed to start encryption: ' . $smtp->getError()['error']);
                        }
                        //Repeat EHLO after STARTTLS
                        if (!$smtp->hello(gethostname())) {
                            throw new \Exception('EHLO (2) failed: ' . $smtp->getError()['error']);
                        }
                        //Get new capabilities list, which will usually now include AUTH if it didn't before
                        $e = $smtp->getServerExtList();
                    }
                    //If server supports authentication, do it (even if no encryption)
                    if (is_array($e) && array_key_exists('AUTH', $e)) {
                        if ($smtp->authenticate($this->mail_user, CryptoHandler::decryptString($this->mail_pw))) {
                            $resp['status'] = 'true';
                            $resp['message'] = "Successful Connection!";

                            $this->updateMultiSettings(array('email_working'=>'false'));

                            return $resp;
                        } else {
                            throw new \Exception('Authentication failed: ' . $smtp->getError()['error']);
                        }
                    }
                } catch (\Exception $e) {
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
