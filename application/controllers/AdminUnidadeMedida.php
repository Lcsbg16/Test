<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once 'BaseCrudController.php';

class AdminUnidadeMedida extends BaseCrudController
{

    public function index()
    {


        $crud = new AppGroceryCRUD();

        // Configurações gerais do cadastro
        $crud->set_theme(self::DEFAULT_CRUD_THEME);
        $crud->set_table('unidade_medida');
        $crud->set_subject('Unidade de medida');

        // Validações
        $crud->required_fields('descricao', 'unidade');

        // Nomes dos campos
        $crud->display_as('descricao', 'Descrição');

        $this->_crud_output($crud);
    }

}
