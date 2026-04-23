<?php
require_once "controllers/CurlController.php";
require_once "utils/CryptoHelper.php";
class User
{
    private $ControllerCurl;
    private $CryptoHelper;
    private $UrlDigibee;

    public function __construct()
    {
        $this->ControllerCurl = new CurlController();
        $this->CryptoHelper = new CrypoHelper();
        $this->UrlDigibee = "https://uniminuto.test.digibee.io/pipeline/uniminuto/v1/actualizacion-datos/usuarios/";
    }

    /**
     * Funcion que se usa para obtener la información del estudiantes desde DIGIBEE
     * @param string $id       Id del estudiante
     */
    public function GetInfoUser(string $id)
    {
        try {
            $curlDigibee = $this->ControllerCurl->curlRequest(
                $this->UrlDigibee . $id,
                'GET',
                [],
                ['apikey: 5H9CcvkLZJTgPDDCXTXTI7KC90k6prl0'],
            );
            http_response_code(200);
            echo json_encode(["status" => 200, "data" => $this->CryptoHelper->encrypt($curlDigibee['body'])]);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(["message" => "Error creating item: " . $e->getMessage()]);
        }
    }
}
