<?php

namespace App\Controllers;

use Leaf\Blade;
use App\Controllers\Controller;
use App\Models\APIBitrix24;
use App\helpers\Auxhelpers;

class Bitrix24Controller extends \Leaf\Controller
{
    private $Bitrix24;
    private $helpers;
    public function __construct()
    {
        $this->Bitrix24 = new APIBitrix24();
        $this->helpers =  new Auxhelpers();
    }
    public function update($idProspecto)
    {
        try {

            $resultDecoded = json_decode($_REQUEST["result"], true);

            $edad = $this->helpers->calcularEdad($resultDecoded["data"]["reniec"]["fch_nacimiento"]);
            $data = [
                "mensaje" => $resultDecoded["data"]["mensaje"],
                "num_dni" => $resultDecoded["data"]["reniec"]["num_dni"],
                "nombres" =>  $resultDecoded["data"]["reniec"]["nombres"],
                "ape_paterno" => $resultDecoded["data"]["reniec"]["ape_paterno"],
                "ape_materno" => $resultDecoded["data"]["reniec"]["ape_materno"],
                "sexo" => $resultDecoded["data"]["reniec"]["sexo"],
                "edad" => $edad,
                "distri_domic" => $resultDecoded["data"]["reniec"]["distri_domic"],
                "fch_nacimiento" => $resultDecoded["data"]["reniec"]["fch_nacimiento"],
                "direccion" => $resultDecoded["data"]["reniec"]["direccion"],
                "ubi_depart_domic" => $resultDecoded["data"]["reniec"]["ubi_depart_domic"],
                "ubi_provin_domic" => $resultDecoded["data"]["reniec"]["ubi_provin_domic"],
                "ubi_distri_domic" => $resultDecoded["data"]["reniec"]["ubi_distri_domic"],
                "depart_domic" => $resultDecoded["data"]["reniec"]["depart_domic"],
                "provin_domic" => $resultDecoded["data"]["reniec"]["provin_domic"],
                "codigo" => $resultDecoded["data"]["codigo"]
            ];

            $this->helpers->LogRegister($data);
            $this->Bitrix24->UpdateLead($idProspecto, $data);
            return render('Principal/update', ['idProspecto' => $idProspecto]);
        } catch (\Throwable $e) {
            $this->Bitrix24->MessaggeCRM($idProspecto, "Hubo un error por favor verifica los valores de los campos");
            echo "Error: Hubo un error por favor verifica los valores de los campos";
            // O registrar el error en un archivo
            $this->helpers->LogRegister($e->getMessage());
        }
    }
    public function update_lead($idProspecto, $data)
    {
        $this->Bitrix24->UpdateLead($idProspecto, $data);
        return render('Principal/update', ['idProspecto' => $idProspecto]);
    }
}
