<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once 'BaseCrudController.php';

class AdminEstacoes extends BaseCrudController
{

    public function __construct()
    {
        $retorno = parent::__construct();

        $this->load->model('EstacoesModel');

        return $retorno;
    }

    public function index()
    {



        $crud = new AppGroceryCRUD();

        // Configurações gerais do cadastro
        $crud->set_theme(self::DEFAULT_CRUD_THEME);
        $crud->set_table('estacao');
        $crud->set_subject('Estações');

        // Validações
        $crud->required_fields('identificador', 'descricao', 'tipo');
        $crud->unique_fields('identificador');

        // Nomes dos campos
        $crud->display_as('endereco_id', 'Local');
        $crud->display_as('descricao', 'Descrição');
        $crud->display_as('_grupos', 'Grupos de Usuários com Acesso');
        $crud->display_as('_coordenadas', 'Coordenadas');
        $crud->display_as('endereco', 'Endereço Completo');
        $crud->display_as('_usuarios', 'Usuários com Acesso');
        $crud->display_as('obs', 'Observação');
        $crud->display_as('weathercloud_api_id', 'Wheather Cloud id');
        $crud->display_as('weathercloud_api_key', 'Wheather Cloud key');
        $crud->display_as('weathercloud_api_id', 'WeatherCloud - API ID');
        $crud->display_as('weathercloud_api_key', 'WeatherCloud - API Key');
        $crud->display_as('weathercom_station_id', 'Weather.com - Station Id');
        $crud->display_as('weathercom_api_key', 'Weather.com - API Key');
        $crud->display_as('foto_estacao', 'Foto da Estação');
        // Campos
        $crud->fields('identificador', 'tipo', 'descricao', 'foto_estacao', 'endereco', '_coordenadas', 'ativa', '_usuarios', '_grupos', 'obs', 'latitude', 'longitude', 'weathercloud_api_id', 'weathercloud_api_key', 'weathercom_station_id', 'weathercom_api_key');

        // Tipos de campos
        $crud->field_type('ativa', 'true_false', ['Inativa', 'Ativa']);
        $crud->field_type('latitude', 'invisible');
        $crud->field_type('longitude', 'invisible');
        $crud->set_field_upload('foto_estacao', 'assets/uploads/estacao');
        $crud->unset_texteditor('obs');

        // Callbacks de campo
        $crud->callback_field('_coordenadas', array($this, 'callbackFieldCoordenadas'));

        // Filtros
        $this->adicionaFiltroAcessoEstacao($crud, '`estacao`.`id`', true);

        // Relacionamentos
        $crud->set_relation('endereco_id', 'endereco', 'descricao');
        $crud->set_relation_n_n('_grupos', 'grupo_acessa_estacao', 'grupo', 'estacao_id', 'grupo_id', 'nome');
        $crud->set_relation_n_n('_usuarios', 'usuario_acessa_estacao', 'usuario', 'estacao_id', 'usuario_id', 'nome', 'ordem');

        // Configurações da listagem
        $crud->columns('identificador', 'descricao', 'endereco', 'ativa');

        // Callbacks de processamento
        $crud->callback_before_insert(array($this, 'callbackBeforeProcess'));
        $crud->callback_before_update(array($this, 'callbackBeforeProcess'));
       
        $this->_adicionarCallbacksDePosProcessamentoPadrao($crud);
        $this->_crud_output($crud);
    }

    public function callbackFieldCoordenadas($value = '', $primaryKey = NULL)
    {

        if ($primaryKey)
        {
            $estacao   = $this->EstacoesModel->getEstacao($primaryKey);
            $latitude  = $estacao['latitude'];
            $longitude = $estacao['longitude'];
        }
        else
        {
            $latitude  = '';
            $longitude = '';
        }

        return $this->loadSmartyView('campos/coordenadas', ['latitude' => $latitude, 'longitude' => $longitude], true);
    }

    public function callbackBeforeProcess($postArray, $primaryKey = NULL)
    {

        $usuario                  = $this->LoginModel->getDadosUsuarioLogado();
        $postArray['_usuarios'][] = $usuario['id'];

        $postArray['latitude']  = $this->input->post('latitude') ? strtr($this->input->post('latitude'), ['.' => '', ',' => '.']) : NULL;
        $postArray['longitude'] = $this->input->post('longitude') ? strtr($this->input->post('longitude'), ['.' => '', ',' => '.']) : NULL;

        return $postArray;
    }
}
