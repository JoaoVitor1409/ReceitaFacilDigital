<?php

namespace App\Controllers;

use MF\Controller\Action;
use MF\Model\Container;
use PDO;

class TemplateController extends Action
{

    private  $templates = [
        [
            "templateId" => 1,
            "templateName" => "Febre",
            "templateMedicines" => [
                [
                    "medicineName" => "Dipirona 250mg",
                    "medicineSize" => "1cp",
                    "medicineFrequency" => "8 horas",
                    "medicineObs" => "Apenas se tiver dor"
                ],
                [
                    "medicineName" => "Engov 500ml",
                    "medicineSize" => "10ml",
                    "medicineFrequency" => "12 horas",
                    "medicineObs" => "Gostei do nome, por isso passei :D"
                ]
            ]
        ],
        [
            "templateId" => 2,
            "templateName" => "Dor de cabeça",
            "templateMedicines" => [
                [
                    "medicineName" => "Dipirona Monoidratada 250mg",
                    "medicineSize" => "1cp",
                    "medicineFrequency" => "10 horas",
                    "medicineObs" => "Apenas se tiver dor"
                ]
            ]
        ],
        [
            "templateId" => 3,
            "templateName" => "Dor de garganta",
            "templateMedicines" => [
                [
                    "medicineName" => "Xarope Ruim 500ml",
                    "medicineSize" => "10ml",
                    "medicineFrequency" => "8 horas",
                    "medicineObs" => "Para aprender a usar blusa"
                ],
                [
                    "medicineName" => "Dipirona 250mg",
                    "medicineSize" => "1cp",
                    "medicineFrequency" => "8 horas",
                    "medicineObs" => "Apenas se tiver dor"
                ],
                [
                    "medicineName" => "Anti-inflamatório 250mg",
                    "medicineSize" => "1cp",
                    "medicineFrequency" => "6 horas",
                    "medicineObs" => "Saí desse corpo"
                ],
            ]
        ],
        [
            "templateId" => 4,
            "templateName" => "Tosse",
            "templateMedicines" => [
                [
                    "medicineName" => "Xarope Ruim 500ml",
                    "medicineSize" => "10ml",
                    "medicineFrequency" => "8 horas",
                    "medicineObs" => "Para aprender a usar blusa"
                ]
            ]
        ],
        [
            "templateId" => 5,
            "templateName" => "Coração",
            "templateMedicines" => [
                [
                    "medicineName" => "Remédio cardíaco",
                    "medicineSize" => "1cp",
                    "medicineFrequency" => "1 hora",
                    "medicineObs" => "TOMAR SE NÃO MORRE"
                ]
            ]
        ],
    ];

    public function saveTemplate()
    {
        echo json_encode($_POST);
    }

    public function templatesList()
    {

        $templateName = null;
        if (isset($_POST["templateName"])) {
            $templateName = $_POST["templateName"];
        }
        $data = [];



        if (!$templateName) {
            $data = $this->templates;
        } else {
            foreach ($this->templates as $template) {
                if ($templateName == $template["templateName"]) {
                    $data[] = $template;
                }
            }
        }
        if ($data) {
            echo json_encode($data);
            return;
        }

        echo json_encode(["notFound" => 1]);
    }

    public function getTemplate()
    {
        $templateId = $_POST["templateId"];
        $template = [];

        foreach ($this->templates as $t) {
            if ($t["templateId"] == $templateId) {
                $template = $t;
                break;
            }
        }

        echo json_encode($template);
    }

    public function deleteTemplate()
    {
        $templateId = $_POST["templateId"];

        echo("Template excluído");
    }
}
