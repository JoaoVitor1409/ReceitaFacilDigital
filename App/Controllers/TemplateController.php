<?php

namespace App\Controllers;

use Collator;
use MF\Controller\Action;
use MF\Model\Container;
use PDO;

class TemplateController extends Action
{
    public function saveTemplate()
    {
        $templateId = null;
        if (isset($_POST["templateId"])) {
            $templateId = $_POST["templateId"];
        }
        $templateName = $_POST["templateName"];
        $medicineName = $_POST["medicineName"];
        $medicineSize = $_POST["medicineSize"];
        $medicineFrequency = $_POST["medicineFrequency"];
        $medicineObs = $_POST["medicineObs"];

        session_start();

        $template = Container::getModel("Template");
        if (!$templateId) {
            $template->__set("name", $templateName);
            $template->__set("doctorId", $_SESSION["rfd"]["user"]["id"]);
            $templateName = $template->getTemplateByName();
            if ($templateName) {
                $result = ["code" => 0, "message" => "Já existe um  modelo com esse nome", "input" => "templateNameModal"];

                echo json_encode($result);
                return;
            }

            $template->save();
            $templateId = $template->getLastTemplate()[0]["ModeloID"];

            for ($i = 0; $i < count($medicineName); $i++) {
                $medicine[$i] = Container::getModel("Medicine");
                $medicine[$i]->__set("desc", $medicineName[$i]);
                $medicine[$i]->__set("size", $medicineSize[$i]);
                $medicine[$i]->__set("frequency", $medicineFrequency[$i]);

                if (isset($medicineObs[$i])) {
                    $medicine[$i]->__set("obs", $medicineObs[$i]);
                }

                $medicine[$i]->__set("templateId", $templateId);
                $medicine[$i]->save();
            }
        } else {
            $template->__set("id", $templateId);
            $template->__set("name", $templateName);
            $template->__set("doctorId", $_SESSION["rfd"]["user"]["id"]);
            $templateName = $template->getTemplateByName();

            if (count($templateName) > 1) {
                $result = ["code" => 0, "message" => "Já existe um  modelo com esse nome", "input" => "templateNameModal"];

                echo json_encode($result);
                return;
            }

            $template->update();

            for ($i = 0; $i < count($medicineName); $i++) {
                $medicine[$i] = Container::getModel("Medicine");
                $medicine[$i]->__set("desc", $medicineName[$i]);
                $medicine[$i]->__set("size", $medicineSize[$i]);
                $medicine[$i]->__set("frequency", $medicineFrequency[$i]);

                if (isset($medicineObs[$i])) {
                    $medicine[$i]->__set("obs", $medicineObs[$i]);
                }

                $medicine[$i]->__set("templateId", $templateId);

                $medicines = $medicine[$i]->getMedicineByTemplate();
                if (isset($medicines[$i])) {
                    $medicine[$i]->__set("id", $medicines[$i]["MedicamentoID"]);
                    $medicine[$i]->update();
                }
            }

            $medicines = Container::getModel("Medicine");
            $medicines->__set("templateId", $templateId);
            $medicines = $medicines->getMedicineByTemplate();

            if (count($medicineName) < count($medicines)) {
                for ($i = count($medicines) - 1; $i > count($medicineName) - 1; $i--) {
                    $medicine[$i] = Container::getModel("Medicine");
                    $medicine[$i]->__set("templateId", $templateId);
                    $medicine[$i]->__set("id", $medicines[$i]["MedicamentoID"]);

                    $medicine[$i]->delete();
                }
            } else if (count($medicineName) > count($medicines)) {
                for ($i = count($medicines); $i < count($medicineName); $i++) {
                    $medicine[$i] = Container::getModel("Medicine");
                    $medicine[$i]->__set("desc", $medicineName[$i]);
                    $medicine[$i]->__set("size", $medicineSize[$i]);
                    $medicine[$i]->__set("frequency", $medicineFrequency[$i]);

                    if (isset($medicineObs[$i])) {
                        $medicine[$i]->__set("obs", $medicineObs[$i]);
                    }

                    $medicine[$i]->__set("templateId", $templateId);

                    $medicine[$i]->save();
                }
            }
        }

        $result = ["code" => 1, "message" => "Template salvo com sucesso"];

        echo json_encode($result);
    }

    public function templatesList()
    {
        session_start();

        $templateName = null;
        $data = [];
        $template = Container::getModel("Template");
        $template->__set("doctorId", $_SESSION["rfd"]["user"]["id"]);

        if (isset($_POST["templateName"])) {
            $templateName = $_POST["templateName"];

            $template->__set("name", $templateName);
            $templates = $template->getTemplateByName();
        } else {
            $templates = $template->getTemplateByDoctor();
        }

        for ($i = 0; $i < count($templates); $i++) {
            $medicines[$i] = Container::getModel("Medicine");
            $medicines[$i]->__set("templateId", $templates[$i]["ModeloID"]);
            $medicines[$i] = $medicines[$i]->getMedicineByTemplate();

            $data[] = [
                "ModeloID" => $templates[$i]["ModeloID"],
                "ModeloNome" => $templates[$i]["ModeloNome"],
                "medicines" => $medicines[$i]
            ];
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

        $template = Container::getModel("Template");
        $template->__set("id", $templateId);
        $templateName = $template->getTemplateByID()[0]["ModeloNome"];

        $medicines = Container::getModel("Medicine");
        $medicines->__set("templateId", $templateId);
        $medicines = $medicines->getMedicineByTemplate();

        $data = [
            "ModeloID" => $templateId,
            "ModeloNome" => $templateName,
            "medicines" => $medicines
        ];

        echo json_encode($data);
    }

    public function deleteTemplate()
    {
        $templateId = $_POST["templateId"];

        $template = Container::getModel("Template");
        $template->__set("id", $templateId);
        $template->delete();

        echo ("Template excluído");
    }
}
