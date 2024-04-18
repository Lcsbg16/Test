<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once 'BasePrivateController.php';

class Grafico extends BasePrivateController
{

    public function index()
    {
        $this->load->model('EstacoesModel');

        $variaveisView = [];

        $estacoes  = $this->EstacoesModel->getEstacoesComAcessoPorUsuario();  
        $imploded = implode(',', array_map('array_pop', $estacoes));
        $variaveisView['estacoes'] = $this->EstacoesModel->getEstacoes(false, explode(',',$imploded));


       // $variaveisView['estacoes']      = $this->EstacoesModel->getEstacoes();
        $variaveisView['titulo_pagina'] = 'Estatísticas';

        $this->loadSmartyView('Grafico/index', $variaveisView);
    }

}
