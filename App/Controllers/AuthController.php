<?php

    namespace App\Controllers;

    use MF\Controller\Action;
    use MF\Model\Container;

    class AuthController extends Action{
        public function login(){
            session_start();
            $_SESSION['rfd'] = [
                'user' => [
                    "name" => "João Vitor Martins de Siqueira",
                    "type" => "doctor"
                ]
            ];

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
            
            header("Location: /plataforma");
        }

        public function register(){

        }

        public function sendVerificationCode(){

        }

        public function verifyCode(){

        }

        public function changePassword(){

        }

        public function logout(){
            session_start();
            session_destroy();
            header("Location: /");
        }
    }