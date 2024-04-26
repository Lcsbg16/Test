<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once 'BaseCrudController.php';

class AdminBairros extends BaseCrudController
{

    public function index()
    {


        $crud = new AppGroceryCRUD();

        // Configurações gerais do cadastro
        $crud->set_theme(self::DEFAULT_CRUD_THEME);
        $crud->set_table('bairro');
        $crud->set_subject('Bairros');

        // Validações
        $crud->required_fields('nome', 'cidade_id');

        // Nomes dos campos
        $crud->display_as('cidade_id', 'Cidade');

        // Callbacks de campo
        // Incluir aqui uma callback para o campo de bairro em cascata
        //$crud->callback_edit_field('senha', array($this, 'show_password_field'));
        // Relacionamentos
        $crud->set_relation('cidade_id', 'cidade', 'nome');

        $this->_adicionarCallbacksDePosProcessamentoPadrao($crud);
        $this->_crud_output($crud);
    }

}
