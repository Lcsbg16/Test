<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once 'BasePrivateController.php';

class Dashboard extends BasePrivateController
{

    public function __construct()
    {
        parent::__construct();
        $this->session->cardsAlertaDashboard = [];
    }

    public function index()
    {
        $this->load->model('OcorrenciasModel');
        $this->load->model('EstacoesModel');
        $this->load->model('LeiturasModel');

        $variaveisView = [];

        $variaveisView['titulo_pagina'] = 'Painel de Controle';
        $variaveisView['ocorrencias']   = $this->OcorrenciasModel->getOcorrencias(5);

        $variaveisView['eventos'] = $this->EstacoesModel->getEventos(5);
        $this->loadSmartyView('Dashboard/index', $variaveisView);
    }

    public function listaCardsAlerta()
    {
        $cards            = [];
        $alertasDashboard = [];

        $alertas = $this->alertasubject->getMonitoramentos();
        foreach ($alertas as $index => $alertaAtual)
        {
            $alertasDashboard[] = $alertaAtual;
            $cards[]            = [
                'id'        => $index,
                'url'       => base_url('/Dashboard/cardAlerta/' . $index),
                'largura'   => 3,
                'corAlerta' => $alertaAtual->getCor()
            ];
        }
        $this->session->alertasDashboard = $alertasDashboard;

        /* $cards = [
          [
          'id'        => 1,
          'url'       => base_url('/Dashboard/cardAlertas'),
          'largura'   => 3,
          'corAlerta' => 'green'
          ]
          ]; */

        $this->jsonOutput($cards);
    }

    public function cardAlerta($indiceAlerta)
    {
        $alerta = unserialize(serialize($this->session->alertasDashboard[$indiceAlerta]));
        echo $alerta->getCardHTML();
    }

    public function cardAlertas()
    {
        $this->load->model('LeiturasModel');
        $variaveisView                       = [];
        $variaveisView['temperatura_minima'] = $this->LeiturasModel->getTemperaturaMinima();

        $this->loadSmartyView('Dashboard/cards/cardAlertas', $variaveisView);
    }

    public function cardContagemEstacoes()
    {
        $this->load->model('EstacoesModel');
        $variaveisView                  = [];
        $variaveisView['qtde_estacoes'] = $this->EstacoesModel->getContagemEstacoes();
        // $variaveisView['largura']       = $largura;
        $this->loadSmartyView('Dashboard/cards/cardContagemdeEstacoes', $variaveisView);
    }

    public function cardEstacoesAtivas()
    {
        $this->load->model('EstacoesModel');
        $variaveisView                         = [];
        $variaveisView['qtde_estacoes_online'] = $this->EstacoesModel->getQtdeEstacoesOnline();
        //  $variaveisView['largura']              = $largura;
        $this->loadSmartyView('Dashboard/cards/cardEstacoesAtivas', $variaveisView);
    }

    public function cardEstacoesOffline()
    {
        $this->load->model('EstacoesModel');
        $variaveisView                          = [];
        $variaveisView['qtde_estacoes_offline'] = $this->EstacoesModel->getQtdeEstacoesOffline();
        // $variaveisView['largura']               = $largura;
        $this->loadSmartyView('Dashboard/cards/cardEstacoesOffline', $variaveisView);
    }

    public function cardTemperaturaMedia()
    {
        $this->load->model('LeiturasModel');
        $variaveisView                      = [];
        $variaveisView['temperatura_media'] = $this->LeiturasModel->getUltimaTemperaturaMedia();
//$variaveisView['largura']           = $largura;

        $this->loadSmartyView('Dashboard/cards/cardTemperaturaMedia', $variaveisView);
    }

    public function cardTemperaturaMinima()
    {
        $this->load->model('LeiturasModel');
        $variaveisView                       = [];
        $variaveisView['temperatura_minima'] = $this->LeiturasModel->getTemperaturaMinima();
        //  $variaveisView['largura']            = $largura;

        $this->loadSmartyView('Dashboard/cards/cardTemperaturaMinima', $variaveisView);
    }

    public function cardTemperaturaMaxima()
    {
        $this->load->model('LeiturasModel');
        $variaveisView                       = [];
        $variaveisView['temperatura_maxima'] = $this->LeiturasModel->getTemperaturaMaxima();
        // $variaveisView['largura']            = $largura;

        $this->loadSmartyView('Dashboard/cards/cardTemperaturaMaxima', $variaveisView);
    }

    public function cardVolumeChuvaMinimo()
    {
        $this->load->model('LeiturasModel');
        $variaveisView                  = [];
        $variaveisView['vol_chuva_min'] = $this->LeiturasModel->getVolumeChuvaMinimo();
        //  $variaveisView['largura']       = $largura;

        $this->loadSmartyView('Dashboard/cards/cardVolumeChuvaMinimo', $variaveisView);
    }

    public function cardVolumeChuvaMaximo()
    {
        $this->load->model('LeiturasModel');
        $variaveisView                  = [];
        $variaveisView['vol_chuva_max'] = $this->LeiturasModel->getVolumeChuvaMaxima();
        // $variaveisView['largura']       = $largura;

        $this->loadSmartyView('Dashboard/cards/cardVolumeChuvaMaximo', $variaveisView);
    }

    public function cardVelocidadeMinimaVento()
    {
        $this->load->model('LeiturasModel');
        $variaveisView                      = [];
        $variaveisView['velocidade_minima'] = Conversao::velVentoParakmH($this->LeiturasModel->getVelocidadeMinima());
        //   $variaveisView['largura']           = $largura;

        $this->loadSmartyView('Dashboard/cards/cardVelocidadeMinimaVento', $variaveisView);
    }

    public function cardVelocidadeMaximaVento()
    {
        $this->load->model('LeiturasModel');
        $variaveisView                      = [];
        $variaveisView['velocidade_maxima'] = Conversao::velVentoParakmH($this->LeiturasModel->getVelocidadeMaxima());
        //  $variaveisView['largura']           = $largura;

        $this->loadSmartyView('Dashboard/cards/cardVelocidadeMaximaVento', $variaveisView);
    }
}
