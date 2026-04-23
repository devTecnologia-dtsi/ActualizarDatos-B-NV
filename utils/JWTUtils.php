<?php
    require 'vendor/autoload.php';
    use Firebase\JWT\JWT;
    use Firebase\JWT\Key;

    // JWTUtils class
    class JWTUtils {
        // Key for JWT
        private static $key = "password in md5";

        // Generate JWT
        public static function generateJWT($payload) {
            $jwt = JWT::encode($payload, self::$key, 'HS256');
            return $jwt;
        }

        // Validate JWT
        public static function validateJWT($jwt) {
            try {
                $decoded = JWT::decode($jwt, new Key(self::$key, 'HS256'));
                return $decoded;
            } catch (\Exception $e) {
                return null;
            }
        }
    }
?>