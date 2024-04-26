<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once 'BasePrivateController.php';

class Info extends BasePrivateController
{

    public function index()
    {   
        $this->load->model('EstacoesModel');
        $this->load->model('LeiturasModel');
        $this->load->model('OcorrenciasModel');
        
        $variaveisView = [];

        $variaveisView['titulo_pagina'] = 'Info';
        $variaveisView['qtde_estacoes'] = $this->EstacoesModel->getContagemEstacoes();

        $variaveisView['estacoes'] = $this->EstacoesModel->getEstacaoComDadosMeteorologicos();

        //var_dump($variaveisView['estacoes']);
        $this->loadSmartyView('Info/index', $variaveisView);
        
    }

}
