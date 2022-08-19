<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once 'BaseCrudController.php';

class AdminEstacoes extends BaseCrudController
{

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

        // Tipos de campos
        $crud->field_type('ativa', 'true_false', ['Inativa', 'Ativa']);
        $crud->unset_texteditor('obs');

        // Relacionamentos
        $crud->set_relation('endereco_id', 'endereco', 'descricao');

        // Configurações da listagem
        $crud->columns('identificador', 'descricao', 'endereco', 'ativa');

        $this->_crud_output($crud);
    }

}
