<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once 'BasePrivateController.php';

class Grafico extends BasePrivateController
{

    public function index()
    {
        $this->load->model('EstacoesModel');

        $variaveisView = [];

       // $estacoes  = $this->EstacoesModel->getEstacoesComAcessoPorUsuario();  
        $this->EstacoesModel->getArrayEstacoesComAcesso();
     
       // $imploded = implode(',', $this->EstacoesModel->estacoesComAcesso  );
       
        $variaveisView['estacoes'] = $this->EstacoesModel->getEstacoes(false,$this->EstacoesModel->estacoesComAcesso);


       // $variaveisView['estacoes']      = $this->EstacoesModel->getEstacoes();
        $variaveisView['titulo_pagina'] = 'Estatísticas';

        $this->loadSmartyView('Grafico/index', $variaveisView);
    }

}
