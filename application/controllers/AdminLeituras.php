<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once 'BaseCrudController.php';

class AdminLeituras extends BaseCrudController
{

    public function index()
    {
       // $this->load->model('LeiturasModel');
        $this->load->model('EstacoesModel');

        $crud = new AppGroceryCRUD();

        // Configurações gerais do cadastro
        $crud->set_theme(self::DEFAULT_CRUD_THEME);
        $crud->set_table('leitura');
        $crud->set_subject('Leituras');

        // Filtragem data e hora
        //var_dump($this->input->get('data_inicial'));
        // var_dump($this->input->get('data_final'));

        $data_inicial = $this->input->get('data_inicial');
        $data_final   = $this->input->get('data_final');

        // Construção da condição de filtro
        $where = array();

        // $this->session->set_userdata('leituras_filtro_data_inicial', null);
        // $this->session->set_userdata('leituras_filtro_data_final', null);
        // var_dump($data_inicial);
        // var_dump($data_final);


        if (isset($data_inicial))
        {
            $this->session->set_userdata('leituras_filtro_data_inicial', $data_inicial);
        }

        if (isset($data_final))
        {
            $this->session->set_userdata('leituras_filtro_data_final', $data_final);
        }

        if (!empty($this->session->leituras_filtro_data_inicial))
        {
            $data_inicial = date('Y-m-d H:i:s', strtotime($this->session->leituras_filtro_data_inicial));
            // var_dump($data_inicial);

            $crud->where('datahora >=', $data_inicial);
        }

        if (!empty($this->session->leituras_filtro_data_final))
        {
            // Adicionando 1 minuto para incluir todos os registros até o final do dia selecionado
            $data_final = date('Y-m-d H:i:s', strtotime($this->session->leituras_filtro_data_final));
            // var_dump($data_final);
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

        if (!$this->checaPermissaoUsuarioLogado('ADMIN_EXCLUIR_LEITURAS'))
        {
            $crud->unset_delete();
        }

        // Relacionamentos
        $crud->set_relation('estacao_id', 'estacao', 'identificador');

        // Filtros
        $this->adicionaFiltroAcessoEstacao($crud, '`estacao_id`', false);

        $crud->callback_column('velocidade_vento', array($this, '_callback_converterVelocidadeVento'));

        $this->_adicionarCallbacksDePosProcessamentoPadrao($crud);
        $this->_crud_output($crud);
    }

    protected function _loadDefaultView($output)
    {
        //....
        $conteudo_formulario = $this->loadSmartyView('AdminLeituras/index', [], true);

        $output->output = $conteudo_formulario . $output->output;

        return parent::_loadDefaultView($output);
    }

    public function _callback_converterVelocidadeVento($value, $row)
    {
        //$this->load->model('LeiturasModel');

        $velocidade_ms = $row->velocidade_vento;

        $velocidade_kmh = Conversao::metroPorSegundoParaKmPorHora($velocidade_ms);

        return $velocidade_kmh;
    }

    public function getLeiturasPorIntervaloDeDatas($dataInicial, $dataFinal)
    {
        $this->db->from('leitura');

        if ($dataInicial && $dataFinal)
        {
            $this->db->where('datahora >=', $dataInicial);
            $this->db->where('datahora <=', $dataFinal);
        }

        $query = $this->db->get();

        return $query->result();
    }
}
