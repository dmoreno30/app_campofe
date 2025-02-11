<?php

namespace App\Models;

use App\Core\crest;
use App\helpers\Auxhelpers;

/**
 * CampoFeAPI Model
 * ---
 * The Nova model provides a space to set atrributes
 * that are common to all models
 */
class APIBitrix24
{
    private $ConsultaRENIEC = 'https://appmovil.grupofe.com.pe/com/prospecto/digital/consulta_reniec';
    private $helpers;


    public function UpdateLead($id, $data)
    {
        $apellidos =  $data["ape_paterno"] . " " . $data["ape_materno"];
        $this->helpers = new Auxhelpers();
        $fields = [
            'id' => $id,
            'NAME' => $data["nombres"],
            'LAST_NAME' => $apellidos,
            'UF_CRM_1732304586' => ($data["sexo"] == "MAS") ? 108 : 110,
            'UF_CRM_1732304773' => $data["distri_domic"],
            'BIRTHDATE' => $data["fch_nacimiento"],
            'UF_CRM_1732304925' => $data["direccion"],
            'UF_CRM_1732304390' => $data["edad"],
            'UF_CRM_1732304190' => $data["num_dni"],
            'UF_CRM_1732304809' => ($data["ubi_depart_domic"] == 14) ? 114 : 116,
            'UF_CRM_1734374506' => $data["ubi_depart_domic"],
            'UF_CRM_1734374527' => $data["ubi_provin_domic"],
            'UF_CRM_1734374546' => $data["ubi_distri_domic"],
            'UF_CRM_1734375123' => $data["depart_domic"],
            'UF_CRM_1734375147' => $data["provin_domic"],
            'UF_CRM_1734375175' => $data["distri_domic"],
        ];
        $this->helpers->LogRegister($fields);
        $result = crest::call('crm.lead.update', [
            'id' => $id,
            'fields' => $fields
        ]);

        $this->MessaggeCRM($id, "Lead actualizado con los datos de la RENIEC ");


        return $result;
    }
    public function UpdateLead_general($id, $data)
    {
        $result = crest::call('crm.lead.update', [
            'id' => $id,
            'fields' => $data
        ]);
        return $result;
    }
    public function getLead($id)
    {
        $result = crest::call('crm.lead.get', [
            'id' => $id,
        ]);

        return $result["result"];
    }
    public function Consejero_Lead($id)
    {
        $result = crest::call('crm.lead.update', [
            'id' => $id,
        ]);

        return $result["result"];
    }
    public function BP_lead($idProspecto)
    {
        $result = CRest::call(
            'bizproc.workflow.start',
            [
                'TEMPLATE_ID' => 64,
                'DOCUMENT_ID' => [
                    'crm',
                    'CCrmDocumentLead',
                    $idProspecto
                ]
            ]
        );
        return $result;
    }
    public function BP_lead_desblindar($idProspecto, $idProceso)
    {
        $result = CRest::call(
            'bizproc.workflow.start',
            [
                'TEMPLATE_ID' => $idProceso,
                'DOCUMENT_ID' => [
                    'crm',
                    'CCrmDocumentLead',
                    $idProspecto
                ]
            ]
        );
        return $result;
    }
    public function BP_terminate($codigoBP)
    {
        $result = CRest::call(
            'bizproc.workflow.terminate',
            [
                'ID' => $codigoBP,
                'STATUS' => 'Lead no asignado'

            ]
        );
        return $result;
    }
    public function MessaggeCRM($id, $mensaje)
    {

        $result = crest::call(
            'crm.timeline.comment.add',
            [
                'fields' => [
                    'ENTITY_ID' => $id,
                    'ENTITY_TYPE' => 'lead',
                    'COMMENT' => $mensaje,
                    'AUTHOR_ID' => 5
                ]
            ]
        );

        return $result;
    }
}
