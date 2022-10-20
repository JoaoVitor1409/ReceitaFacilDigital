<?php

    namespace App;

    use MF\Init\Bootstrap;

    class Route extends Bootstrap{
        
        protected function initRoutes(){

            // indexController

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

            $routes['signUp'] = array(
                'route' => '/cadastrar',
                'controller' => 'indexController',
                'action' => 'signUp'
            );

            $routes['access'] = array(
                'route' => '/acessar',
                'controller' => 'indexController',
                'action' => 'access'
            );

            $routes['modal'] = array(
                'route' => '/components/modals',
                'controller' => 'indexController',
                'action' => 'modal'
            );


            // authController

            $routes['register'] = array(
                'route' => '/registrar',
                'controller' => 'authController',
                'action' => 'register'
            );

            $routes['login'] = array(
                'route' => '/autenticar',
                'controller' => 'authController',
                'action' => 'login'
            );

            $routes['sendVerificationCode'] = array(
                'route' => '/enviarCodigo',
                'controller' => 'authController',
                'action' => 'sendVerificationCode'
            );

            $routes['verifyCode'] = array(
                'route' => '/verificarCodigo',
                'controller' => 'authController',
                'action' => 'verifyCode'
            );

            $routes['changePassword'] = array(
                'route' => '/alterarSenha',
                'controller' => 'authController',
                'action' => 'changePassword'
            );

            $routes['logout'] = array(
                'route' => '/plataforma/sair',
                'controller' => 'authController',
                'action' => 'logout'
            );


            // userAreaController

            $routes['userArea'] = array(
                'route' => '/plataforma',
                'controller' => 'userAreaController',
                'action' => 'userArea'
            );

            $routes['userAreaScreen'] = array(
                'route' => '/plataforma/loadScreen',
                'controller' => 'userAreaController',
                'action' => 'userAreaScreen'
            );

            $routes['modalPrescription'] = array(
                'route' => '/plataforma/components/modalReceita',
                'controller' => 'userAreaController',
                'action' => 'modalPrescription'
            );


            $this->setRoutes($routes);  
        }        
    }
?>