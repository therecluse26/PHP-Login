<?php
class PasswordCrypt
{
    public static function encryptPw($password)
    {
        $pwresp = password_hash($password, PASSWORD_DEFAULT);
        return $pwresp;
    }

    public static function checkPw($userpassword, $dbpassword)
    {
        $pwresp = password_verify($userpassword, $dbpassword);
        return $pwresp;
    }
}
