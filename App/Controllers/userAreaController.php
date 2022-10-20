<?php

namespace App\Controllers;

use MF\Controller\Action;
use MF\Model\Container;
use PDO;

class userAreaController extends Action
{

    public function userArea() {

        $this->render("userArea", "layoutUser");
    }

    public function userAreaScreen() {

        if (!isset($_POST["screen"])) {
            header("Location: /");
        }

        $screen = $_POST["screen"];

        $user = [
            "name" => "João Vitor Martins de Siqueira",
            "type" => "doctor"
        ];

        $prescriptions = [
            [
                "code" => "P02",
                "issueDate" => "01/01/2002"
            ],
            [
                "code" => "P09",
                "issueDate" => "01/09/2002"
            ],
            [
                "code" => "P14",
                "issueDate" => "14/09/2022"
            ],
        ];

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
            } else {
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
        } elseif ($screen == 'prescription') {
            $page = '<h1>Tela da Prescrição</h1>';
        } elseif ($screen == 'history') {

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

            $page .= '</div>'; // row1

            $page .= '</form>'; // form


            $page .= '
                <div class="row mt-4 tableDiv">
                    <table class="table text-center">
                        <thead>
            ';

            if ($options["name"]) {
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

            foreach ($prescriptions as $prescription) {
                $page .= '<tr>';
                if ($options["name"]) {
                    $page .= '
                    <td class="text-start">' . $user["name"] . '</td>
                    <td>' . $prescription["code"] . '</td>
                ';
                } else {
                    $page .= '<td class="text-start">' . $prescription["code"] . '</td>';
                }

                $page .= '
                <td>' . $prescription["issueDate"] . '</td>
                <td><span id="' . $prescription["code"] . '" class="detailPrescription material-icons-outlined">info</span></td>
            ';

                $page .= '</tr>';
            }


            $page .= '
                        </tbody>
                    </table>
                </div>
            ';
        }

        echo $page;
    }

    public function modalPrescription() {

        if(isset($_GET['js'])){
            $this->render('/components/modalPrescription', null);
        }else{
            header("Location: /");
        }
    }
}
