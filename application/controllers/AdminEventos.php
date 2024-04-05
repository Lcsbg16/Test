<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once 'BaseCrudController.php';

/**
 * Controller do cadastro de locais
 */
class AdminEventos extends BaseCrudController
{

    public function index()
    {


        $crud = new AppGroceryCRUD();

        $crud->set_theme(self::DEFAULT_CRUD_THEME);
        $crud->set_table('evento');
        $crud->set_subject('Eventos');

        $crud->required_fields('estacao_id', 'datahora', 'tipo_evento_id');

        $crud->display_as('estacao_id', 'Estação');
        $crud->display_as('datahora', 'Data/Hora');
        $crud->display_as('tipo_evento_id', 'Evento');

        $crud->columns('estacao_id', 'datahora', 'tipo_evento_id');
        $crud->field_type('estacao_id', 'readonly');
        $crud->field_type('datahora', 'readonly');
        $crud->field_type('tipo_evento_id', 'readonly');

        $crud->set_relation('estacao_id', 'estacao', 'identificador');
        $crud->set_relation('tipo_evento_id', 'tipo_evento', 'nome');

          // Filtros
        $this->adicionaFiltroAcessoEstacao($crud,'`estacao_id`');

        $crud->unset_add();
        $crud->unset_delete();
        $crud->unset_edit();
        $crud->unset_clone();
        $crud->order_by('datahora', 'desc');


        $estacao_id = $this->input->get('estacao_id');

        if ($estacao_id !== null && is_numeric($estacao_id)) {
            $this->session->admin_eventos_estacao_id = $estacao_id;

        }
        $crud->where('estacao_id', $this->session->admin_eventos_estacao_id );

        $this->formatar_datahora($crud);

       return $this->_crud_output($crud);
    }

    private function formatar_datahora($crud)
    {
        $crud->callback_column('datahora', function ($value, $row)
        {
            $data_formatada = date('d/m/Y - H:i', strtotime($value));
            return $data_formatada;
        });
    }
}
