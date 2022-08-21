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
        $crud->required_fields('identificador', 'descricao');
        $crud->unique_fields('identificador');

        // Nomes dos campos
        $crud->display_as('endereco_id', 'Local');
        $crud->display_as('descricao', 'Descrição');
        $crud->display_as('_grupos', 'Grupos de Usuários com Acesso (Adicional ao controle de acesso por locais)');
        $crud->display_as('_coordenadas', 'Coordenadas');
        $crud->display_as('endereco', 'Endereço Completo');
        $crud->display_as('_usuarios', 'Usuários com Acesso');

        // Campos
        $crud->fields('identificador', 'descricao', 'endereco', '_coordenadas', 'ativa', '_usuarios', '_grupos', 'obs', 'latitude', 'longitude');

        // Tipos de campos
        $crud->field_type('ativa', 'true_false', ['Inativa', 'Ativa']);
        $crud->field_type('latitude', 'invisible');
        $crud->field_type('longitude', 'invisible');

        $crud->unset_texteditor('obs');

        // Callbacks de campo
        $crud->callback_field('_coordenadas', array($this, 'callbackFieldCoordenadas'));

        // Relacionamentos
        $crud->set_relation('endereco_id', 'endereco', 'descricao');
        $crud->set_relation_n_n('_grupos', 'grupo_acessa_estacao', 'grupo', 'estacao_id', 'grupo_id', 'nome');
        $crud->set_relation_n_n('_usuarios', 'usuario_acessa_estacao', 'usuario', 'estacao_id', 'usuario_id', 'nome', 'ordem');

        // Configurações da listagem
        $crud->columns('identificador', 'descricao', 'endereco', 'ativa');

        // Callbacks de processamento
        $crud->callback_before_insert(array($this, 'callbackBeforeProcess'));
        $crud->callback_before_update(array($this, 'callbackBeforeProcess'));

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

    public function callbackBeforeProcess($postArray, $primaryKey)
    {
        $postArray['latitude']  = $this->input->post('latitude') ? $this->input->post('latitude') : NULL;
        $postArray['longitude'] = $this->input->post('longitude') ? $this->input->post('longitude') : NULL;

        return $postArray;
    }

}
