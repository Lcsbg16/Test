<?php

require_once 'BaseModel.php';

class EstacoesModel extends BaseModel
{

    public function contaQtdeEstacoesPorUltimoTipoEvento($tipo_evento_id, $somenteAtivas = true)
    {
        $this->db->from('estacao e')
                ->select('
                            (
                                SELECT
                                    tipo_evento_id
                                FROM
                                    evento ev
                                WHERE
                                    ev.estacao_id = e.id
                                ORDER BY
                                    datahora DESC LIMIT 1
                            ) as ultimo_tipo_evento_id
                ')
                ->having('ultimo_tipo_evento_id', $tipo_evento_id);

        if ($somenteAtivas)
        {
            $this->db->where('ativa', 1);
        }

        return $this->db->count_all_results();
    }

    public function getQtdeEstacoesOnline()
    {
        return $this->contaQtdeEstacoesPorUltimoTipoEvento(2);
    }

    public function getQtdeEstacoesOffline()
    {
        return $this->contaQtdeEstacoesPorUltimoTipoEvento(1);
    }

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

    public function getEstacoesGeoJson($camada = NULL, $atividade, $ids)
    {
        $estacoesBD = $this->getEstacoes($atividade, $ids); //getEstações a partir dos ids

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

    public function monitorarEstacao($intervaloTempo)
    {
        $estacoes = $this->getEstacoes(true); //variavel para armazenar todas as estações Ativas
        $eventos = [];
        foreach ($estacoes as $estacao)
        {
            $ultimaLeitura = $this->getUltimaLeitura($estacao['id']);
            $ultimoEvento  = $this->getUltimoEvento($estacao['id']);

            if (!isset($ultimaLeitura['datahora_cadastro']) || strtotime($ultimaLeitura['datahora_cadastro']) < strtotime('-' . $intervaloTempo . ' minutes')) //Verificando se a estação esta sem enviar leituras.
            {
                if (!isset($ultimoEvento['tipo_evento_id']) || $ultimoEvento['tipo_evento_id'] != 1) //Verificando se o evento anterior também não é down
                {
                    $this->inserirEvento($estacao['id'], 1); //Inserindo na tabela evento que a estação esta offline
                    $eventos[] = [
                        "estacao_id" => $estacao['id'],
                        "tipo_evento_id" => 1,
                        "mensagem" => 'offline'
                    ];
                }
            }
            else
            {
                if (!isset($ultimoEvento['tipo_evento_id']) || $ultimoEvento['tipo_evento_id'] != 2) //Verificando se o evento anterior também não é up
                {
                    $this->inserirEvento($estacao['id'], 2); //Inserindo na tabela evento que a estação esta online
                    $eventos[] = [
                        "estacao_id" => $estacao['id'],
                        "tipo_evento_id" => 2,
                        "mensagem" => 'online'
                    ];
                }
            }
        }
        return $eventos;
    }
    
    public function enviarEmailEvento($nomeEstacao, $evento)
    {
        $this->load->library('EmailUtil');
        $adminEmail = 'lbguimaraes16@gmail.com';
        $assunto = "Evento de Estação: $nomeEstacao $evento";
        $destinatario = $adminEmail;
        $mensagem = "A estação $nomeEstacao está $evento.";
        $remetente = 'lucasbarbosaguimaraes2016@gmail.com';
       
        $this->emailutil->enviarEmail($assunto, $destinatario, $mensagem, $remetente);
    }


    private function getUltimaLeitura($estacaoId)
    {
        return $this->db->where('estacao_id', $estacaoId)
                        ->order_by('datahora_cadastro', 'desc')
                        ->limit(1)
                        ->get('leitura')
                        ->row_array();
    }

    private function getUltimoEvento($estacaoId)
    {
        return $this->db->where('estacao_id', $estacaoId)
                        ->order_by('datahora', 'desc')
                        ->limit(1)
                        ->get('evento')
                        ->row_array();
    }

    private function inserirEvento($estacaoId, $tipoEventoId)
    {
        $data = [
            'estacao_id'     => $estacaoId,
            'datahora'       => date('Y-m-d H:i:s'),
            'tipo_evento_id' => $tipoEventoId
        ];
        $this->db->insert('evento', $data);
    }



    public function getEventos($limit)
    {
        $this->db->select('evento.id, evento.datahora, evento.tipo_evento_id, estacao.id AS estacao_id, estacao.descricao AS estacao_descricao')
                ->from('evento')
                ->join('estacao', 'evento.estacao_id = estacao.id')
                ->order_by('evento.datahora', 'desc')
                ->limit($limit);

        $query = $this->db->get();
        return $query->result_array();
    }
}
