<?php

    namespace App;

    use MF\Init\Bootstrap;

    class Route extends Bootstrap{
        
        protected function initRoutes(){
            $routes['home'] = array(
                'route' => '/',
                'controller' => 'indexController',
                'action' => 'index'
            );

            $routes['contact'] = array(
                'route' => '/contato',
                'controller' => 'indexController',
                'action' => 'contact'
            );

            $this->setRoutes($routes);  
        }        
    }
?>