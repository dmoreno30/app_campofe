<?php

namespace App\Controllers;

use Leaf\Blade;
use App\Controllers\Controller;

class CampoFeController extends \Leaf\Controller
{

    public function index()
    {
        //$placement_options = json_decode($_REQUEST['PLACEMENT_OPTIONS'], true);
        $placement_options = json_decode($_REQUEST['PLACEMENT_OPTIONS'], true);
        $idProspecto = $placement_options["ID"];
        //return view('gestionReniec/index', ["placement_options" => $placement_options]);
        return render('Principal/index', ['idProspecto' => $idProspecto]);
    }
}
