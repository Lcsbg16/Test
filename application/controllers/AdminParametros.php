<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once 'BaseCrudController.php';

/**
 * Controller do cadastro de locais
 */
class AdminParametros extends BaseCrudController
{

    protected $permissaoAcesso = 'ADMIN_PARAMETROS';

    public function index()
    {


        $crud = new AppGroceryCRUD();

        $crud->set_theme(self::DEFAULT_CRUD_THEME);
        $crud->set_table('parametros');
        $crud->set_subject('Parametros');
        $crud->unset_add();
        $crud->unset_delete();

        $crud->display_as('nome', 'Nome');
        $crud->display_as('titulo', 'Título');
        $crud->display_as('descricao', 'Descrição');
        $crud->display_as('valor', 'Valor');

        $crud->columns('titulo', 'valor');
        $crud->field_type('nome', 'readonly');
        $crud->field_type('titulo', 'readonly');
        $crud->field_type('descricao', 'readonly');
        $crud->field_type('valor', 'string');

        $this->_adicionarCallbacksDePosProcessamentoPadrao($crud);
        $this->_crud_output($crud);
    }
}
