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

    public function testCalcularEstatiscas()
    {
        $this->load->model('LeiturasModel');

        $filtros = new FiltrosLeitura();
        $filtros->setEstacoes(['2']);
        $filtros->setDataInicial('2023-03-17');
        $filtros->setDataFinal('2023-03-21');
        $filtros->setEscala(FiltrosLeitura::ESCALA_MINUTO);
        $filtros->setTipoInformacao(FiltrosLeitura::TIPO_VELOCIDADE_VENTO);
        $filtros->setDirecao(FiltrosLeitura::DIRECAO_ASC);
        $leituras = $this->LeiturasModel->calcularEstatisticasPorPeriodo($filtros);
        
        //var_dump($leituras);
    }

    public function testUltimasLeituras()
    {
        $this->load->model('LeiturasModel');

        $filtros = new FiltrosLeitura();
        $filtros->setEstacoes(['2']);
        $filtros->setDataInicial('2023-04-19');
        //$filtros->setDataFinal('2023-03-21');
        //$filtros->setTipoInformacao(FiltrosLeitura::TIPO_VELOCIDADE_VENTO);
        $leituras = $this->LeiturasModel->getUltimasLeituras($filtros);
        
        var_dump($leituras);
    }

    
}
