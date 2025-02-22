<?php

namespace App\Controllers;

use Leaf\Blade;
use App\Controllers\Controller;
use App\Models\ApiCampofe;
use App\Models\APIBitrix24;
use App\helpers\Auxhelpers;
use Stringable;

class CampoFeConsejerosController extends \Leaf\Controller
{
    private $apiCampoFe;
    private $apiBitrix24;
    private $helpers;

    public function __construct()
    {
        $this->helpers = new Auxhelpers();
        $this->apiBitrix24 = new APIBitrix24();
        $this->apiCampoFe = new ApiCampofe();
    }
    public function index()
    {

        $placement_options = json_decode($_REQUEST['PLACEMENT_OPTIONS'], true);
        $idProspecto = $placement_options["ID"];
        return render('Consejeros/index', ['idProspecto' => $idProspecto]);
    }

    public function list()
    {

        $consultar =   app()->request()->get('consultar');
        $idProspecto =   app()->request()->get('idProspecto');
        if ($consultar == "consultar") {

            $result = $this->apiCampoFe->consejeros();
            $resultBitrix24 = $this->apiBitrix24->getLead($idProspecto);
            $blindado = $resultBitrix24["UF_CRM_1732304972"] == 120 ? "SI" : "NO";

            return render('Consejeros/index', ['consultar' => $consultar, 'idProspecto' => $idProspecto, 'result' => $result, 'blindado' => $blindado]);
        }
    }
    public function blindar($idProspecto, $cod_vendedor, $dsc_vendedor)
    {


        $this->apiBitrix24->MessaggeCRM($idProspecto, "Información enviada, espere...");

        $resultBitrix = $this->apiBitrix24->getLead($idProspecto);

        try {
            $fechadeReunion = $this->helpers->FormatDateAC(nuevafecha: $resultBitrix["UF_CRM_1732308492"]);
            $apellidos = explode(" ", $resultBitrix["LAST_NAME"]);
            $dataBitrix = [
                "ac_pro_dni" => $resultBitrix["UF_CRM_1732304190"],
                "ac_pro_tipo_doc" => (string)($resultBitrix["UF_CRM_1732304190"] == 102) ? "DNI" : (($resultBitrix["UF_CRM_1732304190"] == 104) ? "CE" : (($resultBitrix["UF_CRM_1732304190"] == 112) ? 'PASS' :  'DNI')),
                "ac_pro_id_lead" =>  $resultBitrix["ID"],
                "ac_pro_fecha_proceso" => $resultBitrix["DATE_CREATE"],
                "ac_pro_origen" => "W",
                "ac_pro_nombres" => $resultBitrix["NAME"] ? $resultBitrix["NAME"] : "",
                "ac_pro_ape_paterno" => $apellidos[0] ? $apellidos[0] : "",
                "ac_pro_ape_materno" => $apellidos[1] ? $apellidos[1] : "",
                "ac_pro_telefono" => $resultBitrix["PHONE"][0]["VALUE"],
                "ac_pro_correo" => $resultBitrix["EMAIL"][0]["VALUE"],
                "ac_pro_distrito" => $resultBitrix["UF_CRM_1732304773"] ? $resultBitrix["UF_CRM_1732304773"] : "",
                "ac_pro_deseo_recibir_info" => ($resultBitrix["UF_CRM_1732304773"] == 314) ? '1' : '0',
                "ac_pro_urgencia" => (string)($resultBitrix["UF_CRM_1732309514"] == 318) ? 'SI' : 'NO',
                "ac_pro_adultos_mayores" => (string)($resultBitrix["UF_CRM_1732309533"] == 322) ? 'SI' : 'NO',
                "ac_pro_inversion_mes" => (string)$resultBitrix["UF_CRM_1734371718"] ? $resultBitrix["UF_CRM_1734371718"] : '',
                "ac_pro_personas_proteger" => (string)$resultBitrix["UF_CRM_1734371784"] ? $resultBitrix["UF_CRM_1734371784"] : '',
                "ac_pro_seguro" => (string)($resultBitrix["UF_CRM_1734371828"] == 556) ? 'SI' : 'NO',
                "ac_pro_camposanto" => (string)($resultBitrix["UF_CRM_1732308755"] == 196) ? 'N' : (($resultBitrix["UF_CRM_1732308755"] == 198) ? 'L' : (($resultBitrix["UF_CRM_1732308755"] == 200) ? 'H' : 'N')),
                "ac_pro_cod_vendedor" => (string)$cod_vendedor,
                "ubi_depart_domic" => (string)$resultBitrix["UF_CRM_1734374506"] ? (string)$resultBitrix["UF_CRM_1734374506"] : '',
                "ubi_provin_domic" => (string)$resultBitrix["UF_CRM_1734374527"] ? (string)$resultBitrix["UF_CRM_1734374527"] : '',
                "ubi_distri_domic" => (string)$resultBitrix["UF_CRM_1734374546"] ? (string)$resultBitrix["UF_CRM_1734374546"] : '',
                "depart_domic" => (string)$resultBitrix["UF_CRM_1734375123"] ? (string)$resultBitrix["UF_CRM_1734375123"] : '',
                "provin_domic" => (string)$resultBitrix["UF_CRM_1734375147"] ? (string)$resultBitrix["UF_CRM_1734375147"] : '',
                "distri_domic" => (string)$resultBitrix["UF_CRM_1734375175"] ? (string)$resultBitrix["UF_CRM_1734375175"] : '',
                "direccion" => (string)$resultBitrix["UF_CRM_1732304925"] ? (string)$resultBitrix["UF_CRM_1732304925"] : "",
                "fch_reunion" => $fechadeReunion,
                "cod_tipo_reunion" => (string)($resultBitrix["UF_CRM_1732308280"] == 150) ? 'V' : 'P',
            ];
            $result = $this->apiCampoFe->BlindarLead($dataBitrix);
            $this->helpers->LogRegister($dataBitrix);
            if ($result["error"] === null) {
                switch ($result["data"]["codigo"]) {
                    case 1:
                        $this->apiBitrix24->BP_lead($idProspecto);

                        $this->apiBitrix24->MessaggeCRM($idProspecto, "El lead fue asignado con exito");
                        $datalead = [
                            "UF_CRM_1732308416" => $dsc_vendedor,
                            "UF_CRM_1732308439" => $cod_vendedor
                        ];
                        $this->apiBitrix24->UpdateLead_general($idProspecto, $datalead);
                        break;
                    case 0:
                        $this->apiBitrix24->BP_lead($idProspecto);

                        $this->apiBitrix24->MessaggeCRM($idProspecto, "El lead fue asignado con exito");
                        $datalead = [
                            "UF_CRM_1732308416" => $dsc_vendedor,
                            "UF_CRM_1732308439" => $cod_vendedor
                        ];
                        $this->apiBitrix24->UpdateLead_general($idProspecto, $datalead);
                        break;
                    case -1:
                        $this->apiBitrix24->MessaggeCRM($idProspecto, $result["data"]["mensaje"]);
                        break;
                }
            } else {
                $this->apiBitrix24->MessaggeCRM($idProspecto, $result["error"]["mensaje"]);
            }
            return render('Consejeros/update', ['mensaje' => $result["data"]["mensaje"]]);
        } catch (\Throwable $e) {

            $this->apiBitrix24->MessaggeCRM($idProspecto, "Hubo un error por favor verifica los valores de los campos");
            echo "Error: verifica los campos enviados por favor";
            // O registrar el error en un archivo
            $this->helpers->LogRegister($e->getMessage());
        }
    }
    public function desblindar($id, $tipoDocumento, $documento)
    {
        $this->helpers = new Auxhelpers();
        $this->apiBitrix24 = new APIBitrix24();
        $this->apiCampoFe = new ApiCampofe();

        $resultBitrix = $this->apiBitrix24->getLead($id);
        $this->apiBitrix24->MessaggeCRM($id, "Información enviada, espere...");

        $data = [
            "ac_pro_tipo_doc" =>  $tipoDocumento,
            "ac_pro_dni" => $documento,
        ];
        $result = $this->apiCampoFe->desblindarLead($data);
        $this->helpers->LogRegister($result);
        if ($result["error"] === null) {

            if ($result["data"]["codigo"] == 0) {
                $r = $this->apiBitrix24->BP_lead_desblindar($id, idProceso: 82);
                $this->apiBitrix24->MessaggeCRM($id, $result["data"]["mensaje"]);
                $tr = $this->apiBitrix24->BP_terminate($resultBitrix["UF_CRM_1737599200"]);
                $this->helpers->LogRegister($tr);
            } else {
                $this->apiBitrix24->MessaggeCRM($id, mensaje: $result["data"]["mensaje"]);
            }
        } else {
            $this->apiBitrix24->MessaggeCRM($id, mensaje: $result["error"]["mensaje"]);
        }

        return true;
    }
}
