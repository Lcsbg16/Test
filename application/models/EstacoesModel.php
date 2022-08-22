<?php

require_once 'BaseModel.php';

class EstacoesModel extends BaseModel
{

    public function getEstacao($id)
    {
        $estacao = $this->db->where('id', $id)
                ->get('estacao')
                ->row_array();

        return $estacao;
    }

    public function getEstacoes()
    {
        return $this->db->order_by('descricao')
                        ->get('estacao')
                        ->result_array();
    }

}
