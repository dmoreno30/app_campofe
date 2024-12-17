<?php

namespace App\Controllers;

use Leaf\Blade;
use App\Controllers\Controller;
use App\Models\ApiCampofe;
use App\Models\APIBitrix24;
use App\helpers\Auxhelpers;

class CampoFeConsejerosController extends \Leaf\Controller
{
    private $apiCampoFe;
    private $apiBitrix24;
    private $helpers;
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
            $this->apiCampoFe = new ApiCampofe();
            $result = $this->apiCampoFe->consejeros();

            return render('Consejeros/index', ['consultar' => $consultar, 'idProspecto' => $idProspecto, 'result' => $result]);
        }
    }
    public function blindar($idProspecto, $cod_vendedor)
    {
        $this->helpers = new Auxhelpers();
        $this->apiBitrix24 = new APIBitrix24();

        $resultBitrix = $this->apiBitrix24->getLead($idProspecto);

        $dataBitrix = [
            "ac_pro_dni" => $resultBitrix["UF_CRM_1732304190"],
            "ac_pro_tipo_doc" => ($resultBitrix["UF_CRM_1732304190"] == 112) ? "DNI" : "CE",
            "ac_pro_id_lead" =>  $resultBitrix["ID"],
            "ac_pro_fecha_proceso" => $resultBitrix["DATE_CREATE"],
            "ac_pro_origen" => "P",
            "ac_pro_nombres" => $resultBitrix["NAME"],
            "ac_pro_ape_paterno" => $resultBitrix["LAST_NAME"],
            "ac_pro_ape_materno" => $resultBitrix["LAST_NAME"],
            "ac_pro_telefono" => $resultBitrix["PHONE"][0]["VALUE"],
            "ac_pro_correo" => $resultBitrix["EMAIL"][0]["VALUE"],
            "ac_pro_distrito" => $resultBitrix["UF_CRM_1732304773"] ? $resultBitrix["UF_CRM_1732304773"] : "",
            "ac_pro_deseo_recibir_info" => ($resultBitrix["UF_CRM_1732304773"] == 314) ? '1' : '0',
            "ac_pro_urgencia" => (string)($resultBitrix["UF_CRM_1732309514"] == 318) ? 'SI' : 'NO',
            "ac_pro_adultos_mayores" => (string)($resultBitrix["UF_CRM_1732309533"] == 322) ? 'SI' : 'NO',
            "ac_pro_inversion_mes" => (string)$resultBitrix["UF_CRM_1734371718"] ? $resultBitrix["UF_CRM_1734371718"] : '',
            "ac_pro_personas_proteger" => (string)$resultBitrix["UF_CRM_1734371784"] ? $resultBitrix["UF_CRM_1734371784"] : '',
            "ac_pro_seguro" => (string)($resultBitrix["UF_CRM_1734371828"] == 556) ? 'SI' : 'NO',
            "ac_pro_camposanto" => (string)($resultBitrix["UF_CRM_1732308755"] == 196) ? 'N' : (($resultBitrix["UF_CRM_1732308755"] == 198) ? 'L' : (($resultBitrix["UF_CRM_1732308755"] == 200) ? 'H' : 'Desconocido')),
            "ac_pro_cod_vendedor" => (string)$cod_vendedor,
            "ubi_depart_domic" => (string)$resultBitrix["UF_CRM_1734374506"] ? (string)$resultBitrix["UF_CRM_1734374506"] : '',
            "ubi_provin_domic" => (string)$resultBitrix["UF_CRM_1734374527"] ? (string)$resultBitrix["UF_CRM_1734374527"] : '',
            "ubi_distri_domic" => (string)$resultBitrix["UF_CRM_1734374546"] ? (string)$resultBitrix["UF_CRM_1734374546"] : '',
            "depart_domic" => (string)$resultBitrix["UF_CRM_1734375123"] ? (string)$resultBitrix["UF_CRM_1734375123"] : '',
            "provin_domic" => (string)$resultBitrix["UF_CRM_1734375147"] ? (string)$resultBitrix["UF_CRM_1734375147"] : '',
            "distri_domic" => (string)$resultBitrix["UF_CRM_1734375175"] ? (string)$resultBitrix["UF_CRM_1734375175"] : '',
            "direccion" => (string)$resultBitrix["UF_CRM_1732304925"],
            "fch_reunion" => (string)$resultBitrix["UF_CRM_1732308492"],
            "cod_tipo_reunion" => (string)($resultBitrix["UF_CRM_1732308280"] == 150) ? 'V' : 'P',
        ];
        $dataBitrix = json_encode($dataBitrix);
        $this->helpers->LogRegister($dataBitrix);
        /*
        $this->apiCampoFe = new ApiCampofe();
        $this->apiCampoFe->BlindarLead($Cedula, $TipoDocumento); */
    }
    public function desblindar($id)
    {
        $Cedula =   app()->request()->get('cedula');
        $TipoDocumento =   app()->request()->get('TipoDocumento');


        $this->apiBitrix24 = new APIBitrix24();
        $this->apiCampoFe = new ApiCampofe();
        $this->apiCampoFe->desblindarLead($Cedula, $TipoDocumento);

        return true;
    }
}
