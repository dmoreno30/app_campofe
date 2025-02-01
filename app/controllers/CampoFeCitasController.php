<?php

namespace App\Controllers;

use Leaf\Blade;
use App\Controllers\Controller;
use App\Models\ApiCampofe;
use App\Models\APIBitrix24;
use App\helpers\Auxhelpers;

class CampoFeCitasController extends \Leaf\Controller
{
    private $apiCampoFe;
    private $apiBitrix24;
    private $helpers;
    public function index($idProspecto)
    {
        $this->helpers = new Auxhelpers();

        $dataBitrix =  $this->estructurarData($idProspecto);
        //$t =  $this->apiCampoFe->agendarCita($dataBitrix);
        $this->helpers->LogRegister($idProspecto);
    }

    private function estructurarData($idProspecto)
    {
        $this->helpers = new Auxhelpers();
        $this->apiBitrix24 = new APIBitrix24();

        $resultBitrix = $this->apiBitrix24->getLead($idProspecto);

        $dataBitrix = [
            "cod_documento_identidad" => (string)($resultBitrix["UF_CRM_1732304190"] == 102) ? "DNI" : (($resultBitrix["UF_CRM_1732304190"] == 104) ? "CE" : (($resultBitrix["UF_CRM_1732304190"] == 112) ? 'PASS' :  'DNI')),
            "dsc_documento_identidad" => (string)$resultBitrix["UF_CRM_1732304190"],
            "ac_pro_id_lead" =>  (string)$resultBitrix["ID"],
            "cod_vendedor" => (string)$resultBitrix["UF_CRM_1732308439"],
            "cod_etapa" => "2",
            "id_item_etapa" => 0,
            "fch_reunion" => $resultBitrix["UF_CRM_1732308492"],
            "tipo_reunion" => (string)($resultBitrix["UF_CRM_1732308280"] == 150) ? 'V' : 'P',
            "cod_estado" => "SEG",
            "dsc_comentario" => (string)$resultBitrix["COMMENTS"],
            "dsc_detalle" => (string)$resultBitrix["COMMENTS"],
            "dsc_noleinteresa" => null,
        ];
        $dataBitrixFormated = json_encode($dataBitrix);
        $this->helpers->LogRegister($dataBitrixFormated);
        return $dataBitrix;
    }

    /* 
    cod_documento_identidad String SI Tipo de Documento:
DNI 
PSP
CE
dsc_documento_identidad String SI Número de documento
ac_pro_id_lead String SI Número identificador del 
lead
cod_vendedor String SI Código del consejero
cod_etapa String SI Enviar “2”: Etapa Cita
id_item_etapa INT SI Enviar 0: Nueva cita
fch_reunion DateTime SI Fecha y hora de la reunión
tipo_reunion String SI Enviar P: Presencial 
V: Virtual
cod_estado String SI Enviar "SEG"
dsc_comentario String NO Enviar cadena vacía “”
dsc_detalle String NO Enviar cadena vacía “”
dsc_noleinteresa String NO Enviar null */
}
