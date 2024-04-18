<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once 'BaseCrudController.php';

class AdminGrupos extends BaseCrudController
{

    public function index()
    {


        $crud = new AppGroceryCRUD();

        // Configurações gerais do cadastro
        $crud->set_theme(self::DEFAULT_CRUD_THEME);
        $crud->set_table('grupo');
        $crud->set_subject('Grupos de Usuários');

        // Validações
        $crud->required_fields('nome');
        $crud->unique_fields('nome');

        // Nomes dos campos
        $crud->display_as('_locais', 'Acesso aos Locais');
        $crud->display_as('_estacoes', 'Acesso às Estações');

        // Configurações da listagems
        $crud->columns('nome');

        // Relacionamentos
        //$crud->set_relation_n_n('_locais', 'grupo_acessa_endereco', 'endereco', 'grupo_id', 'endereco_id', 'descricao', 'ordem');
        $crud->set_relation_n_n('_estacoes', 'grupo_acessa_estacao', 'estacao', 'grupo_id', 'estacao_id', '{id} ({descricao})', 'ordem');
     //   $crud->where('dfs = 3');
        $this->_crud_output($crud);
    }

}
