<?php

namespace App\Controllers;

use MF\Controller\Action;
use MF\Model\Container;
use PDO;

class PrescriptionController extends Action
{

    public function medicines()
    {
        if (isset($_POST['js'])) {
            $prescriptionCode = $_POST["prescriptionCode"];
            $medicines = [];

            $prescription = Container::getModel("Prescription");
            $prescription->__set("id", $prescriptionCode);
            $prescription = $prescription->getPrescriptionById()[0];

            $pacient = Container::getModel("Pacient");
            $pacient->__set("cpf", $prescription["PacienteCPF"]);
            $pacientName = $pacient->getPacientByCPF()[0]["PacienteNome"];

            $doctor = Container::getModel("Doctor");
            $doctor->__set("id", $prescription["MedicoID"]);
            $doctorName = $doctor->getDoctorById()[0]["MedicoNome"];

            $medicine = Container::getModel("Medicine");
            $medicine->__set("prescriptionId", $prescriptionCode);
            $medicines = $medicine->getMedicineByPrescription();

            $prescription = [
                "PacienteNome" => $pacientName,
                "PacienteCPF" => $prescription["PacienteCPF"],
                "ReceitaData" => $prescription["ReceitaData"],
                "MedicoNome" => $doctorName,
                "medicines" => $medicines
            ];

            echo json_encode($prescription);
        } else {
            header("Location: /plataforma");
        }
    }

    public function getPrescription()
    {
        $cpf = false;
        if (isset($_POST["cpf"])) {
            $code = $_POST["cpf"];
            $cpf = true;
        } else {
            $code = $_POST['code'];
        }
        $prescription = [];

        $prescriptions = [
            [
                "pacientName" => "João Vitor",
                "pacientCPF" => "450.344.568-50",
                "prescriptionCode" => "P14",
                "issueDate" => "2002-09-14",
                "doctorName" => "Dr. Luciano Alves",
                "medicines" => [
                    [
                        "medicineName" => "Dipirona",
                        "medicineSize" => "250mg",
                        "medicineFrequency" => "1cp a cada 8h"
                    ],
                    [
                        "medicineName" => "Engov",
                        "medicineSize" => "10ml",
                        "medicineFrequency" => "1 a cada 12h"
                    ]
                ]
            ],
            [
                "pacientName" => "Paulo Roberto",
                "pacientCPF" => "123.456.789-00",
                "prescriptionCode" => "P01",
                "issueDate" => "2000-01-01",
                "doctorName" => "Dra. Maria Alves",
                "medicines" => [
                    [
                        "medicineName" => "Dipirona",
                        "medicineSize" => "250mg",
                        "medicineFrequency" => "1cp a cada 8h"
                    ],
                    [
                        "medicineName" => "Engov",
                        "medicineSize" => "10ml",
                        "medicineFrequency" => "1 a cada 12h"
                    ]
                ]
            ],
            [
                "pacientName" => "Maria Julia",
                "pacientCPF" => "123.456.789-01",
                "prescriptionCode" => "P02",
                "issueDate" => "2000-02-01",
                "doctorName" => "Dr. Rodrigo Guedes",
                "medicines" => [
                    [
                        "medicineName" => "Dipirona",
                        "medicineSize" => "250mg",
                        "medicineFrequency" => "1cp a cada 8h"
                    ],
                    [
                        "medicineName" => "Engov",
                        "medicineSize" => "10ml",
                        "medicineFrequency" => "1 a cada 12h"
                    ]
                ]
            ],
        ];

        foreach ($prescriptions as $p) {
            if ($cpf) {
                if ($code == $p["pacientCPF"]) {
                    $prescription = $p;
                    break;
                }
            } elseif ($code == $p["prescriptionCode"]) {
                $prescription = $p;
                break;
            }
        }

        echo json_encode($prescription);
    }

    public function emitPrescription()
    {
        $pacientCPF = $_POST["cpf"];
        $pacientPhone = $_POST["phone"];
        $medicineName = $_POST["medicineName"];
        $medicineSize = $_POST["medicineSize"];
        $medicineFrequency = $_POST["medicineFrequency"];
        $medicineObs = $_POST["medicineObs"];

        if (!$this->CpfValidation($pacientCPF)) {
            $result = ["code" => 0, "message" => "Digite um CPF válido", "input" => "cpf"];

            echo json_encode($result);
            return;
        }

        session_start();

        $prescription = Container::getModel("Prescription");
        $prescription->__set("pacientCPF", $pacientCPF);
        $prescription->__set("pacientPhone", $pacientPhone);
        $prescription->__set("doctorId", $_SESSION["rfd"]["user"]["id"]);

        $prescription->save();
        $prescriptionId = $prescription->getLastPrescription()[0]["ReceitaID"];

        for ($i = 0; $i < count($medicineName); $i++) {
            $medicine[$i] = Container::getModel("Medicine");
            $medicine[$i]->__set("desc", $medicineName[$i]);
            $medicine[$i]->__set("size", $medicineSize[$i]);
            $medicine[$i]->__set("frequency", $medicineFrequency[$i]);

            if (isset($medicineObs[$i])) {
                $medicine[$i]->__set("obs", $medicineObs[$i]);
            }

            $medicine[$i]->__set("prescriptionId", $prescriptionId);
            $medicine[$i]->save();
        }

        $result = ["code" => 1, "message" => "Receita Emitida com sucesso", "prescriptionId" => $prescriptionId];

        echo json_encode($result);
    }

    private function CpfValidation($cpf)
    {

        $cpf = preg_replace('/[^0-9]/is', '', $cpf);

        if (strlen($cpf) != 11) {
            return false;
        }

        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }
        return true;
    }
}
