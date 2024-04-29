<?php

require_once 'BaseModel.php';

class EstacoesModel extends BaseModel
{

    public $estacoesComAcesso = array();

    public function __construct()
    {

        parent::__construct();
    }

    public function getArrayEstacoesComAcesso($filtrar = false)
    {
        $parametro_filtro_estacoes = CONTROLE_ACESSO_ESTACAO;

        if ($parametro_filtro_estacoes == "true" OR $filtrar == true)
        {
            $this->estacoesComAcesso = $this->getEstacoesComAcessoPorUsuario();

            if ($this->estacoesComAcesso)
            {
                $imploded                = implode(',', $this->estacoesComAcesso);
                $this->estacoesComAcesso = explode(',', $imploded);
            }
        }
        else
        {
            $this->estacoesComAcesso = array();
        }
    }

    public function filtrarEstacoesComAcesso($fild, $filtrar = false)
    {
        $parametro_filtro_estacoes = CONTROLE_ACESSO_ESTACAO;

        if ($parametro_filtro_estacoes == "true" OR $filtrar == true)
        {
            if (!$this->estacoesComAcesso)
            {
                $this->db->where_in($fild, 'NULL');
            }
            else
            {
                $this->db->where_in($fild, $this->estacoesComAcesso);
            }
        }
    }

    /**
     * Retorna a quantidade de estações cujo último evento registrado é de id informado em $tipo_evento_id
     *
     * @param int $tipo_evento_id Id do tipo de evento a ser considerado na contagem
     * @param bool $somenteAtivas Considerar apenas estações ativas
     * @param array[int] $filtrarTipos Considerar apenas os esses ids de tipo na consulta. Se vazio, considera todos os tipos.
     * @return int
     */
    public function contaQtdeEstacoesPorUltimoTipoEvento($tipo_evento_id, $somenteAtivas = true, $filtrarTipos = [])
    {
        if ($filtrarTipos)
        {
            $queryFiltrarTipos = 'AND ev.tipo_evento_id IN(' . implode(',', $filtrarTipos) . ')';
        }

        $this->getArrayEstacoesComAcesso();

        $this->db->from('estacao e')
                ->select("
                            (
                                SELECT
                                    tipo_evento_id
                                FROM
                                    evento ev
                                WHERE
                                    ev.estacao_id = e.id
                                    {$queryFiltrarTipos}
                                ORDER BY
                                    datahora DESC LIMIT 1
                            ) as ultimo_tipo_evento_id
                ")
                ->having('ultimo_tipo_evento_id', $tipo_evento_id);

        if ($somenteAtivas)
        {
            $this->db->where('ativa', 1);
        }

        $this->filtrarEstacoesComAcesso('e.id');

        return $this->db->count_all_results();
    }

    /**
     * Retorna a quantidade de estações ativas online
     *
     * @return int
     */
    public function getQtdeEstacoesOnline()
    {
        $idTipoEventoOnline        = 2;
        $idsTipoEventoOnlineOffile = [1, 2];

        return $this->contaQtdeEstacoesPorUltimoTipoEvento($idTipoEventoOnline, true, $idsTipoEventoOnlineOffile);
    }

    /**
     * Retorna a quantidade de estações ativas offline
     *
     * @return int
     */
    public function getQtdeEstacoesOffline()
    {
        $idTipoEventoOffline       = 1;
        $idsTipoEventoOnlineOffile = [1, 2];

        return $this->contaQtdeEstacoesPorUltimoTipoEvento($idTipoEventoOffline, true, $idsTipoEventoOnlineOffile);
    }

    /**
     * Retorna os dados de uma estação em array
     *
     * @param int $id
     * @return array[mixed]
     */
    public function getEstacao($id)
    {
        $estacao = $this->db->where('id', $id)
                ->get('estacao')
                ->row_array();

        $estacao['online'] = $this->getEstacaoOnline($id);

        return $estacao;
    }

    /**
     * Retorna um array com os dados de uma estação, buscando-se pelo identificador (string)
     *
     * @param string $identificador
     * @return array
     */
    public function getEstacaoPorIdentificador($identificador)
    {
        $estacao = $this->db->where('identificador', $identificador)
                ->get('estacao')
                ->row_array();

        $estacao['online'] = $this->getEstacaoOnline($estacao['id']);

        return $estacao;
    }

    /**
     * Checa se uma estação está online
     *
     * @param int $estacaoId Id da estação
     * @return bool
     */
    public function getEstacaoOnline($estacaoId)
    {
        $idTipoEventoOnline        = 2;
        $idsTipoEventoOnlineOffile = [1, 2];

        $evento = $this->getUltimoEvento($estacaoId, $idsTipoEventoOnlineOffile);

        if (!$evento)
        {
            return false;
        }

        return $evento['tipo_evento_id'] == $idTipoEventoOnline;
    }

    public function getEstacoes($somenteAtivas = FALSE, $ids = array(), $tipo = NULL)
    {


        $this->db->order_by('ativa', 'DESC'); // Ordenar as ativas para as telas de relatorio
        $this->db->order_by('descricao');

        if ($somenteAtivas)
        {

            $this->db->where('ativa', true);
        }

        if (!empty($ids))
        {
            $this->db->where_in('id', $ids);
        }

        if ($tipo)
        {
            $this->db->where('tipo', $tipo);
        }

        $estacoes = $this->db->get('estacao')->result_array();

        foreach ($estacoes as $index => $estacaoAtual)
        {
            $estacoes[$index]['online'] = $this->getEstacaoOnline($estacaoAtual['id']);
        }

        return $estacoes;
    }

    public function getContagemEstacoes($somenteAtivas = TRUE)
    {

        $this->getArrayEstacoesComAcesso();

        if ($somenteAtivas)
        {
            $this->db->where('ativa', TRUE);
        }

        // $result   = $this->getEstacoesComAcessoPorUsuario();
        ////$imploded = implode(',',  $result);
        // $this->db->where_in('`estacao`.`id`', explode(',', $imploded));
        $this->filtrarEstacoesComAcesso('`estacao`.`id`');
        return $this->db->count_all_results('estacao');
    }

    /**
     * Retotna um array com as dados das estações, com as últimas leituras, no padrão do JSON para Leaflet
     *
     * @param string $camada Tipo de informação
     * @param bool $somenteAtivas Mostrar somente estações ativas
     * @param array $ids Ids das estações
     * @return array
     */
    public function getEstacoesGeoJson($camada = NULL, $somenteAtivas, $ids)
    {
        $estacoesBD = $this->getEstacoes($somenteAtivas, $ids);

        $estacoes = [];

        foreach ($estacoesBD as $eAtual)
        {
            if ($eAtual['latitude'] && $eAtual['longitude'])
            {

                if ($ultimoRegistro = $this->getUltimoRegistro($eAtual['id'], NULL, true))
                {
                    $ultimaLeituraRetorno = [
                        'datahora'             => $ultimoRegistro['datahora'],
                        'datahora_formatada'   => date('d/m/Y H:i:s', strtotime($ultimoRegistro['datahora'])),
                        'temperatura'          => $ultimoRegistro['temperatura'],
                        'umidade_ar'           => $ultimoRegistro['umidade_ar'],
                        'velocidade_vento'     => $ultimoRegistro['velocidade_vento'],
                        'dir_vento'            => $ultimoRegistro['dir_vento'],
                        'volume_chuva'         => $ultimoRegistro['volume_chuva'],
                        'volume_acumulado_1h'  => $ultimoRegistro['volume_chuva_ac_1h'],
                        'volume_acumulado_24h' => $ultimoRegistro['volume_chuva_ac_24h'],
                        'volume_acumulado_96h' => $ultimoRegistro['volume_chuva_ac_96h']
                    ];
                }
                else
                {
                    $ultimaLeituraRetorno = NULL;
                }

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
                        'ultimaLeitura' => $ultimaLeituraRetorno,
                        'camada'        => $this->getConfiguracoesCamada($ultimoRegistro, $camada)
                    ],
                    'id'         => $eAtual['id']
                ];
            }
        }
        $geojson = ['type' => 'FeatureCollection', 'features' => $estacoes];

        return $geojson;
    }

    private function getConfiguracoesCamada($leitura, $camada)
    {
        $this->load->model('LeiturasModel');

        $corDaEstacao = '#0000FF';
        switch ($camada)
        {
            case FiltrosLeitura::TIPO_VOLUME_CHUVA:
                if ($leitura)
                {
                    $coresPluviometria = $this->LeiturasModel->getCoresNiveisAlertasPluviometria();
                    $nivel             = $this->LeiturasModel->calcularAlertaPluviometria($leitura);
                    $corDaEstacao      = $coresPluviometria[$nivel];
                }
                break;
        }

        $camadaRetorno = [
            'cor' => $corDaEstacao
        ];

        return $camadaRetorno;
    }

    public function monitorarEstacao($intervaloTempo)
    {
        $estacoes = $this->getEstacoes(true); //variavel para armazenar todas as estações Ativas
        $eventos  = [];
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
                        "estacao_id"     => $estacao['id'],
                        "tipo_evento_id" => 1,
                        "mensagem"       => 'offline'
                    ];
                }
            }
            else
            {
                if (!isset($ultimoEvento['tipo_evento_id']) || $ultimoEvento['tipo_evento_id'] != 2) //Verificando se o evento anterior também não é up
                {
                    $this->inserirEvento($estacao['id'], 2); //Inserindo na tabela evento que a estação esta online
                    $eventos[] = [
                        "estacao_id"     => $estacao['id'],
                        "tipo_evento_id" => 2,
                        "mensagem"       => 'online'
                    ];
                }
            }
        }
        return $eventos;
    }

    /**
     * Retorna a última leitura, baseando-se na data/hora de cadastro da leitura no banco de dados
     *
     * @param int $estacaoId
     * @return array
     */
    private function getUltimaLeitura($estacaoId)
    {
        return $this->db->where('estacao_id', $estacaoId)
                        ->order_by('datahora_cadastro', 'desc')
                        ->limit(1)
                        ->get('leitura')
                        ->row_array();
    }

    private function getUltimoEvento($estacaoId, $filtroTipos = NULL)
    {
        $this->db->where('estacao_id', $estacaoId)
                ->order_by('datahora', 'desc')
                ->limit(1);

        if ($filtroTipos)
        {
            $this->db->where_in('tipo_evento_id', $filtroTipos);
        }

        return $this->db->get('evento')->row_array();
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

        $this->getArrayEstacoesComAcesso();

        $this->db->select('evento.id, evento.datahora, evento.tipo_evento_id, estacao.id AS estacao_id, estacao.descricao AS estacao_descricao, estacao.identificador as estacao_identificador')
                ->from('evento')
                ->join('estacao', 'evento.estacao_id = estacao.id')
                ->order_by('evento.datahora', 'desc')
                ->limit($limit);
        $this->filtrarEstacoesComAcesso('estacao_id');

        $query = $this->db->get();

        return $query->result_array();
    }

    /**
     * Retorna a última leitura, considerando a data da leitura, registrada pela estação
     *
     * @param int $estacaoId
     * @param int $limiteTempoEmMinutos
     * @param bool $cacheBD
     * @return array
     */
    public function getUltimoRegistro($estacaoId, $limiteTempoEmMinutos = NULL, $cacheBD = false) //pega o ultimo registro de cada estação para atualizar o mapa de monitoamento a cada 30seg
    {
        $this->db->where('estacao_id', $estacaoId)
                ->order_by('datahora', 'desc')
                ->limit(1);

        if ($limiteTempoEmMinutos !== NULL)
        {
            $this->db->where('datahora >= date_sub(now(), INTERVAL ' . $limiteTempoEmMinutos . ' MINUTE)');
        }

        if ($cacheBD)
        {
            $tabela = 'leitura_calculada';
        }
        else
        {
            $tabela = 'v_leitura_calculada';
        }

        return $this->db->get($tabela)
                        ->row_array();
    }

    public function getURLMonitoramentoEstacao($idEstacao)
    {
        return base_url('Estacoes/monitoramentoIndividual/' . $idEstacao);
    }

    public function getEstacaoComDadosMeteorologicos()
    {
        //$result = $this->getEstacoesComAcessoPorUsuario();
        $this->getArrayEstacoesComAcesso();
        // $imploded = implode(',', $result);

        $this->db->select('estacao.*, leitura.temperatura, leitura.velocidade_vento, leitura.volume_chuva');
        $this->db->from('estacao');
        $this->db->join('(SELECT estacao_id, MAX(id) AS max_id FROM leitura GROUP BY estacao_id) AS ultima_leitura', 'estacao.id = ultima_leitura.estacao_id', 'left');
        $this->db->join('leitura', 'ultima_leitura.max_id = leitura.id', 'left');
        $this->db->where('estacao.ativa', 1);
        // $this->db->where_in('`estacao`.`id`', explode(',', $imploded));
        $this->filtrarEstacoesComAcesso('`estacao`.`id`');
        return $this->db->get()->result();
    }

    public function getEmailsUsuariosPorEstacao($estacaoId)
    {
        return $this->db
                        ->select('u.email, u.nome')
                        ->from('Usuario u')
                        ->join('Usuario_Acessa_Estacao ue', 'u.id = ue.usuario_id')
                        ->where('ue.estacao_id', $estacaoId)
                        ->get()
                        ->result();
    }

    private function getEstacoesComAcessoPorUsuario()
    {
        $this->load->model('LoginModel');

        $usuario    = $this->LoginModel->getDadosUsuarioLogado();
        $usuario_id = $usuario['id'];
        $grupos     = implode(", ", $usuario['grupos']);

        $db = $this->db
                ->distinct()->select('e.id')
                ->from('estacao e')
                ->join('usuario_acessa_estacao uas', 'uas.estacao_id = e.id', 'left')
                ->join('grupo_acessa_estacao gas', 'gas.estacao_id = e.id', 'left')
                ->where('gas.grupo_id in ' . '(' . $grupos . ')')
                ->or_where('uas.usuario_id', $usuario_id)
                ->get()
                ->result_array();

        $db = array_map(function ($linha)
        {
            return $linha['id'];
        }, $db);

        return $db;
    }
}
