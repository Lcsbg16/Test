<?php

require_once 'BaseModel.php';

class OcorrenciasModel extends BaseModel
{

    public function getOCorrencias($limite = NULL)
    {
        $this->db->order_by('datahora_ocorrido', 'desc')
                ->order_by('to.nome')
                ->from('ocorrencia o')
                ->join('tipo_ocorrencia to', 'o.tipo_ocorrencia_id = to.id')
                ->select('o.*, to.nome as tipo_ocorrencia');

        if ($limite)
        {
            $this->db->limit($limite);
        }

        $ocorrencias = $this->db->get()->result_array();

        foreach ($ocorrencias as $index => $oAtual)
        {
            $ocorrencias[$index]['url'] = base_url('RelatorioOcorrencias/index/read/' . $oAtual['id']);
        }

        return $ocorrencias;
    }

}
