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

    public function getEstacoes($somenteAtivas = FALSE)
    {
        $this->db->order_by('descricao');
        if ($somenteAtivas)
        {
            $this->db->where('ativa', true);
        }

        return $this->db->get('estacao')
                        ->result_array();
    }

    public function getContagemEstacoes($somenteAtivas = TRUE)
    {
        if ($somenteAtivas)
        {
            $this->db->where('ativa', TRUE);
        }

        return $this->db->count_all_results('estacao');
    }

}
