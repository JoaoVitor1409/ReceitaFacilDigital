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