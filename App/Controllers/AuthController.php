<?php

    namespace App\Controllers;

    use MF\Controller\Action;
    use MF\Model\Container;

    class AuthController extends Action{
        public function login(){
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
            header("Location: /");
        }
    }