<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once 'BaseCrudController.php';

class AdminLeituras extends BaseCrudController
{

    public function index()
    {


        $crud = new AppGroceryCRUD();

        // Configurações gerais do cadastro
        $crud->set_theme(self::DEFAULT_CRUD_THEME);
        $crud->set_table('leitura');
        $crud->set_subject('Leituras');

        // Validações
        // Nomes dos campos
        $crud->display_as('descricao', 'Descrição');
        $crud->display_as('numero', 'Número');
        $crud->display_as('cep', 'CEP');
        $crud->display_as('bairro_id', 'Bairro');
        $crud->display_as('_grupos', 'Grupos de Usuários com Acesso');

        // Tipos de campos
        // Configurações da listagems
        //$crud->columns('descricao', 'bairro_id');
        $crud->unset_add();
        $crud->unset_edit();
        $crud->unset_delete();

        // Relacionamentos
        $crud->set_relation('estacao_id', 'estacao', 'descricao');

        $this->_crud_output($crud);
    }

}
