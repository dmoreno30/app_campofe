<?php

namespace App\Controllers;

use Leaf\Blade;
use App\Controllers\Controller;
use App\Models\ApiCampofe;
use App\Models\APIBitrix24;

class CampoFeController extends \Leaf\Controller
{
    private $apiCampoFe;
    private $apiBitrix24;
    public function index()
    {

        $placement_options = json_decode($_REQUEST['PLACEMENT_OPTIONS'], true);
        $idProspecto = $placement_options["ID"];

        return render('Principal/index', ['idProspecto' => $idProspecto]);
    }
    public function add()
    {
        $Cedula =   app()->request()->get('cedula');
        $TipoDocumento =   app()->request()->get('TipoDocumento');
        $idProspecto =   app()->request()->get('idProspecto');

        $this->apiCampoFe = new ApiCampofe();
        $this->apiBitrix24 = new APIBitrix24();

        $result = $this->apiCampoFe->findUser($Cedula, $TipoDocumento);

        if ($result["error"] === null) {
            switch ($result["data"]["codigo"]) {
                case 0:
                    $this->apiBitrix24->MessaggeCRM($idProspecto, $result["data"]["mensaje"]);
                    break;
                case -3:
                    $this->apiBitrix24->BP_lead($idProspecto);
                    $this->apiBitrix24->MessaggeCRM($idProspecto, $result["data"]["mensaje"]);
                    break;
            }
        }
        return render('Principal/index', ['result' => $result, 'idProspecto' => $idProspecto]);
    }
}
