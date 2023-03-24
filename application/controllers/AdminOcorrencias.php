<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once 'BaseCrudController.php';

class AdminOcorrencias extends BaseCrudController
{

    public function index()
    {

        $crud = new AppGroceryCRUD();

        // Configurações gerais do cadastro
        $crud->set_theme(self::DEFAULT_CRUD_THEME);
        $crud->set_table('ocorrencia');

        if ($crud->getStateCategory() == 'create')
        {
            $crud->set_subject('Reportar Ocorrência');
        }
        else
        {
            $crud->set_subject('Minhas Ocorrências');
        }

        // Validações
        $crud->required_fields('tipo_ocorrencia_id', 'datahora_ocorrido', 'endereco', 'descricao');

        // Nomes dos campos
        $crud->display_as('descricao', 'Descrição');
        $crud->display_as('tipo_ocorrencia_id', 'Tipo de Ocorrência');
        $crud->display_as('endereco', 'Endereço');
        $crud->display_as('usuario_id', 'Reportado Por');
        $crud->display_as('datahora_ocorrido', 'Data e Hora do Ocorrido');
        $crud->display_as('datahora_cadastro', 'Data e Hora do Cadastro');

        // Tipos de campos
        $crud->unset_texteditor('descricao');

        if ($crud->getStateCategory() != 'create')
        {
            $crud->field_type('datahora_cadastro', 'readonly');
            $crud->field_type('usuario_id', 'readonly');
        }
        else
        {
            $crud->field_type('datahora_cadastro', 'invisible');
            $crud->field_type('usuario_id', 'invisible');
        }

        // Configurações da listagems
        $crud->columns('tipo_ocorrencia_id', 'datahora_ocorrido', 'endereco', 'descricao');
        $crud->unset_delete();
        $crud->unset_edit();
        $crud->unset_clone();
        $crud->order_by('datahora_ocorrido', 'desc');

        // Relacionamentos
        $crud->set_relation('tipo_ocorrencia_id', 'tipo_ocorrencia', 'nome');
        if ($crud->getStateCategory() != 'create')
        {
            $crud->set_relation('usuario_id', 'usuario', 'nome');
        }

        // Callbacks de processamento
        $crud->callback_before_insert([$this, 'callbackBeforeInsert']);

        // Filtros
        $this->adicionaFiltros($crud);

        $this->_crud_output($crud);
    }

    protected function adicionaFiltros($crud)
    {
        $usuario = $this->getDadosUsuarioLogado();
        $crud->where('usuario_id', $usuario['id']);

        return $crud;
    }

    public function callbackBeforeInsert($postArray)
    {
        $usuario                        = $this->getDadosUsuarioLogado();
        $postArray['usuario_id']        = $usuario['id'];
        $postArray['datahora_cadastro'] = date('Y-m-d H:i:s');

        return $postArray;
    }

}
