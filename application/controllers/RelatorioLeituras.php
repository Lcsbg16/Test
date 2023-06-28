<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once 'BaseCrudController.php';

class RelatorioLeituras extends BaseCrudController
{

    public function index()
    {
        $this->load->model('LeiturasModel');
        $this->load->model('EstacoesModel');
        $variaveisView = [];
        $filtros = new FiltrosLeitura(); //New filtro -> contato com o BD
        if($this->input->get('tipoInformacao'))
        {
            $estacoes = $this->input->get('estacoes');
            $dataInicial = $this->input->get('dataInicial');
            $dataFinal = $this->input->get('dataFinal');
            $colunaPeriodo = $this->input->get('escala');
            $colunaTipoInformacao = $this->input->get('tipoInformacao');
    
            $classe = new ReflectionClass('FiltrosLeitura');
            $colunaPeriodo = $classe->getConstant($colunaPeriodo);
            $colunaTipoInformacao = $classe->getConstant($colunaTipoInformacao);    
            
            $filtros->setEstacoes($estacoes);
            $filtros->setDataInicial($dataInicial);
            $filtros->setDataFinal($dataFinal);
            $filtros->setTipoInformacao($colunaTipoInformacao);
            $filtros->setEscala($colunaPeriodo);
            $filtros->setDirecao(FiltrosLeitura::DIRECAO_ASC);
            $leituras = $this->LeiturasModel->calcularEstatisticasPorPeriodo($filtros);            
        }
        else
        {
            $leituras = [];
        }        
        $variaveisView['estacoes']      = $this->EstacoesModel->getEstacoes();
        $variaveisView['titulo_pagina'] = 'Estatísticas';
        $variaveisView['leituras'] = $leituras;
        $this->loadSmartyView('Leituras/index', $variaveisView);
    }

}
