<?php
class CurlController
{
    /**
     * Ejecuta una petición cURL con reintentos automáticos.
     *
     * @param string $url          URL destino
     * @param string $method       Método HTTP: GET, POST, PUT, DELETE
     * @param array  $body         Datos a enviar (se serializa a JSON)
     * @param array  $headers      Headers HTTP adicionales
     * @param int    $maxIntentos  Máximo de intentos (default: 3)
     * @param int    $delay        Segundos de espera entre reintentos (default: 1)
     * @return array               ['success', 'status', 'body', 'error']
     */
    function curlRequest(
        string $url,
        string $method = 'GET',
        array $body = [],
        array $headers = []
    ): array {

        // Headers por defecto
        $headersBase = array_merge([
            'Content-Type: application/json',
            'Accept: application/json',
        ], $headers);

        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 15,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_HTTPHEADER     => $headersBase,
            CURLOPT_SSL_VERIFYPEER => true,
        ]);

        // Configurar método y body
        switch (strtoupper($method)) {
            case 'POST':
                curl_setopt($ch, CURLOPT_POST, true);
                if (!empty($body)) {
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
                }
                break;

            case 'PUT':
            case 'PATCH':
            case 'DELETE':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
                if (!empty($body)) {
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
                }
                break;

            case 'GET':
            default:
                if (!empty($body)) {
                    $url .= '?' . http_build_query($body);
                    curl_setopt($ch, CURLOPT_URL, $url);
                }
                break;
        }

        $response   = curl_exec($ch);
        $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError  = curl_error($ch);
        unset($ch);

        $esExitosa = !$curlError && $httpStatus >= 200 && $httpStatus < 300;

        if ($esExitosa) {
            return [
                'success'  => true,
                'status'   => $httpStatus,
                'body'     => json_decode($response, true) ?? $response,
                'error'    => null,
            ];
        }

        // Error
        return [
            'success'  => false,
            'status'   => $httpStatus ?? 0,
            'body'     => json_decode($response, true)
        ];
    }
}
