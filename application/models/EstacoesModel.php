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

}
