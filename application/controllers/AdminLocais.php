<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once 'BaseCrudController.php';

/**
 * Controller do cadastro de locais
 */
class AdminLocais extends BaseCrudController
{

    public function index()
    {


        $crud = new AppGroceryCRUD();

        // Configurações gerais do cadastro
        $crud->set_theme(self::DEFAULT_CRUD_THEME);
        $crud->set_table('endereco');
        $crud->set_subject('Locais');

        // Validações
        $crud->required_fields('descricao', 'bairro_id');

        // Nomes dos campos
        $crud->display_as('descricao', 'Descrição');
        $crud->display_as('numero', 'Número');
        $crud->display_as('cep', 'CEP');
        $crud->display_as('bairro_id', 'Bairro');       
        $crud->display_as('_grupos', 'Grupos de Usuários com Acesso');

        // Tipos de campos
        // Configurações da listagems
        $crud->columns('descricao', 'bairro_id');

        // Relacionamentos
        $crud->set_relation('bairro_id', 'bairro', 'nome');

        // Relacionamentos
        $crud->set_relation_n_n('_grupos', 'grupo_acessa_endereco', 'grupo', 'endereco_id', 'grupo_id', 'nome');

        $this->_crud_output($crud);
    }

}
