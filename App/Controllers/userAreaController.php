<?php

    namespace App\Controllers;

    use MF\Controller\Action;
    use MF\Model\Container;

    class userAreaController extends Action{

        public function userArea(){
            
            $this->render("userArea", "layoutUser");
        }
    }