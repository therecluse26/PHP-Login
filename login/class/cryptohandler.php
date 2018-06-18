<?php
/**
 * PHPLogin\CryptoHandler
 */
namespace PHPLogin;

/**
* Encryption-related functions
*
* Handles various encryption/decryption methods, mostly for safely handling external auth keys
*/
class CryptoHandler
{
    /**
     * Encrypts plaintext string
     * @param  string $raw_string Plaintext string to be encrypted
     * @param  string $key_file   .keystore file where encryption key is to be stored
     * @return string             Encrypted string
     */
    public static function encryptString(string $raw_string, string $key_file = __DIR__.'/../../.keystore'): string
    {
        try {
            $file = fopen($key_file, "r");
            $key = unserialize(fread($file, "10000"));
            fclose($file);
            $enc_string = \Defuse\Crypto\Crypto::encrypt($raw_string, $key);
            return $enc_string;
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return '';
        }
    }
    /**
   * Decrypts string stored in .keystore file and returns plaintext string
   * @param  string $enc_string Encrypted string to be decrypted
   * @param  string $key_file   .keystore file where encryption key is stored
   * @return string             Plaintext string
   */
    public static function decryptString(string $enc_string, string $key_file = __DIR__.'/../../.keystore'): string
    {
        try {
            $file = fopen($key_file, "r");
            $key = unserialize(fread($file, "10000"));
            fclose($file);
            $plaintext = \Defuse\Crypto\Crypto::decrypt($enc_string, $key);
            return $plaintext;
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return '';
        }
    }
    /**
     * Generates encryption key and securely stores in .keystore file
     * @param string $path .keystore file path
     */
    public static function generateKey(string $key_file = __DIR__.'/../../.keystore'): void
    {
        try {
            $key = \Defuse\Crypto\Key::createNewRandomKey();
            $file = fopen($key_file, 'w+');
            fwrite($file, serialize($key));
            fclose($file);
            chmod($key_file, 0600);
        } catch (\Exception $e) {
            error_log($e->getMessage());
        }
    }
}
