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
        $result = $this->apiCampoFe->findUser($Cedula, $TipoDocumento);

        return render('Principal/index', ['result' => $result, 'idProspecto' => $idProspecto]);
    }
}
