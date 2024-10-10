<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once 'BasePrivateController.php';
require_once BASEPATH . '../application/models/LeiturasModel.php';

class Dashboard extends BasePrivateController
{

    private $objLeituraModel;
    private $fonteDadosController;


    public function __construct()
    {
        $this->acoesPublicas[] = 'carregarCards';

        parent::__construct();

        $this->session->cardsAlertaDashboard = [];
        $this->fonteDadosController = Fonte_Dashboard;
        $this->objLeituraModel = new LeiturasModel($this->fonteDadosController); 

    }

    public function index()
    {
        if(defined('Fonte_Dashboard_index')){
            $this->objLeituraModel = new LeiturasModel(Fonte_Dashboard_index); 
        }
        
        $this->load->model('OcorrenciasModel');
        $this->load->model('EstacoesModel');
       // $this->load->model('LeiturasModel');
      
       

        $variaveisView = [];

        $variaveisView['titulo_pagina'] = 'Painel de Controle';
        $variaveisView['ocorrencias']   = $this->OcorrenciasModel->getOcorrencias(5);

        $variaveisView['eventos'] = $this->EstacoesModel->getEventos(5);
        $this->loadSmartyView('Dashboard/index', $variaveisView);
    }

    public function cardAcumuladoChuvaPorPeriodoGeral()
    {
        //$this->load->model('LeiturasModel');
        //$this->objLeituraModel = new LeiturasModel($this->fonteDadosController); 
        $variaveisView = [];
       $variaveisView['acumulados'] = $this->objLeituraModel->getAcumuladoChuvaPorPeriodoGeral(); 
        $this->loadSmartyView('Dashboard/cards/cardAcumuladoChuvaPorPeriodoGeral', $variaveisView );
    }

    public function listaOcorrencias(){
        $this->load->model('OcorrenciasModel');
        $variaveisView = [];
        $variaveisView['ocorrencias']   = $this->OcorrenciasModel->getOcorrencias(5);
        $this->loadSmartyView('Dashboard/ocorrencias/ocorrencias', $variaveisView);

    }

      
    public function listaEventos(){
        $this->load->model('EstacoesModel');
        $variaveisView = [];
        $variaveisView['eventos'] = $this->EstacoesModel->getEventos(5);
        $this->loadSmartyView('Dashboard/eventos/eventos', $variaveisView);

    }

    public function listaCardsAlerta()
    {
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");

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

        $this->jsonOutput($cards);
    }

    public function cardAlerta($indiceAlerta)
    {
        $alerta = unserialize(serialize($this->session->alertasDashboard[$indiceAlerta]));
        echo $alerta->getCardHTML();
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
        //$this->load->model('LeiturasModel');
        $variaveisView                      = [];
        $variaveisView['temperatura_media'] = $this->objLeituraModel->getUltimaTemperaturaMedia();

        $this->loadSmartyView('Dashboard/cards/cardTemperaturaMedia', $variaveisView);
    }

    public function cardTemperaturaMinima()
    {
       // $this->load->model('LeiturasModel');
        $variaveisView                       = [];
        $variaveisView = $this->objLeituraModel->getTemperaturaMinima();

        $this->loadSmartyView('Dashboard/cards/cardTemperaturaMinima', $variaveisView);
    }

    public function cardAlertas()
    {
        $variaveisView                       = [];
        $this->loadSmartyView('Dashboard/cards/cardAlertas', $variaveisView);
    }

    public function cardTemperaturaMaxima()
    {
       // $this->load->model('LeiturasModel');
        $variaveisView                       = [];
        $variaveisView = $this->objLeituraModel->getTemperaturaMaxima();
        $this->loadSmartyView('Dashboard/cards/cardTemperaturaMaxima', $variaveisView);
    }

    public function cardVolumeChuvaMinimo()
    {
       // $this->load->model('LeiturasModel');
        $variaveisView                  = [];
        $variaveisView = $this->LeiturasModel->getVolumeChuvaMinimo();

        $this->loadSmartyView('Dashboard/cards/cardVolumeChuvaMinimo', $variaveisView);
    }

    public function cardVolumeChuvaMaximo()
    {
        //$this->load->model('LeiturasModel');
        $variaveisView                  = [];
        $variaveisView = $this->objLeituraModel->getVolumeChuvaMaxima();

        $this->loadSmartyView('Dashboard/cards/cardVolumeChuvaMaximo', $variaveisView);
    }

    public function cardVelocidadeMinimaVento()
    {
       // $this->load->model('LeiturasModel');
        $variaveisView = [];

        $variaveisView['velocidade_minima'] = $this->objLeituraModel->getVelocidadeMinima() !== NULL ? Conversao::metroPorSegundoParaKmPorHora($this->LeiturasModel->getVelocidadeMinima()) : NULL;

        $this->loadSmartyView('Dashboard/cards/cardVelocidadeMinimaVento', $variaveisView);
    }

    public function cardVelocidadeMaximaVento()
    {
      //  $this->load->model('LeiturasModel');
        $variaveisView = [];
        $variaveisView = $this->LeiturasModel->getVelocidadeMaxima();

        if ($variaveisView) {
            $variaveisView['velocidade_maxima'] = Conversao::metroPorSegundoParaKmPorHora($variaveisView['velocidade_maxima']);
        }

        $this->loadSmartyView('Dashboard/cards/cardVelocidadeMaximaVento', $variaveisView);
    }
}
