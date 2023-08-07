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

    public function mapaMonitoramento()
    {
        $this->load->model('EstacoesModel');

        $variaveisView = [];

        $variaveisView['titulo_pagina'] = 'Mapa de Monitoramento';
        $variaveisView['estacoes']      = $this->EstacoesModel->getEstacoes(true);

        $this->loadSmartyView('Estacoes/mapaMonitoramento', $variaveisView);
    }

    public function monitoramentoIndividual()
    {
        $this->load->model('EstacoesModel');

        $variaveisView = [];

        $variaveisView['titulo_pagina'] = 'Monitoramento individual de estações';
        $variaveisView['estacoes']      = $this->EstacoesModel->getEstacoes(true);

        $this->loadSmartyView('Estacoes/monitoramentoIndividual', $variaveisView);
    }

    public function getEstacoesGeoJson($idCamada = NULL)
    {
        $this->load->model('EstacoesModel');
        $ids = $this->input->get('ids'); // IDs selecionados

    // Separa os IDs das estações em um array
        $estacoesIds = explode(',', $ids);
        $estacoes = $this->EstacoesModel->getEstacoesGeoJson($idCamada, $estacoesIds); //segundo argumento: os IDs das estações

        $this->jsonOutput($estacoes);
    }
	
	 public function monitoramentoEstacao()
    {
        $this->load->model('EstacoesModel');

        $this->EstacoesModel->monitorarEstacao(5);
    }

}
