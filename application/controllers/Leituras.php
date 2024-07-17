<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once 'BasePrivateController.php';

require_once BASEPATH . '../application/models/LeiturasModel.php';

class Leituras extends BasePrivateController
{

    private $objLeituraModel;
    private $fonteDadosController;

    public function __construct()
    {
        parent::__construct();

        $this->fonteDadosController = Fonte_Dashboard;
        $this->objLeituraModel = new LeiturasModel($this->fonteDadosController); 

    }

    public function getEstatisticasLeiturasJson() //Gerencia dados do Gráfico
    {
       // $this->load->model('LeiturasModel');

        $estacao     = $this->input->post('estacao_selecionada');
        $dataInicial = $this->input->post('data_inicial');
        $dataFinal   = $this->input->post('data_final');
        $escala      = $this->input->post('escala');
        $tipo_dados  = $this->input->post('tipo_dados');

        $filtros = new FiltrosLeitura();
        $filtros->setEstacoes($estacao);
        $filtros->setDataInicial($dataInicial);
        $filtros->setDataFinal($dataFinal);
        $filtros->setEscala(constant("FiltrosLeitura::$escala"));
        $filtros->setTipoInformacao(constant("FiltrosLeitura::$tipo_dados"));
        $filtros->setDirecao(FiltrosLeitura::DIRECAO_ASC);

       // $this->objLeituraModel = new LeiturasModel($this->fonteDadosController); 

        $leituras = $this->objLeituraModel->calcularEstatisticasPorPeriodo($filtros);

        $this->jsonOutput($leituras);
    }

    public function getUltimaLeituraRegistrada()
    {
        $estacao    = $this->input->post('estacao_selecionada');
        $tipo_dados = $this->input->post('tipo_dados');

        //$this->load->model('LeiturasModel');
        $filtros  = new FiltrosLeitura();
        $filtros->setEstacoes($estacao);
        $filtros->setTipoInformacao(constant("FiltrosLeitura::$tipo_dados"));
        $leituras = $this->objLeituraModel->getUltimaLeituraRegistrada($filtros);

        $this->jsonOutput($leituras);
    }

    public function exportarLeitura()
    {
       // $this->load->model('LeiturasModel');
        $this->load->model('EstacoesModel');
        $this->load->model('LoginModel');

        $podeVerTodasAsEstacoes = $this->LoginModel->checaPermissaoUsuarioLogado('VER_TODAS_AS_ESTACOES');

        // $result  = $this->EstacoesModel->getEstacoesComAcessoPorUsuario();
        $this->EstacoesModel->getArrayEstacoesComAcesso();

        $variaveisView = [];
        $filtros       = new FiltrosLeitura();

        if ($this->input->get('escala'))
        {
            $estacoes      = $this->input->get('estacoes');
            $dataInicial   = $this->input->get('data_inicial');
            $dataFinal     = $this->input->get('data_final');
            $colunaPeriodo = $this->input->get('escala');
            $estacao       = $this->input->get('estacao');

            if ($estacoes)
            {
                $filtros->setEstacoes($estacoes);
            }
            else
            {
                if (!$podeVerTodasAsEstacoes)
                {
                    $this->EstacoesModel->filtrarEstacoesComAcesso('estacao_id');
                    /*  $imploded = implode(',', $result);
                      $this->db->where_in('estacao_id', explode(',',$imploded));
                      $filtros->setEstacoes(explode(',',$imploded)); */
                }
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

            $filtros->setEstacoes($estacoes);
            // Obter e exibir o conteúdo da variável $leituras
            $csvData = $this->objLeituraModel->exportarLeiturasParaCSV($filtros);

            $filename = 'leituras.csv';
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="' . $filename . '"');

            $output = fopen('php://output', 'w');

            $separador = ';';

            foreach ($csvData as $row)
            {
                fputcsv($output, $row, $separador);
            }

            fclose($output);
            exit;
        }
        else
        {
            $leituras = [];
        }

        $estacoes                       = $this->EstacoesModel->estacoesComAcesso;
        // $imploded = implode(',', $estacoes);
        $variaveisView['estacoes']      = $this->EstacoesModel->getEstacoes(false, $this->EstacoesModel->estacoesComAcesso);
        $variaveisView['titulo_pagina'] = 'Exportar Leituras';
        $variaveisView['leituras']      = $leituras;

        $this->loadSmartyView('ExportarLeituras/index', $variaveisView);
    }
}
