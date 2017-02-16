<?php
class EmailLogger {

    public static function logResponse($emailresp, $recipient, $type, $mailstatus) {

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

      if($mailstatus != false) {

           $status = 'Success';
           $response = 'Mail sent successfully to: ' . $toString;

       } else {

           $status = 'Error';
           $response = $emailresp;

          if (AppConfig::pullSetting('email_working') == 'true') {

            $emailSettings = new EmailSettings;
            $emailSettings->testMailSettings();

          }

       };

        $db = new DbConn;
        $tbl_mailLog = $db->tbl_mailLog;

        $stmt = $db->conn->prepare("INSERT INTO ".$tbl_mailLog." (type, recipient, status, response) VALUES (:type, :recipient, :status, :response)");
        $stmt->bindParam(':type', $type);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':recipient', $toString);
        $stmt->bindParam(':response', $response);
        $stmt->execute();
        unset($stmt);

        } catch (PDOException $e) {

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

    public static function PullLog($limit = 10, $offset = 0, $read = 0) {
        try {
            $db = new DbConn;
            $tbl_mailLog = $db->tbl_mailLog;

            $stmt = $db->conn->prepare("SELECT id, type, status, recipient, response, timestamp FROM ".$tbl_mailLog." where isread = ".$read." order by timestamp desc limit ".$limit." offset ".$offset);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {

            $result = "Error: " . $e->getMessage();

        }

        return $result;
    }

    public static function PullLogCount($read = 0) {
        try {
            $db = new DbConn;
            $tbl_mailLog = $db->tbl_mailLog;

            $stmt = $db->conn->prepare("SELECT ifnull(count(distinct id), 0) as rowcount FROM ".$tbl_mailLog." where isread = ".$read);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {

            $result = "Error: " . $e->getMessage();

        }

        return $result;
    }
}
