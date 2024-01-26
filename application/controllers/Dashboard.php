<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once 'BasePrivateController.php';

class Dashboard extends BasePrivateController
{

    public function __construct()
    {
        $this->acoesPublicas[] = 'carregarCards';
    }

    public function index()
    {
        $this->load->model('OcorrenciasModel');
        $this->load->model('EstacoesModel');
        $this->load->model('LeiturasModel');

        $variaveisView = [];

        $variaveisView['titulo_pagina'] = 'Painel de Controle';
        $variaveisView['ocorrencias']   = $this->OcorrenciasModel->getOcorrencias(5);
//        $variaveisView['qtde_estacoes']         = $this->EstacoesModel->getContagemEstacoes();
//        $variaveisView['qtde_estacoes_online']  = $this->EstacoesModel->getQtdeEstacoesOnline();
//        $variaveisView['qtde_estacoes_offline'] = $this->EstacoesModel->getQtdeEstacoesOffline();
//        $variaveisView['temperatura_media']     = $this->LeiturasModel->getUltimaTemperaturaMedia();
//        $variaveisView['vol_chuva_min'] = $this->LeiturasModel->getVolumeChuvaMinimo();
//        $variaveisView['vol_chuva_max'] = $this->LeiturasModel->getVolumeChuvaMaxima();
//
//        $variaveisView['temperatura_minima'] = $this->LeiturasModel->getTemperaturaMinima();
//        $variaveisView['temperatura_maxima'] = $this->LeiturasModel->getTemperaturaMaxima();
//
//        //$variaveisView['velocidade_minima'] = $this->LeiturasModel->converterVelocidadeVentoKMH($this->LeiturasModel->getVelocidadeMinima());
//        $variaveisView['velocidade_minima'] = Conversao::velVentoParakmH($this->LeiturasModel->getVelocidadeMinima());
//        $variaveisView['velocidade_maxima'] = Conversao::velVentoParakmH($this->LeiturasModel->getVelocidadeMaxima());

        $variaveisView['eventos'] = $this->EstacoesModel->getEventos(5);
        $this->loadSmartyView('Dashboard/index', $variaveisView);
    }

    public function carregarCards()
    {
        $this->cardContagemEstacoes();
        $this->cardEstacoesAtivas();
        $this->cardEstacoesOffline();
        $this->cardTemperaturaMaxima();
        $this->cardTemperaturaMedia();
        $this->cardTemperaturaMinima();
        $this->cardVelocidadeMaximaVento();
        $this->cardVelocidadeMinimaVento();
        $this->cardVolumeChuvaMaximo();
        $this->cardVolumeChuvaMinimo();
    }

    public function cardContagemEstacoes()
    {
        $this->load->model('EstacoesModel');
        $variaveisView                  = [];
        $variaveisView['qtde_estacoes'] = $this->EstacoesModel->getContagemEstacoes();
        $this->loadSmartyView('Dashboard/cards/cardContagemdeEstacoes', $variaveisView);
    }

    public function cardEstacoesAtivas()
    {
        $this->load->model('EstacoesModel');
        $variaveisView                         = [];
        $variaveisView['qtde_estacoes_online'] = $this->EstacoesModel->getQtdeEstacoesOnline();
        $this->loadSmartyView('Dashboard/cards/cardEstacoesAtivas', $variaveisView);
    }

    public function cardEstacoesOffline()
    {
        $this->load->model('EstacoesModel');
        $variaveisView                          = [];
        $variaveisView['qtde_estacoes_offline'] = $this->EstacoesModel->getQtdeEstacoesOffline();
        $this->loadSmartyView('Dashboard/cards/cardEstacoesOffline', $variaveisView);
    }

    public function cardTemperaturaMedia()
    {
        $this->load->model('LeiturasModel');
        $variaveisView                      = [];
        $variaveisView['temperatura_media'] = $this->LeiturasModel->getUltimaTemperaturaMedia();

        $this->loadSmartyView('Dashboard/cards/cardTemperaturaMedia', $variaveisView);
    }

    public function cardTemperaturaMinima()
    {
        $this->load->model('LeiturasModel');
        $variaveisView                       = [];
        $variaveisView['temperatura_minima'] = $this->LeiturasModel->getTemperaturaMinima();

        $this->loadSmartyView('Dashboard/cards/cardTemperaturaMinima', $variaveisView);
    }

    public function cardTemperaturaMaxima()
    {
        $this->load->model('LeiturasModel');
        $variaveisView                       = [];
        $variaveisView['temperatura_maxima'] = $this->LeiturasModel->getTemperaturaMaxima();

        $this->loadSmartyView('Dashboard/cards/cardTemperaturaMaxima', $variaveisView);
    }

    public function cardVolumeChuvaMinimo()
    {
        $this->load->model('LeiturasModel');
        $variaveisView                  = [];
        $variaveisView['vol_chuva_min'] = $this->LeiturasModel->getVolumeChuvaMinimo();

        $this->loadSmartyView('Dashboard/cards/cardVolumeChuvaMinimo', $variaveisView);
    }

    public function cardVolumeChuvaMaximo()
    {
        $this->load->model('LeiturasModel');
        $variaveisView                  = [];
        $variaveisView['vol_chuva_max'] = $this->LeiturasModel->getVolumeChuvaMaxima();

        $this->loadSmartyView('Dashboard/cards/cardVolumeChuvaMaximo', $variaveisView);
    }

    public function cardVelocidadeMinimaVento()
    {
        $this->load->model('LeiturasModel');
        $variaveisView                      = [];
        $variaveisView['velocidade_minima'] = Conversao::velVentoParakmH($this->LeiturasModel->getVelocidadeMinima());

        $this->loadSmartyView('Dashboard/cards/cardVelocidadeMinimaVento', $variaveisView);
    }

    public function cardVelocidadeMaximaVento()
    {
        $this->load->model('LeiturasModel');
        $variaveisView                      = [];
        $variaveisView['velocidade_maxima'] = Conversao::velVentoParakmH($this->LeiturasModel->getVelocidadeMaxima());

        $this->loadSmartyView('Dashboard/cards/cardVelocidadeMaximaVento', $variaveisView);
    }
}
