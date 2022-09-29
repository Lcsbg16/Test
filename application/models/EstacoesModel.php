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

    public function getEstacoesGeoJson($camada = NULL)
    {
        $estacoesBD = $this->getEstacoes();

        $estacoes = [];
        $cores    = ['#4DB600', '#FF0000', '#FFAA00', '#FCFF22', '#D200DF'];
        foreach ($estacoesBD as $eAtual)
        {
            if ($eAtual['latitude'] && $eAtual['longitude'])
            {
                $estacoes[] = [
                    'type'       => 'Feature',
                    'geometry'   => [
                        'type'        => 'Point',
                        'coordinates' => [
                            floatval($eAtual['longitude']),
                            floatval($eAtual['latitude'])
                        ]
                    ],
                    'properties' => [
                        'estacao'       => $eAtual,
                        'ultimaLeitura' => [],
                        'camada'        => [
                            'cor' => $cores[array_rand($cores)]
                        ]
                    ],
                    'id'         => $eAtual['id']
                ];
            }
        }
        $geojson = ['type' => 'FeatureCollection', 'features' => $estacoes];

        return $geojson;
    }

}
