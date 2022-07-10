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

            $routes['access'] = array(
                'route' => '/acessar',
                'controller' => 'indexController',
                'action' => 'access'
            );

            $routes['modal'] = array(
                'route' => '/modals',
                'controller' => 'indexController',
                'action' => 'modal'
            );

            $routes['login'] = array(
                'route' => '/autenticar',
                'controller' => 'authController',
                'action' => 'login'
            );

            $this->setRoutes($routes);  
        }        
    }
?>