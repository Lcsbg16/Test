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

    public function getEstacoes($somenteAtivas = FALSE, $ids = array()) //alteração para aceitar um array de estações a ser buscadas também
    {
        $this->db->order_by('descricao');
        if ($somenteAtivas)
        {
            $this->db->where('ativa', true);
        }
        if (!empty($ids))
    {
        $this->db->where_in('id', $ids); //se houver estaçõesy
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

    public function getEstacoesGeoJson($camada = NULL, $id_s)
    {
        $estacoesBD = $this->getEstacoes(null,$id_s); //getEstações a partir dos ids

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
                        'ultimaLeitura' => [
                            'datahora'           => date('Y-m-d H:i:s'),
                            'datahora_formatada' => date('d/m/Y H:i:s'),
                            'temperatura'        => 30.,
                            'umidade_ar'         => 60.,
                            'velocidade_vento'   => 30.,
                            'dir_vento'          => 'NE',
                            'volume_chuva'       => 0.5,
                            'volume_acc_chuva'   => 50.
                        ],
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
