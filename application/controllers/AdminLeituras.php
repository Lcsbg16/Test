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
        $crud->display_as('datahora', 'Data/hora');
        $crud->display_as('estacao_id', 'Estação');
        $crud->callback_column('temperatura', array($this, 'adicionarGraus'));
        $crud->callback_column('umidade_ar', array($this, 'adicionarPorcentagem'));
        $crud->callback_column('velocidade_vento', array($this, 'adicionarKm'));
        $crud->callback_column('volume_chuva', array($this, 'adicionarMm'));
        $crud->callback_column('volume_acc_chuva', array($this, 'adicionarMm'));
        $this->_crud_output($crud);

        // Tipos de campos
        // Configurações da listagems
        //$crud->columns('descricao', 'bairro_id');
        $crud->unset_add();
        $crud->unset_edit();
        $crud->unset_delete();

        // Relacionamentos
        $crud->set_relation('estacao_id', 'estacao', 'identificador');

        $this->_crud_output($crud);
    }
    public function adicionarGraus($value, $row)
    {
        return $value . ' ºC';
    }

    public function adicionarPorcentagem($value, $row)
    {
        return $value . ' %';
    }

    public function adicionarKm($value, $row)
    {
        return $value . ' km/h';
    }

    public function adicionarMm($value, $row)
    {
        return $value . ' mm';
    }


}
