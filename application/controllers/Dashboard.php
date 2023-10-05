<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once 'BasePrivateController.php';

class Dashboard extends BasePrivateController
{

    public function index()
    {
        $this->load->model('OcorrenciasModel');
        $this->load->model('EstacoesModel');
        $this->load->model('LeiturasModel');

        $variaveisView = [];

        $variaveisView['titulo_pagina']         = 'Painel de Controle';
        $variaveisView['ocorrencias']           = $this->OcorrenciasModel->getOcorrencias(5);
        $variaveisView['qtde_estacoes']         = $this->EstacoesModel->getContagemEstacoes();
        $variaveisView['qtde_estacoes_online']  = $this->EstacoesModel->getQtdeEstacoesOnline();
        $variaveisView['qtde_estacoes_offline'] = $this->EstacoesModel->getQtdeEstacoesOffline();
        $variaveisView['temperatura_media']     = $this->LeiturasModel->getUltimaTemperaturaMedia();

        $variaveisView['vol_chuva_min'] = $this->LeiturasModel->getVolumeChuvaMinimo();
        $variaveisView['vol_chuva_max'] = $this->LeiturasModel->getVolumeChuvaMaxima();

        $variaveisView['temperatura_minima'] = $this->LeiturasModel->getTemperaturaMinima();
        $variaveisView['temperatura_maxima'] = $this->LeiturasModel->getTemperaturaMaxima();

        $variaveisView['velocidade_minima'] = $this->LeiturasModel->converterVelocidadeVentoKMH($this->LeiturasModel->getVelocidadeMinima());
        $variaveisView['velocidade_maxima'] = $this->LeiturasModel->converterVelocidadeVentoKMH($this->LeiturasModel->getVelocidadeMaxima());

        $variaveisView['eventos'] = $this->EstacoesModel->getEventos(5);
        $this->loadSmartyView('Dashboard/index', $variaveisView);
    }
}
