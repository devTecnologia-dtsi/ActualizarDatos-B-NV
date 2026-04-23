<?php
    require_once 'utils/JWTUtils.php';
    // Middleware to authenticate the user
    class AuthMiddleware {
        // Check if the user is authenticated
        public static function authenticate() {
            $headers = getallheaders();
            if (!isset($headers['Authorization'])) {
                http_response_code(401);
                echo json_encode(["message" => "Unauthorized access. Token missing.."]);
                exit;
            }

            $authHeader = $headers['Authorization'];
            $jwt = str_replace('Bearer ', '', $authHeader);

            $decoded = JWTUtils::validateJWT($jwt);
            if (!$decoded) {
                http_response_code(401);
                echo json_encode(["message" => "Unauthorized access. Invalid token."]);
                exit;
            }
        }
    }
?>