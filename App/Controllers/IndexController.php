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

        public function access(){

            $this->render('access', null);
        }

        public function modal(){

            if(isset($_GET['js'])){
                $this->render('modals', null);
            }else{
                header("Location: /");
            }           
        }
    }