<?php

    namespace App\Controllers;

    use MF\Controller\Action;
    class IndexController extends Action{

        public function index(){
            $this->render('index');            
        }

        public function contact(){
            $this->render('contact');
        }

        public function signUp()
        {
            $this->view->inputs = [
                'name' => [
                    'input' => 'name',
                    'icon' => 'person',
                    'type' => 'text',
                    'placeholder' => 'Nome Completo'
                ],
                'birthDate' => [
                    'input' => 'birthDate',
                    'icon' => 'calendar_month',
                    'type' => 'text',
                    'placeholder' => 'Data de Nascimento'
                ],
                'cpf' => [
                    'input' => 'cpf',
                    'icon' => 'badge',
                    'type' => 'text',
                    'placeholder' => 'CPF'
                ],
                'email' => [
                    'input' => 'email',
                    'icon' => 'email',
                    'type' => 'text',
                    'placeholder' => 'E-mail'
                ],
                'phone' => [
                    'input' => 'phone',
                    'icon' => 'phone_android',
                    'type' => 'text',
                    'placeholder' => 'Número de Celular'
                ]                
            ];

            $this->render('signUp', 'layoutAuth');
        }

        public function access(){

            $this->render('access', 'layoutAuth');
        }

        public function modal(){

            if(isset($_GET['js'])){
                $this->render('components/modals', null);
            }else{
                header("Location: /");
            }           
        }
    }