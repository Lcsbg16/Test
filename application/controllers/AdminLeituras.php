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

    public function testCaclularEstatiscas() //Gerencia dados do Gráfico 
    {   
        $estacao = $this->input->post('estacao_selecionada'); // estação selecionada
        $dataInicial = $this->input->post('data_inicial'); // data inicial 
        $dataFinal = $this->input->post('data_final'); // data final 
        $escala = $this->input->post('escala'); // escala escolhida 
        $tipo_dados = $this->input->post('tipo_dados'); // tipo de dados  


        $this->load->model('LeiturasModel'); 

        $filtros = new FiltrosLeitura(); //New filtro -> contato com o BD
        $filtros->setEstacoes([$estacao]);
        $filtros->setDataInicial($dataInicial);
        $filtros->setDataFinal($dataFinal);
        $filtros->setEscala(constant("FiltrosLeitura::$escala"));
        $filtros->setTipoInformacao(constant("FiltrosLeitura::$tipo_dados"));
        $filtros->setDirecao(FiltrosLeitura::DIRECAO_ASC);
        $leituras = $this->LeiturasModel->calcularEstatisticasPorPeriodo($filtros);
        print_r(json_encode($leituras));

    }


    public function testUltimasLeituras()
    {      
        $filtros->setEstacoes(['1']);
        $filtros->setDataInicial('2023-04-21');
        $filtros->setDataFinal('2023-04-22');
        $filtros->setTipoInformacao(FiltrosLeitura::TIPO_TEMPERATURA);
        $leituras = $this->LeiturasModel->getUltimasLeituras($filtros);
        
 
    }


    

    
}
