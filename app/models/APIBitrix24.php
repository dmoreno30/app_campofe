<?php

namespace App\Models;

use App\Core\crest;

/**
 * CampoFeAPI Model
 * ---
 * The Nova model provides a space to set atrributes
 * that are common to all models
 */
class APIBitrix24
{
    private $ConsultaRENIEC = 'https://appmovil.grupofe.com.pe/com/prospecto/digital/consulta_reniec';



    public function UpdateLead($id, $data)
    {
        $apellidos =  $data["ape_paterno"] . " " . $data["ape_materno"];
        $mensaje = ($data["mensaje"] == "El prospecto NO existe" || $data["mensaje"] == "El prospecto si existe pero esta libre") ? 122 : 120;

        $fields = [
            'id' => $id,
            'NAME' => $data["nombres"],
            'LAST_NAME' => $apellidos,
            'UF_CRM_1732304586' => ($data["sexo"] == "MAS") ? 108 : 110,
            'UF_CRM_1732304773' => $data["distri_domic"],
            'BIRTHDATE' => $data["fch_nacimiento"],
            'UF_CRM_1732304925' => $data["direccion"],
            'UF_CRM_1732304190' => $data["num_dni"],
            'UF_CRM_1732304809' => ($data["ubi_depart_domic"] == 14) ? 114 : 116,
            'UF_CRM_1732304972' =>  $mensaje,
            'UF_CRM_1732308416' => ($mensaje == 120) ? $mensaje : "",
            'UF_CRM_1734374506' => $data["ubi_depart_domic"],
            'UF_CRM_1734374527' => $data["ubi_provin_domic"],
            'UF_CRM_1734374546' => $data["ubi_distri_domic"],
            'UF_CRM_1734375123' => $data["depart_domic"],
            'UF_CRM_1734375147' => $data["provin_domic"],
            'UF_CRM_1734375175' => $data["distri_domic"],
        ];

        $result = crest::call('crm.lead.update', [
            'id' => $id,
            'fields' => $fields
        ]);

        if ($mensaje == 120) {
            $this->BP_lead($id);
            $this->MessaggeCRM($id, "El Lead se encuentra Blindado por 30 días");
        }

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
    private function BP_lead($idProspecto)
    {
        $params = [
            "TEMPLATE_ID" => 64,
            "DOCUMENT_ID" => ['crm', 'CCrmDocumentLead', $idProspecto],
        ];
        crest::call("bizproc.workflow.start", [
            $params
        ]);
    }
    private function MessaggeCRM($id, $mensaje)
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
        print_r($result);
        return $result;
    }
}
