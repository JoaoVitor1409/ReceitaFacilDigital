<?php

    namespace App;

    use MF\Init\Bootstrap; // Require

    class Route extends Bootstrap{
        
        protected function initRoutes(){
            $routes['home'] = array(
                'route' => '/',
                'controller' => 'indexController',
                'action' => 'index'
            );

            $this->setRoutes($routes);  
        }        
    }
?>