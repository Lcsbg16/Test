<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once 'BaseCrudController.php';

class AdminLeituras extends BaseCrudController
{

    public function index()
    {
        $this->load->model('LeiturasModel');
        $crud = new AppGroceryCRUD();

        // Configurações gerais do cadastro
        $crud->set_theme(self::DEFAULT_CRUD_THEME);
        $crud->set_table('leitura');
        $crud->set_subject('Leituras');

        // Validações
        // Nomes dos campos
        $crud->display_as('datahora', 'Data/hora');
        $crud->display_as('estacao_id', 'Estação');
        $crud->display_as('temperatura', 'Temperatura (&#176;C)');
        $crud->display_as('umidade_ar', 'Umidade do Ar (%)');
        $crud->display_as('velocidade_vento', 'Velocidade do Vento (km/h)');
        $crud->display_as('volume_chuva', 'Volume da Chuva (mm³)');
        $crud->display_as('volume_acc_chuva', 'Volume Acumulado de Chuva (mm&sup3;)');
        $crud->display_as('dir_vento', 'Direção do Vento (&#176;)');

        $crud->set_read_fields('datahora', 'estacao_id', 'temperatura', 'umidade_ar', 'velocidade_vento', 'dir_vento', 'volume_chuva');

        // Tipos de campos
        // Configurações da listagems
        $crud->columns('datahora', 'estacao_id', 'temperatura', 'umidade_ar', 'velocidade_vento', 'dir_vento', 'volume_chuva');
        $crud->unset_add();
        $crud->unset_edit();
        $crud->unset_delete();

        // Relacionamentos
        $crud->set_relation('estacao_id', 'estacao', 'identificador');

        $crud->callback_column('velocidade_vento', array($this, '_callback_converterVelocidadeVento'));
        $this->_crud_output($crud);
    }

    public function _callback_converterVelocidadeVento($value, $row)
    {
        $this->load->model('LeiturasModel');
        
        $velocidade_ms = $row->velocidade_vento;

        $velocidade_kmh = $velocidade_kmh = Conversao::velVentoParakmH($velocidade_ms);

        return $velocidade_kmh;
    }

    public function getEstatisticasLeiturasJson() //Gerencia dados do Gráfico
    {
        $this->load->model('LeiturasModel');

        $estacao     = $this->input->post('estacao_selecionada'); // estação selecionada
        $dataInicial = $this->input->post('data_inicial'); // data inicial
        $dataFinal   = $this->input->post('data_final'); // data final
        $escala      = $this->input->post('escala'); // escala escolhida
        $tipo_dados  = $this->input->post('tipo_dados'); // tipo de dados

        $filtros  = new FiltrosLeitura();
        $filtros->setEstacoes($estacao);
        $filtros->setDataInicial($dataInicial);
        $filtros->setDataFinal($dataFinal);
        $filtros->setEscala(constant("FiltrosLeitura::$escala"));
        $filtros->setTipoInformacao(constant("FiltrosLeitura::$tipo_dados"));
        $filtros->setDirecao(FiltrosLeitura::DIRECAO_ASC);
        $leituras = $this->LeiturasModel->calcularEstatisticasPorPeriodo($filtros);

        $this->jsonOutput($leituras);
    }

    public function getUltimaLeituraRegistrada()
    {
        $estacao    = $this->input->post('estacao_selecionada'); // estação selecionada
        $tipo_dados = $this->input->post('tipo_dados'); // tipo de dados

        $this->load->model('LeiturasModel');
        $filtros  = new FiltrosLeitura();
        $filtros->setEstacoes($estacao);
        $filtros->setTipoInformacao(constant("FiltrosLeitura::$tipo_dados"));
        $leituras = $this->LeiturasModel->getUltimaLeituraRegistrada($filtros);
        $this->jsonOutput($leituras);
    }

    public function exportarLeitura()
    {
        $this->load->model('LeiturasModel');
        $this->load->model('EstacoesModel');
        $variaveisView = [];
        $filtros       = new FiltrosLeitura();
        if ($this->input->get('escala'))
        {
            $estacoes      = $this->input->get('estacoes');
            $dataInicial   = $this->input->get('dataInicial');
            $dataFinal     = $this->input->get('dataFinal');
            $colunaPeriodo = $this->input->get('escala');

            $classe        = new ReflectionClass('FiltrosLeitura');
            $colunaPeriodo = $classe->getConstant($colunaPeriodo);

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
            
            $leituras = $this->LeiturasModel->getLeiturasPorEscala($filtros);

            $filename = 'leituras.csv';
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
    
            $output = fopen('php://output', 'w');
    
            $separador = ';';
    
            $header = array(
                'id',
                'datahora',
                'estacao_identificador',
                'estacao_descricao',
                'estacao_endereco',
                'estacao_latitude',
                'estacao_longitude',
                'temperatura',
                'umidade_ar',
                'velocidade_vento',
                'direcao_vento',
                'volume_chuva',
                'datahora_cadastro'
                );
    
    
            fputcsv($output, $header, $separador);
    
            foreach ($leituras as $leitura)
            {
                // Converter velocidade do vento para km/h
                $leitura['velocidade_vento'] = Conversao::velVentoParakmH($leitura['velocidade_vento']);

                //Substituindo o separador decimal de . para ,
                $leitura['velocidade_vento'] = str_replace('.', ',', $leitura['velocidade_vento']);
                $leitura['temperatura'] = str_replace('.', ',', $leitura['temperatura']);
                $leitura['umidade_ar'] = str_replace('.', ',', $leitura['umidade_ar']);
                $leitura['volume_chuva'] = str_replace('.', ',', $leitura['volume_chuva']);
                fputcsv($output, $leitura, $separador);
            }
    
            fclose($output);
            exit;
        }
        else
        {
            $leituras = [];
        }
        $variaveisView['estacoes']      = $this->EstacoesModel->getEstacoes();
        $variaveisView['titulo_pagina'] = 'Exportar Leituras';
        $variaveisView['leituras']      = $leituras;

        $this->loadSmartyView('ExportarLeituras/index', $variaveisView);

    }
}
