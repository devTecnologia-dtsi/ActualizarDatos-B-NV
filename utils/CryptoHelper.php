<?php
//$_ENV['ENCRYPTION_KEY']/
define('ENCRYPTION_KEY', "Uni4#sof3!Qw"); // misma clave que Angular
class CrypoHelper
{

    public function decrypt(string $ciphertext): array
    {
        $key = ENCRYPTION_KEY;

        // crypto-js usa un formato compatible con OpenSSL
        $ciphertext = base64_decode($ciphertext);

        // Extraer el salt (bytes 8-15)
        $salt = substr($ciphertext, 8, 8);
        $ct   = substr($ciphertext, 16);

        // Derivar key + IV igual que crypto-js
        $keyAndIv = $this->evpkdf($key, $salt);
        $aesKey   = substr($keyAndIv, 0, 32);
        $iv       = substr($keyAndIv, 32, 16);

        $decrypted = openssl_decrypt($ct, 'aes-256-cbc', $aesKey, OPENSSL_RAW_DATA, $iv);
        return json_decode($decrypted, true);
    }

    function encrypt(array $data): string
    {
        $key  = ENCRYPTION_KEY;
        $salt = random_bytes(8);

        $keyAndIv = $this->evpkdf($key, $salt);
        $aesKey   = substr($keyAndIv, 0, 32);
        $iv       = substr($keyAndIv, 32, 16);

        $encrypted = openssl_encrypt(
            json_encode($data),
            'aes-256-cbc',
            $aesKey,
            OPENSSL_RAW_DATA,
            $iv
        );

        return base64_encode('Salted__' . $salt . $encrypted);
    }

    // Derivación de clave compatible con crypto-js (EVP_BytesToKey)
    private function evpkdf(string $password, string $salt): string
    {
        $derived = '';
        $block   = '';
        while (strlen($derived) < 48) {
            $block   = md5($block . $password . $salt, true);
            $derived .= $block;
        }
        return $derived;
    }
}
