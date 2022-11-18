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

            $prescriptions = [
                [
                    "pacientName" => "João Vitor",
                    "pacientCPF" => "450.344.568-50",
                    "prescriptionCode" => "P01",
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
                    "pacientName" => "Seu Jorge",
                    "pacientCPF" => "123.456.789-01",
                    "prescriptionCode" => "P06",
                    "issueDate" => "2000-01-01",
                    "doctorName" => "Dra. Luciane Alves",
                    "medicines" => [
                        [
                            "medicineName" => "Dipirona",
                            "medicineSize" => "250mg",
                            "medicineFrequency" => "1cp a cada 8h"
                        ],
                        [
                            "medicineName" => "Doralgina ",
                            "medicineSize" => "500mg",
                            "medicineFrequency" => "1cp por dia"
                        ],
                        [
                            "medicineName" => "Doril ",
                            "medicineSize" => "1g",
                            "medicineFrequency" => "1cp por dia"
                        ],
                        [
                            "medicineName" => "Tylenol ",
                            "medicineSize" => "10ml",
                            "medicineFrequency" => "1 a cada 12h"
                        ],
                    ]
                ],
                [
                    "pacientName" => "Dona Maria",
                    "pacientCPF" => "123.456.789-02",
                    "prescriptionCode" => "P09",
                    "issueDate" => "2000-01-02",
                    "doctorName" => "Dr. Paulo Silvestre",
                    "medicines" => [
                        [
                            "medicineName" => "Dipirona",
                            "medicineSize" => "250mg",
                            "medicineFrequency" => "1cp a cada 8h"
                        ],
                        [
                            "medicineName" => "Novalgina ",
                            "medicineSize" => "250mg",
                            "medicineFrequency" => "1cp a cada 6h"
                        ],
                        [
                            "medicineName" => "Sonrisal",
                            "medicineSize" => "5ml",
                            "medicineFrequency" => "1 a cada 12h"
                        ],
                    ]
                ],
            ];

            foreach ($prescriptions as $prescription) {
                if ($prescriptionCode == $prescription["prescriptionCode"]) {
                    $medicines = $prescription;
                }
            }

            echo json_encode($medicines);
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
        echo json_encode($_POST);
    }
}
