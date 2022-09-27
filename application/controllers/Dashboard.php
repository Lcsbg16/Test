<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once 'BasePrivateController.php';

class Dashboard extends BasePrivateController
{

    public function index()
    {
        $this->load->model('OcorrenciasModel');
        $this->load->model('EstacoesModel');

        $variaveisView = [];

        $variaveisView['titulo_pagina'] = 'Painel de Controle';
        $variaveisView['ocorrencias']   = $this->OcorrenciasModel->getOcorrencias(5);
        $variaveisView['qtde_estacoes'] = $this->EstacoesModel->getContagemEstacoes();

        $this->loadSmartyView('Dashboard/index', $variaveisView);
    }

}
