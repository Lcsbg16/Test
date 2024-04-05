<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once 'BaseCrudController.php';

class AdminLeituras extends BaseCrudController
{

    public $smarty; // Torna a propriedade pública para acessá-la em toda a classe

    public function __construct()
    {
        parent::__construct();
        $this->load->library('smarty'); // Carrega a biblioteca Smarty
        $this->smarty = new Smarty(); // Inicializa a instância do Smarty
    }   

    public function index()
    {
        $this->load->model('LeiturasModel');
        $this->load->model('EstacoesModel');
        $this->load->library('session');


        $crud = new AppGroceryCRUD();

        // Configurações gerais do cadastro
        $crud->set_theme(self::DEFAULT_CRUD_THEME);
        $crud->set_table('leitura');
        $crud->set_subject('Leituras');

        // Filtragem data e hora
        //var_dump($this->input->get('data_inicial'));
       // var_dump($this->input->get('data_final'));

        $data_inicial = $this->input->get('data_inicial');
        $data_final = $this->input->get('data_final');

        // Construção da condição de filtro
        $where = array();

        // $this->session->set_userdata('leituras_filtro_data_inicial', null);
        // $this->session->set_userdata('leituras_filtro_data_final', null);   
        // var_dump($data_inicial);
        // var_dump($data_final);
   

        if (isset($data_inicial)) {   
            $this->session->set_userdata('leituras_filtro_data_inicial', $data_inicial);
        }
        
        if (isset($data_final)) {       
            $this->session->set_userdata('leituras_filtro_data_final', $data_final);
        }
        
        if (!empty($this->session->leituras_filtro_data_inicial)) {   
            $data_inicial = date('Y-m-d H:i:s', strtotime($this->session->leituras_filtro_data_inicial));
            var_dump($data_inicial);

            $crud->where('datahora >=', $data_inicial);
        }
        
        if (!empty($this->session->leituras_filtro_data_final)) {   
            // Adicionando 1 minuto para incluir todos os registros até o final do dia selecionado
            $data_final = date('Y-m-d H:i:s', strtotime($this->session->leituras_filtro_data_final ));
            var_dump($data_final);
            $crud->where('datahora <=', $data_final);
        }

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

          // Filtros
          $this->adicionaFiltroAcessoEstacao($crud,'`estacao_id`');

        $crud->callback_column('velocidade_vento', array($this, '_callback_converterVelocidadeVento'));

        $this->_adicionarCallbacksDePosProcessamentoPadrao($crud);
        $this->_crud_output($crud);

    }

    protected function _loadDefaultView($output)
    {
        //....
        $conteudo_formulario = $this->loadSmartyView('AdminLeituras/index',[],true);

        $output->output = $conteudo_formulario . $output->output;

        return parent::_loadDefaultView($output);
    }                                                                                               

    public function _callback_converterVelocidadeVento($value, $row)
    {
        $this->load->model('LeiturasModel');

        $velocidade_ms = $row->velocidade_vento;

        $velocidade_kmh = Conversao::velVentoParakmH($velocidade_ms);

        return $velocidade_kmh;
    }

    public function getLeiturasPorIntervaloDeDatas($dataInicial, $dataFinal)
    {
        $this->db->from('leitura');

        if ($dataInicial && $dataFinal) {
            $this->db->where('datahora >=', $dataInicial);
            $this->db->where('datahora <=', $dataFinal);
        }

        $query = $this->db->get();

        return $query->result();
    }

    public function getEstatisticasLeiturasJson() //Gerencia dados do Gráfico
    {
        $this->load->model('LeiturasModel');

        $estacao     = $this->input->post('estacao_selecionada');
        $dataInicial = $this->input->post('data_inicial'); 
        $dataFinal   = $this->input->post('data_final');
        $escala      = $this->input->post('escala'); 
        $tipo_dados  = $this->input->post('tipo_dados'); 

        $filtros  = new FiltrosLeitura();
        $filtros->setEstacoes($estacao);
        $filtros->setDataInicial($dataInicial);
        $filtros->setDataFinal($dataFinal);
        $filtros->setEscala(constant("FiltrosLeitura::$escala"));
        $filtros->setTipoInformacao(constant("FiltrosLeitura::$tipo_dados"));
        $filtros->setDirecao(FiltrosLeitura::DIRECAO_ASC);
        
        $leituras = $this->LeiturasModel->calcularEstatisticasPorPeriodo($filtros);
        if ($dataInicial && $dataFinal) 
        {
            $leituras = $this->LeiturasModel->getLeiturasPorIntervaloDeDatas($dataInicial, $dataFinal);
        } 
        else 
        {
            $leituras = [];
        }

        $leituras = $this->LeiturasModel->getLeiturasPorIntervaloDeDatas($dataInicial, $dataFinal);

        $this->jsonOutput($leituras);
    }


    public function getUltimaLeituraRegistrada()
    {
        $estacao    = $this->input->post('estacao_selecionada'); 
        $tipo_dados = $this->input->post('tipo_dados'); 

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
        $result  = $this->EstacoesModel->getEstacoesComAcessoPorUsuario();

        $variaveisView = [];
        $filtros = new FiltrosLeitura();
    
        if ($this->input->get('escala')) {
            $estacoes = $this->input->get('estacoes');
            $dataInicial = $this->input->get('data_inicial');
            $dataFinal = $this->input->get('data_final');
            $colunaPeriodo = $this->input->get('escala');
            $estacao = $this->input->get('estacao');
    
            if ($estacoes) {
                $filtros->setEstacoes($estacoes);
            }else{
                $imploded = implode(',', array_map('array_pop', $result));
                $this->db->where_in('estacao_id', explode(',',$imploded));
                $filtros->setEstacoes(explode(',',$imploded));
            }
    
            if ($dataInicial) {
                $filtros->setDataInicial($dataInicial);
            }
    
            if ($dataFinal) {
                $filtros->setDataFinal($dataFinal);
            }
    
            if ($colunaPeriodo) {
                $filtros->setEscala($colunaPeriodo);
            }
    
            $filtros->setEstacoes($estacao);
    
            // Obter e exibir o conteúdo da variável $leituras
            $csvData = $this->LeiturasModel->exportarLeiturasParaCSV($filtros);
    
            $filename = 'leituras.csv';
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
    
            $output = fopen('php://output', 'w');
    
            $separador = ';';
    
            foreach ($csvData as $row) {
                fputcsv($output, $row, $separador);
            }
    
            fclose($output);
            exit;
        } else {
            $leituras = [];
        }

        $estacoes  = $this->EstacoesModel->getEstacoesComAcessoPorUsuario();  
        $imploded = implode(',', array_map('array_pop', $estacoes));
        $variaveisView['estacoes'] = $this->EstacoesModel->getEstacoes(false, explode(',',$imploded));
        $variaveisView['titulo_pagina'] = 'Exportar Leituras';
        $variaveisView['leituras'] = $leituras;
    
        $this->loadSmartyView('ExportarLeituras/index', $variaveisView);
    }

    
}

