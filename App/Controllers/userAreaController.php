<?php

namespace App\Controllers;

use MF\Controller\Action;
use MF\Model\Container;
use PDO;

class UserAreaController extends Action
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
            if ($user["type"] != "pacient") {
                if ($user["type"] == "doctor") {
                    $this->view->titlePrescription = "Emitir receita";
                } elseif ($user["type"] == "pharmacy") {
                    $this->view->titlePrescription = "Buscar receitas";
                }
                $this->view->col = 4;
            } else {
                $this->view->col = 3;
            }

            $this->renderPage("home");
        } elseif ($screen == "prescription") {
            if ($user['type'] == 'doctor') {
                $page = $this->renderPage("createPrescription");
            } elseif ($user['type'] == 'pharmacy') {
                $page = $this->renderPage("searchPrescription");
            }
        } elseif ($screen == "history") {
            $this->view->prescriptions = $_SESSION['rfd']['prescriptions'];
            if ($user["type"] != "pacient") {
                $this->view->col = 3;
            } else {
                $this->view->col = 5;
            }

            $page = $this->renderPage("historyPrescription");
        } elseif ($screen ==  "templates") {
            $page = $this->renderPage("templates");
        } elseif ($screen == "settings") {
            $page = $this->renderPage("settings");
        }

        echo $page;
    }

    private function renderPage($page)
    {
        $this->render("pages/{$page}", null);
    }

    public function modalPrescription()
    {

        if (!isset($_GET['js'])) {
            header("Location: /plataforma");
        }

        $this->render('/components/modalPrescription', null);
    }
    public function modalSelectTemplate()
    {

        if (!isset($_GET['js'])) {
            header("Location: /plataforma");
        }

        $this->render('/components/modalSelectTemplate', null);
    }
    public function modalSaveTemplate()
    {

        if (!isset($_GET['js'])) {
            header("Location: /plataforma");
        }

        $this->render('/components/modalSaveTemplate', null);
    }
    public function modalTemplate()
    {

        if (!isset($_GET['js'])) {
            header("Location: /plataforma");
        }

        $this->render('/components/modalTemplate', null);
    }

    public function tableHistory()
    {

        if (!isset($_POST["js"])) {
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

    public function getPacient()
    {
        $cpf = $_POST["cpf"];

        $pacients = [
            [
                "cpf" => "450.344.568-50",
                "phone" => "(15)99634-1499"
            ],
            [
                "cpf" => "123.456.789-00",
                "phone" => "(14)99871-0192"
            ],
            [
                "cpf" => "123.456.789-01",
                "phone" => "(14)99112-0910"
            ],
        ];

        foreach ($pacients as $pacient) {
            if ($pacient["cpf"] == $cpf) {
                echo json_encode(["phone" => $pacient["phone"]]);
                return;
            }
        }
    }
}
