<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once 'BaseCrudController.php';
require_once BASEPATH . '../application/models/LeiturasModel.php';

class RelatorioLeituras extends BaseCrudController
{


    private $objLeituraModel;
    private $fonteDadosController = "";

    public function index()
    {
       
        if(defined('Fonte_RelatorioLeitura')){
            $this->fonteDadosController = Fonte_RelatorioLeitura ;
        }

       $this->objLeituraModel = new LeiturasModel($this->fonteDadosController); 
        $this->load->model('EstacoesModel');
        $variaveisView = [];
        $filtros       = new FiltrosLeitura(); //New filtro -> contato com o BD
        if ($this->input->get('tipoInformacao'))
        {
            $estacoes             = $this->input->get('estacoes');
            $dataInicial          = $this->input->get('dataInicial');
            $dataFinal            = $this->input->get('dataFinal');
            $colunaPeriodo        = $this->input->get('escala');
            $colunaTipoInformacao = $this->input->get('tipoInformacao');

            $classe               = new ReflectionClass('FiltrosLeitura');
            $colunaPeriodo        = $classe->getConstant($colunaPeriodo);
            $colunaTipoInformacao = $classe->getConstant($colunaTipoInformacao);

            if ($estacoes)
            {
                $filtros->setEstacoes($estacoes);
            }

            if ($dataInicial)
            {
                $filtros->setDataInicial($dataInicial);
            }

            if ($dataFinal)
            {
                $filtros->setDataFinal($dataFinal);
            }

            if ($colunaPeriodo)
            {
                $filtros->setEscala($colunaPeriodo);
            }

            if ($colunaTipoInformacao)
            {
                $filtros->setTipoInformacao($colunaTipoInformacao);
            }
            $filtros->setDirecao(FiltrosLeitura::DIRECAO_ASC);
            $leituras = $this->objLeituraModel->calcularEstatisticasPorPeriodo($filtros);
            
        }
        else
        {
            $leituras = [];
        }
        
        //$estacoes  = $this->EstacoesModel->getEstacoesComAcessoPorUsuario();

        $this->EstacoesModel->getArrayEstacoesComAcesso();
               

        $variaveisView['estacoes']      = $this->EstacoesModel->getEstacoes(false, $this->EstacoesModel->estacoesComAcesso);
       // $variaveisView['estacoes']      = $this->EstacoesModel->getEstacoes();
        $variaveisView['titulo_pagina'] = 'Estatísticas';
        $variaveisView['leituras']      = $leituras;

        $this->loadSmartyView('RelatorioLeituras/index', $variaveisView);
    }
}
