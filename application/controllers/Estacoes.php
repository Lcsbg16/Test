<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once 'BasePrivateController.php';

class Estacoes extends BasePrivateController
{

    public function mapa()
    {
        $this->load->model('EstacoesModel');

        $variaveisView = [];

        $variaveisView['titulo_pagina'] = 'Mapa de Estações';
        $variaveisView['estacoes']      = $this->EstacoesModel->getEstacoes(true);

        $this->loadSmartyView('Estacoes/mapa', $variaveisView);
    }

}
