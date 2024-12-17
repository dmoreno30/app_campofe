<?php

namespace App\Models;

/**
 * CampoFeAPI Model
 * ---
 * The Nova model provides a space to set atrributes
 * that are common to all models
 */
class ApiCampofe
{
    private $ConsultaRENIEC = 'https://appmovil.grupofe.com.pe/com/prospecto/digital/consulta_reniec';
    private $URL_desblindar = 'https://appmovil.grupofe.com.pe/com/prospecto/digital/desblindar';
    private $URL_Consejeros = 'https://appmovil.grupofe.com.pe/com/prospecto/digital/vendedor_lead';
    private $URL_asignarConsejero = 'https://appmovil.grupofe.com.pe/com/prospecto/digital/cargardistribucionconsejero';
    private $cod_trabajador = "MKT";
    private $flg_cliente = "SI";


    private function CurlPost($arr, $apiUrl,  $method)
    {

        $ch = curl_init();

        // Configura las opciones de cURL
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if ($method === "POST") {
            curl_setopt($ch, CURLOPT_POST, true);
        } else {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($arr));

        // Ejecuta la solicitud
        $response = curl_exec($ch);

        // Maneja errores de cURL
        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
            curl_close($ch);
            return ['error' => true, 'message' => $error_msg];
        }

        // Cierra la conexión cURL
        curl_close($ch);

        // Decodifica la respuesta
        $responseData = json_decode($response, true);
        return $responseData;
    }
    private function CurlGet($apiUrl, $token)
    {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token, // Aquí pasas el token en el encabezado
            'Content-Type: application/json'
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
            curl_close($ch);
            return ['error' => true, 'message' => $error_msg];
        }

        curl_close($ch);

        $responseData = json_decode($response, associative: true);

        return $responseData;
    }

    public function findUser($Cedula, $TipoDocumento)
    {

        $data = [
            "cod_trabajador" => $this->cod_trabajador,
            "cod_documento_identidad" =>  $TipoDocumento,
            "dsc_documento_identidad" => $Cedula,
            "flg_cliente" => $this->flg_cliente,
        ];

        $result = $this->CurlPost($data,  $this->ConsultaRENIEC, "POST");

        return $result;
    }
    public function desblindarLead($TipoDocumento, $Cedula)
    {
        $data = [
            "cod_documento_identidad" =>  $TipoDocumento,
            "dsc_documento_identidad" => $Cedula,
        ];
        $result = $this->CurlPost($data,  $this->URL_desblindar, "POST");

        return $result;
    }
    public function BlindarLead($TipoDocumento, $Cedula)
    {
        $data = [
            "cod_documento_identidad" =>  $TipoDocumento,
            "dsc_documento_identidad" => $Cedula,
        ];
        $result = $this->CurlPost($data,  $this->URL_asignarConsejero, "POST");

        return $result;
    }
    public function consejeros()
    {
        $data = [
            "flg_estado" => "SI"
        ];
        $result = $this->CurlPost($data,  $this->URL_Consejeros, "POST");

        return $result;
    }
}
