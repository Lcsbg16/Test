<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once 'BasePrivateController.php';

class Grafico extends BasePrivateController
{

    public function index()
    {
        $this->load->model('EstacoesModel');

        $variaveisView = [];

        $variaveisView['estacoes'] = $this->EstacoesModel->getEstacoes();

        $this->loadSmartyView('Grafico/index', $variaveisView);
    }

}
