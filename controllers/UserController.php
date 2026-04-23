<?php
require_once 'models/User.php';

class UserController
{
    private $UserModel;

    public function __construct()
    {
        $this->UserModel = new User();
    }

    public function handleRequest($method, $vars, $data)
    {
        // Handle the HTTP method
        switch ($method) {
            case 'GET':
                $this->getInfoUser($vars);
                break;
            default:
                // If the method is not a POST, GET, PUT, or DELETE, respond with 405 Method Not Allowed
                http_response_code(405);
                echo json_encode(["message" => "Method Not Allowed"]);
                break;
        }
    }

    private function getInfoUser($vars)
    {
        try {
            if (!isset($vars['id'])) {
                throw new Exception("El id no es valido", 1);
            }
            $this->UserModel->GetInfoUser($vars['id']);
        } catch (\Exception $e) {
            //throw $th;
        }
    }
}
