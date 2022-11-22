<?php

namespace App;

use MF\Init\Bootstrap;

class Route extends Bootstrap
{

    protected function initRoutes()
    {

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

        $routes['modalSelectTemplate'] = array(
            'route' => '/plataforma/components/modalSelecionarModelo',
            'controller' => 'userAreaController',
            'action' => 'modalSelectTemplate'
        );

        $routes['modalSaveTemplate'] = array(
            'route' => '/plataforma/components/modalSalvarModelo',
            'controller' => 'userAreaController',
            'action' => 'modalSaveTemplate'
        );

        $routes['modalTemplate'] = array(
            'route' => '/plataforma/components/modalModelo',
            'controller' => 'userAreaController',
            'action' => 'modalTemplate'
        );

        $routes['tableHistory'] = array(
            'route' => '/plataforma/tabelaHistorico',
            'controller' => 'userAreaController',
            'action' => 'tableHistory'
        );

        $routes['getPacient'] = array(
            'route' => '/plataforma/procuraPaciente',
            'controller' => 'userAreaController',
            'action' => 'getPacient'
        );

        // prescriptionController

        $routes['medicines'] = array(
            'route' => '/plataforma/medicamentos',
            'controller' => 'prescriptionController',
            'action' => 'medicines'
        );

        $routes['getPrescription'] = array(
            'route' => '/plataforma/pesquisaReceita',
            'controller' => 'prescriptionController',
            'action' => 'getPrescription'
        );

        $routes['emitPrescription'] = array(
            'route' => '/plataforma/emiteReceita',
            'controller' => 'prescriptionController',
            'action' => 'emitPrescription'
        );

        $routes['dispensePrescription'] = array(
            'route' => '/plataforma/dispensaReceita',
            'controller' => 'prescriptionController',
            'action' => 'dispensePrescription'
        );

        // templateController

        $routes['saveTemplate'] = array(
            'route' => '/plataforma/salvarModelo',
            'controller' => 'templateController',
            'action' => 'saveTemplate'
        );

        $routes['templatesList'] = array(
            'route' => '/plataforma/listaModelos',
            'controller' => 'templateController',
            'action' => 'templatesList'
        );

        $routes['getTemplate'] = array(
            'route' => '/plataforma/pesquisaTemplate',
            'controller' => 'templateController',
            'action' => 'getTemplate'
        );

        $routes['deleteTemplate'] = array(
            'route' => '/plataforma/excluirTemplate',
            'controller' => 'templateController',
            'action' => 'deleteTemplate'
        );


        $this->setRoutes($routes);
    }
}
