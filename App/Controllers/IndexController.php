<?php

    namespace App\Controllers;

    use MF\Controller\Action;
    class IndexController extends Action{

        public function index(){
            $this->render('index');            
        }

        public function contact(){
            $this->view->email = null;
            if(isset($_POST['email'])){
                $this->view->email = $_POST['email'];
            }
            $this->render('contact');
        }

        public function signUp()
        {
            $this->view->options =[
                'pacient' => [
                    'option' => 'pacient',
                    'checked' => true,
                    'value' => 'Paciente',
                    'valueFormated' => 'Paciente'
                ],
                'doctor' => [
                    'option' => 'doctor',
                    'checked' => false,
                    'value' => 'Medico',
                    'valueFormated' => 'Médico'
                ],
                'pharmacy' => [
                    'option' => 'pharmacy',
                    'checked' => false,
                    'value' => 'Farmacia',
                    'valueFormated' => 'Farmácia'
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