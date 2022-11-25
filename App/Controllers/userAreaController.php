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

        $pacientCPF = null;
        $prescriptionCode = null;
        $issueDate = null;
        $pageNumber = 1;


        if (isset($_POST["pacientCPF"])) {
            $pacientCPF = $_POST["pacientCPF"];
        }

        if (isset($_POST["prescriptionCode"])) {
            $prescriptionCode =  $_POST["prescriptionCode"];
        }

        if (isset($_POST["issueDate"])) {
            if ($_POST["issueDate"]  != "dd/mm/aaaa") {
                $issueDate = $_POST["issueDate"];
            }
        }

        if (isset($_POST["page"])) {
            $pageNumber = $_POST["page"];
        }

        $data = [];

        $prescription = Container::getModel("Prescription");

        if ($_SESSION["rfd"]["user"]["type"] == "pacient") {
            $prescription->__set("pacientCPF", $_SESSION["rfd"]["user"]["cpf"]);
        } else if ($_SESSION["rfd"]["user"]["type"] == "doctor") {
            $prescription->__set("doctorId", $_SESSION["rfd"]["user"]["id"]);
        } else if ($_SESSION["rfd"]["user"]["type"] == "pharmacy") {
            $prescription->__set("pharmacyId", $_SESSION["rfd"]["user"]["id"]);
        }

        if (!$pacientCPF && !$prescriptionCode && !$issueDate) {
            $data = $prescription->getAllPrescriptions();
        } else {
            $prescription->__set("id", $prescriptionCode);
            $prescription->__set("pacientCPF", $pacientCPF);
            $prescription->__set("date", $issueDate);

            $data = $prescription->getPrescriptionByFilter();
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
                            <td class="text-start">' . $data[$i]["PacienteCPF"] . '</td>
                            <td>' . $data[$i]["ReceitaID"] . '</td>
                        ';
                    } else {
                        $page .= '<td class="text-start">' . $data[$i]["ReceitaID"] . '</td>';
                    }

                    $page .= '
                        <td>' . $data[$i]["ReceitaData"] . '</td>
                        <td><span id="' . $data[$i]["ReceitaID"] . '" class="detailPrescription material-icons-outlined">info</span></td>
                    ';

                    $page .= '</tr>';
                }
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

        $pacient = Container::getModel("Pacient");
        $pacient->__set("cpf", $cpf);
        $pacient = $pacient->getPacientByCPF();

        if ($pacient) {
            echo json_encode(["phone" => $pacient[0]["PacienteCelular"]]);
        }
    }

    public function getPharmacy()
    {
        session_start();
        $doctor = Container::getModel("Doctor");
        $doctor->__set("id", $_SESSION["rfd"]["user"]["id"]);
        $adressId = $doctor->getDoctorById()[0]["EnderecoID"];

        $adress = Container::getModel("Adress");
        $adress->__set("id", $adressId);
        $adressData = $adress->getAdressById()[0];

        $pharmacy = Container::getModel("Pharmacy");
        $pharmacy->__set("adressState", $adressData["EnderecoUF"]);
        $pharmacy->__set("adressCity", $adressData["EnderecoCidade"]);
        $pharmacys = $pharmacy->getPharmacyByAdress();

        echo json_encode($pharmacys);
    }

    public function updateUser()
    {
        session_start();
        $email = $_POST["email"];
        $password = md5($_POST["password"]);
        $newPassword = $password;
        if ($_POST["newPassword"]) {
            $newPassword = md5($_POST["newPassword"]);
        }
        

        if (isset($_POST["phone"])) {
            $phone = $_POST["phone"];
        }

        if (isset($_POST["tel"])) {
            $phone = $_POST["tel"];
        }

        $sessionUser = $_SESSION["rfd"]["user"];
        $result = ["code" => 1, "message" => "Usuário atualizado com sucesso"];

        $user = Container::getModel("User");
        $user->__set("email", $sessionUser["email"]);
        $user->__set("password", $password);
        $user = $user->authenticate();

        if (!$user->__get("id")) {
            $result = ["code" => 0, "message" => "Senha Atual incorreta", "input" => "password"];
            echo json_encode($result);
            return;
        }

        if ($sessionUser["type"] == "pacient") {
            $user = Container::getModel("Pacient");
            $user->__set("phone", $phone);
        } else if ($sessionUser["type"] == "doctor") {
            $user = Container::getModel("Doctor");
            $user->__set("phone", $phone);
        } else if ($sessionUser["type"] == "pharmacy") {
            $user = Container::getModel("Pharmacy");
            $user->__set("tel", $phone);
        }

        $user->__set("id", $sessionUser["id"]);
        $user->__set("email", $email);
        $user->__set("password", $newPassword);
        $user->update();

        $_SESSION["rfd"]["user"]["email"] = $email;
        $_SESSION["rfd"]["user"]["phone"] = $phone;



        echo json_encode($result);
    }
}
