<?php

namespace App\Controllers;

use MF\Controller\Action;
use MF\Model\Container;
use PDO;

class userAreaController extends Action
{

    private $totalPrescriptions = 5;

    public function userArea()
    {

        $this->render("userArea", "layoutUser");
    }

    public function userAreaScreen()
    {

        if (!isset($_POST["screen"])) {
            header("Location: /");
        }
        session_start();

        $screen = $_POST["screen"];

        $user = $_SESSION['rfd']['user'];

        $page = "";

        if ($screen == "home") {

            if ($user["type"] == "pacient") {
                $options = [
                    "prescription" => false,
                    "history" => true,
                    "templates" => false
                ];
            } elseif ($user["type"] == "doctor") {
                $options = [
                    "prescription" => "Emitir receita",
                    "history" => true,
                    "templates" => true
                ];
            } elseif ($user['type'] == "farmacy") {
                $options = [
                    "prescription" => "Buscar receitas",
                    "history" => true,
                    "templates" => false
                ];
            }


            $page = '
                <div class="row mb-4">
                    <div class="col-md-12 text-center">
                        <h1 class="welcome">Seja bem vindo ' . $user["name"] . '</h1>
                    </div>
                </div>
            
                <div class="row mb-3">
                    <div class="col-md-12 text-center">
                        <p class="title">O que gostaria de fazer?</p>
                    </div>
                </div>
                <div class="row cards">
                    <div class="row align-items-center justify-content-'; // row 1


            if ($options['prescription']) {
                $page .= 'center">';

                $col = 4;

                $page .= '
                    <div class="col-sm-' . $col . ' d-flex align-items-center justify-content-center">
                        <article class="cardOption d-flex align-items-center justify-content-center flex-column prescription">
                            <div class="d-flex align-items-center justify-content-center">
                                <span class="d-flex text-center align-items-center">
                                    <span class="material-icons">note_add</span>
                                    <h1>' . $options['prescription'] . '</h1>
                                </span>
                            </div>
                            <hr>
                        </article>
                    </div>
            ';
            } else {
                $page .= 'center">';
                $col = 3;
            }
            if ($options['history']) {
                $page .= '
                    <div class="col-sm-' . $col . ' d-flex align-items-center justify-content-center">
                        <article class="cardOption d-flex align-items-center justify-content-center flex-column history">
                            <div class="d-flex align-items-center justify-content-center">
                                <span class="d-flex text-center align-items-center">
                                    <span class="material-icons">history</span>
                                    <h1>Olhar meu histórico</h1>
                                </span>
                            </div>
                            <hr>
                        </article>
                    </div>
            ';
            }

            $page .= '</div>'; // row 1

            $page .= '<div class="row align-items-center justify-content-'; //row 2

            if ($options['templates']) {
                $page .= 'center">';

                $page .= '            
                <div class="col-sm-' . $col . ' d-flex align-items-center justify-content-center">
                    <article class="cardOption d-flex align-items-center justify-content-center flex-column templates">
                        <div class="d-flex align-items-center justify-content-center">
                            <span class="d-flex text-center align-items-center">
                                <span class="material-icons">content_paste</span>
                                <h1>Olhar meus modelos</h1>
                            </span>
                        </div>
                        <hr>
                    </article>
                </div>
            ';
            } else {
                $page .= 'center">';
            }
            $page .= '            
                <div class="col-sm-' . $col . ' d-flex align-items-center justify-content-center">
                    <article class="cardOption d-flex align-items-center justify-content-center flex-column settings">
                        <div class="d-flex align-items-center justify-content-center">
                            <span class="d-flex text-center align-items-center">
                                <span class="material-icons">settings</span>
                                <h1>Acessar as Configurações</h1>
                            </span>
                        </div>
                        <hr>
                    </article>
                </div>
            ';


            $page .= ' </div>'; // row 2

            $page .= ' </div>'; // cards
        } elseif ($screen == "prescription") {
            if ($user['type'] == 'doctor') {
                $page = '<h1>Tela de criar a Prescrição</h1>';
            } elseif ($user['type'] == 'farmacy') {
                $page = '
                    <div class="row">
                        <div class="col-md-12 text-center titleSrc">
                            Qual método gostaria de buscar a receita?
                        </div>
                    </div>
                
                    <div class="row justify-content-around mt-5">
                        <div class="col-5 d-flex align-items-center justify-content-between flex-column cardSrcOption" id="qrCode">
                            <div class="col-10 d-flex align-items-center justify-content-center">
                                <span class="material-icons imgSrcOption qrCodeSrc">qr_code_2</span>
                            </div>
                            <div class="col-9 btnSrcOption d-flex align-items-center justify-content-center text-center">
                                <h1>Ler Qr code da receita</h1>
                            </div>
                        </div>
                        <div class="col-5 off-set-2 d-flex align-items-center justify-content-between flex-column cardSrcOption" id="CPF">
                            <div class="col-10 d-flex align-items-center justify-content-center">
                                <span class="material-icons-outlined imgSrcOption prescriptionSrc">medical_information</span>
                            </div>
                            <div class="col-9 btnSrcOption d-flex align-items-center justify-content-center text-center">
                                <h1>Ler pelo CPF do paciente</h1>
                            </div>
                        </div>
                    </div>
                ';
            }
        } elseif ($screen == "history") {

            $_SESSION['rfd']['prescriptions'] = [
                [
                    "pacientName" => "João Vitor",
                    "prescriptionCode" => "P01",
                    "issueDate" => "2002-09-14",
                ],
                [
                    "pacientName" => "João Vitor",
                    "prescriptionCode" => "P02",
                    "issueDate" => "2022-09-14",
                ],
                [
                    "pacientName" => "João Vitor",
                    "prescriptionCode" => "P03",
                    "issueDate" => "2021-09-14",
                ],
                [
                    "pacientName" => "João Vitor",
                    "prescriptionCode" => "P04",
                    "issueDate" => "2021-09-14",
                ],
                [
                    "pacientName" => "João Vitor",
                    "prescriptionCode" => "P05",
                    "issueDate" => "2021-09-14",
                ],
                [
                    "pacientName" => "Seu Jorge",
                    "prescriptionCode" => "P06",
                    "issueDate" => "2000-01-01",
                ],
                [
                    "pacientName" => "Seu Jorge",
                    "prescriptionCode" => "P07",
                    "issueDate" => "2000-01-03",
                ],
                [
                    "pacientName" => "Seu Jorge",
                    "prescriptionCode" => "P08",
                    "issueDate" => "2000-01-03",
                ],
                [
                    "pacientName" => "Dona Maria",
                    "prescriptionCode" => "P09",
                    "issueDate" => "2000-01-02",
                ],
            ];

            $prescriptions = $_SESSION['rfd']['prescriptions'];


            if ($user["type"] == "pacient") {
                $options = [
                    "name" => false
                ];
            } elseif ($user["type"] == "doctor") {
                $options = [
                    "name" => true
                ];
            } else {
                $options = [
                    "name" => true
                ];
            }

            $page = '
                <div class="row mb-5">
                    <div class="col-md-12 text-center">
                        <h1 class="welcome">Seu histórico de receitas</h1>
                    </div>
                </div>
                <div class="row">
                    <form action="#" method="POST" class="row justify-content-between formSearchPrescription">
            '; // row1  // form

            if ($options['name']) {
                $col = 3;

                $page .= '
                    <div class="col-sm-4 d-flex flex-column">
                        <label for="pacientName" class="inputLabel">Paciente</label>
                        <input type="text" name="pacientName" id="pacientName" placeholder="Nome Paciente">
                    </div>
                ';
            } else {
                $col = 5;
            }

            $page .= '
                <div class="col-sm-' . $col . ' d-flex flex-column">
                    <label for="prescriptionCode" class="inputLabel">Código da Receita</label>
                    <input type="text" name="prescriptionCode" id="prescriptionCode" placeholder="ex: P14">
                </div>

                <div class="col-sm-' . $col . ' d-flex flex-column">
                    <label for="issueDate" class="inputLabel">Data de emissão</label>
                    <input type="date" name="issueDate" id="issueDate">
                </div>
            ';

            $page .= '
                <div class="row">
                    <div class="col-12">
                        <button class="searchBtn d-flex align-items-center justify-content-center">
                            <span class="material-icons-outlined">search</span>
                            Buscar
                        </button>
                    </div>
                </div>
            ';



            $page .= '</form>'; // form
            $page .= '</div>'; // row1

            $page .= '<div class="row mt-4 tableDiv">';
            $page .= $this->tableHistory(true);
            $page .= '</div>';
        } elseif($screen ==  "templates"){
            $page = '<h1>Tela dos modelos</h1>';
        }

        echo $page;
    }

    public function modalPrescription()
    {

        if (!isset($_GET['js'])) {
            header("Location: /plataforma");
        }

        $this->render('/components/modalPrescription', null);
    }

    public function tableHistory($local = false)
    {

        if (!isset($_POST["js"]) && !$local) {
            header("Location: /plataforma");
        }
        if (!isset($_SESSION)) {
            session_start();
        }
        $user = $_SESSION['rfd']['user'];

        $pacientName = null;
        $prescriptionCode = null;
        $issueDate = null;
        $pageNumber = 1;


        if (isset($_POST["pacientName"])) {
            $pacientName = $_POST["pacientName"];
        }

        if (isset($_POST["prescriptionCode"])) {
            $prescriptionCode = $_POST["prescriptionCode"];
        }

        if (isset($_POST["issueDate"])) {
            $issueDate = $_POST["issueDate"];
        }

        if (isset($_POST["page"])) {
            $pageNumber = $_POST["page"];
        }




        $data = [];

        $prescriptions = $_SESSION['rfd']['prescriptions'];

        if (!$pacientName && !$prescriptionCode && !$issueDate) {
            $data = $prescriptions;
        } else {
            foreach ($prescriptions as $prescription) {
                if ($pacientName == $prescription["pacientName"] || $prescriptionCode == $prescription["prescriptionCode"] || $issueDate == $prescription["issueDate"]) {
                    $data[] = $prescription;
                }
            }
        }

        $page = "";

        if ($data) {
            $page .= '                    
                    <table class="table text-center">
                        <thead>
                ';

            if ($user["type"] != "pacient") {
                $page .= '
                        <th class="text-start" scope="col">Paciente</th>
                        <th scope="col">Código receita</th>
                    ';
            } else {
                $page .= '<th class="text-start" scope="col">Código receita</th>';
            }

            $page .= '
                        <th scope="col">Data emissão</th>
                        <th scope="col">Detalhar</th>
                    </thead>
            ';

            $page .= '<tbody>';

            $limit = $this->totalPrescriptions * $pageNumber;
            for ($i = $limit - $this->totalPrescriptions; $i < $limit; $i++) {
                if (isset($data[$i])) {
                    $page .= '<tr>';
                    if ($user["type"] != "pacient") {
                        $page .= '
                            <td class="text-start">' . $data[$i]["pacientName"] . '</td>
                            <td>' . $data[$i]["prescriptionCode"] . '</td>
                        ';
                    } else {
                        $page .= '<td class="text-start">' . $data[$i]["prescriptionCode"] . '</td>';
                    }

                    $page .= '
                        <td>' . $data[$i]["issueDate"] . '</td>
                        <td><span id="' . $data[$i]["prescriptionCode"] . '" class="detailPrescription material-icons-outlined">info</span></td>
                    ';

                    $page .= '</tr>';
                }
            }

            foreach ($data as $prescription) {
            }



            $page .= '</tbody></table>';
            $page .= $this->paginationStyle($pageNumber, $data);
        }

        if (isset($_POST['js'])) {
            echo $page;
        }
        return $page;
    }


    public function paginationStyle($pageNumber, $prescriptions)
    {
        $page = '
            <div class="row paginationMenu">
                <div class="col-12 d-flex justify-content-center">
                    <nav>
                        <ul class="pagination">
        ';

        if (ceil(count($prescriptions) / $this->totalPrescriptions) == 1) {
            $page .= '
                <li class="page-item pageNumber 1 active">
                    <span class="page-link">1</span>
                </li>
            ';
        } else {
            $page .= '
                <li class="page-item pagePrevious disabled">
                    <span class="page-link">Previous</span>
                </li>
            ';

            for ($i = 1; $i <= ceil(count($prescriptions) / $this->totalPrescriptions); $i++) {
                $active = $i == $pageNumber ? 'active' : null;
                $page .= '
                    <li class="page-item ' . $active . ' pageNumber ' . $i . '">
                        <span class="page-link">' . $i . '</span>
                    </li>
                ';
            }


            $page .= '
                <li class="page-item pageNext">
                    <span class="page-link">Next</span>
                </li>
            ';
        }

        $page .= '
                        </ul>
                    </nav>
                </div>
            </div>
        ';

        return $page;
    }

    public function medicines()
    {
        if (isset($_POST['js'])) {
            $prescriptionCode = $_POST["prescriptionCode"];
            $medicines = [];

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
                            "medicineTime" => "1cp a cada 8h"
                        ],
                        [
                            "medicineName" => "Engov",
                            "medicineSize" => "10ml",
                            "medicineTime" => "1 a cada 12h"
                        ]
                    ]
                ],
                [
                    "pacientName" => "Seu Jorge",
                    "pacientCPF" => "123.456.789-01",
                    "prescriptionCode" => "P01",
                    "issueDate" => "2000-01-01",
                    "doctorName" => "Dra. Luciane Alves",
                    "medicines" => [
                        [
                            "medicineName" => "Dipirona",
                            "medicineSize" => "250mg",
                            "medicineTime" => "1cp a cada 8h"
                        ],
                        [
                            "medicineName" => "Doralgina ",
                            "medicineSize" => "500mg",
                            "medicineTime" => "1cp por dia"
                        ],
                        [
                            "medicineName" => "Doril ",
                            "medicineSize" => "1g",
                            "medicineTime" => "1cp por dia"
                        ],
                        [
                            "medicineName" => "Tylenol ",
                            "medicineSize" => "10ml",
                            "medicineTime" => "1 a cada 12h"
                        ],
                    ]
                ],
                [
                    "pacientName" => "Dona Maria",
                    "pacientCPF" => "123.456.789-02",
                    "prescriptionCode" => "P02",
                    "issueDate" => "2000-01-02",
                    "doctorName" => "Dr. Paulo Silvestre",
                    "medicines" => [
                        [
                            "medicineName" => "Dipirona",
                            "medicineSize" => "250mg",
                            "medicineTime" => "1cp a cada 8h"
                        ],
                        [
                            "medicineName" => "Novalgina ",
                            "medicineSize" => "250mg",
                            "medicineTime" => "1cp a cada 6h"
                        ],
                        [
                            "medicineName" => "Sonrisal",
                            "medicineSize" => "5ml",
                            "medicineTime" => "1 a cada 12h"
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
        if(isset($_POST["cpf"])){
            $code = $_POST["cpf"];
            $cpf = true;
        }else{
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
                        "medicineTime" => "1cp a cada 8h"
                    ],
                    [
                        "medicineName" => "Engov",
                        "medicineSize" => "10ml",
                        "medicineTime" => "1 a cada 12h"
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
                        "medicineTime" => "1cp a cada 8h"
                    ],
                    [
                        "medicineName" => "Engov",
                        "medicineSize" => "10ml",
                        "medicineTime" => "1 a cada 12h"
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
                        "medicineTime" => "1cp a cada 8h"
                    ],
                    [
                        "medicineName" => "Engov",
                        "medicineSize" => "10ml",
                        "medicineTime" => "1 a cada 12h"
                    ]
                ]
            ],
        ];

        foreach ($prescriptions as $p) {
            if($cpf){
                if ($code == $p["pacientCPF"]) {
                    $prescription = $p;
                }
            }else{
                if ($code == $p["prescriptionCode"]) {
                    $prescription = $p;
                }
            }
            
        }

        echo json_encode($prescription);
    }
}
