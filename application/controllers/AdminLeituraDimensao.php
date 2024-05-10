<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once 'BaseCrudController.php';

class AdminLeituraDimensao extends BaseCrudController
{

    protected $permissaoAcesso = 'ADMIN_LEITURA_DIMENSAO';

    public function index()
    {


        $crud = new AppGroceryCRUD();

        // Configurações gerais do cadastro
        $crud->set_theme(self::DEFAULT_CRUD_THEME);
        $crud->set_table('leitura_dimensao');
        $crud->set_subject('Leitura Dimensão');

        // Validações
        $crud->required_fields('tag');

        // Nomes dos campos
        $crud->display_as('tipo_dado', 'Tipo do dado');
        $crud->display_as('unidade_medida_id', 'Unidade de medida');

        // Relacionamentos
        $crud->set_relation('unidade_medida_id', 'unidade_medida', 'unidade');

        $this->_crud_output($crud);
    }
}
