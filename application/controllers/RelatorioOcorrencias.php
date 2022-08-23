<?php

require_once 'AdminOcorrencias.php';

class RelatorioOcorrencias extends AdminOcorrencias
{

    public function _crud_output($crud, $view_variables = [])
    {
        $crud->unset_add();
        $crud->set_subject('Ocorrências');

        return parent::_crud_output($crud, $view_variables);
    }

    protected function adicionaFiltros($crud)
    {
        return $crud;
    }

}
