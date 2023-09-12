<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once 'BaseCrudController.php';

class AdminLeituras extends BaseCrudController
{

    public function index()
    {


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
        $crud->display_as('velocidade_vento', 'Velocidade do Vento (m/s)');
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

        $this->_crud_output($crud);
    }

    public function getEstatisticasLeiturasJson() //Gerencia dados do Gráfico
    {
        $estacao     = $this->input->post('estacao_selecionada'); // estação selecionada
        $dataInicial = $this->input->post('data_inicial'); // data inicial
        $dataFinal   = $this->input->post('data_final'); // data final
        $escala      = $this->input->post('escala'); // escala escolhida
        $tipo_dados  = $this->input->post('tipo_dados'); // tipo de dados


        $this->load->model('LeiturasModel');

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
        $leituras = $this->LeiturasModel->getAllLeituras();
    
        if (!empty($leituras)) 
        {
            $filename = 'leituras.csv';
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            
            $output = fopen('php://output', 'w');

            $header = array(
                'id',
                'datahora',
                'identificador',
                'descricao',
                'temperatura',
                'umidade_ar',
                'velocidade_vento',
                'direcao_vento',
                'volume_chuva',
                'datahora_cadastro'
            );
            
            fputcsv($output, $header);

            foreach ($leituras as $leitura) 
            {
                fputcsv($output, $leitura);
            }
    
            fclose($output);
            exit;
        }
        else 
        {
            echo 'Não há dados de leituras para serem exportar.';
        }
    }

}
