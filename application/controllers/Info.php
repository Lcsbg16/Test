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
        // $variaveisView['temperatura_media'] = $this->LeiturasModel->getUltimaTemperaturaMedia();
        // $variaveisView['vol_chuva_min'] = $this->LeiturasModel->getVolumeChuvaMinimo();
        // $variaveisView['vol_chuva_max'] = $this->LeiturasModel->getVolumeChuvaMaxima();
        // $variaveisView['temperatura_minima'] = $this->LeiturasModel->getTemperaturaMinima();
        // $variaveisView['temperatura_maxima'] = $this->LeiturasModel->getTemperaturaMaxima();
        // $variaveisView['velocidade_minima'] = Conversao::velVentoParakmH($this->LeiturasModel->getVelocidadeMinima());
        // $variaveisView['velocidade_maxima'] = Conversao::velVentoParakmH($this->LeiturasModel->getVelocidadeMaxima());
        
        // $variaveisView['estacoes'] = $this->EstacoesModel->getEstacoes();

        $variaveisView['estacoes'] = $this->EstacoesModel->getEstacaoComDadosMeteorologicos();

        //var_dump($variaveisView['estacoes']);
        $this->loadSmartyView('Info/index', $variaveisView);
        
    }

}
