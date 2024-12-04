<?php

namespace App\Controllers;

use Leaf\Blade;
use App\Controllers\Controller;
use App\Models\ApiCampofe;

class CampoFeAPIController extends \Leaf\Controller
{

    public function add()
    {
        $cedula =   app()->request()->get('cedula');

        print_r($cedula);
        return render('Principal/index', ['cedula' => $cedula]);
    }
}
