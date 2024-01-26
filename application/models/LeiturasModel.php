<?php

require_once 'BaseModel.php';

class LeiturasModel extends BaseModel
{

    const PLUVIOMETRIA_NIVEL_NORMALIDADE   = 'normalidade';
    const PLUVIOMETRIA_NIVEL_ATENCAO       = 'atencao';
    const PLUVIOMETRIA_NIVEL_ALERTA        = 'alerta';
    const PLUVIOMETRIA_NIVEL_ALERTA_MAXIMO = 'alerta_maximo';

    public function inserirLeitura($estacaoId, $dadosLeitura)
    {
        $dadosLeitura['estacao_id'] = $estacaoId;
        return $this->db->insert('leitura', $dadosLeitura);
    }

    public function calcularEstatisticasPorPeriodo(FiltrosLeitura $filtros)
    {

        $this->db->from('leitura');
        if ($filtros)
        {
            $estacoes       = $filtros->getEstacoes();
            $dataInicial    = $filtros->getDataInicial();
            $dataFinal      = $filtros->getDataFinal();
            $escala         = $filtros->getEscala();
            $tipoInformacao = $filtros->getTipoInformacao();

            if ($estacoes)
            {
                $this->db->where_in('estacao_id', $estacoes);
            }

            if ($dataInicial)
            {
                $this->db->where('datahora >=', $dataInicial . (strlen($dataInicial) <= 10 ? ' 00:00:00' : ''));
            }

            if ($dataFinal)
            {
                $this->db->where('datahora <=', $dataFinal . (strlen($dataFinal) <= 10 ? ' 23:59:59' : ''));
            }

            $agregador = 'AVG';
            switch ($tipoInformacao)
            {
                case FiltrosLeitura::TIPO_VELOCIDADE_VENTO:
                    $colunaTipoInformacao = 'velocidade_vento';
                    break;

                case FiltrosLeitura::TIPO_DIRECAO_VENTO:
                    $colunaTipoInformacao = 'dir_vento';
                    break;

                case FiltrosLeitura::TIPO_TEMPERATURA:
                    $colunaTipoInformacao = 'temperatura';
                    break;

                case FiltrosLeitura::TIPO_VOLUME_CHUVA:
                    $colunaTipoInformacao = 'volume_chuva';
                    $agregador            = 'SUM';
                    break;

                case FiltrosLeitura::TIPO_UMIDADE_AR:
                    $colunaTipoInformacao = 'umidade_ar';
                    break;

                default:
                    throw new Exception('É necessário informar o tipo de informação desejada.');
            }


            switch ($escala)
            {
                case FiltrosLeitura::ESCALA_MINUTO:
                    $colunaPeriodo = "DATE_FORMAT(datahora,'%Y-%m-%d %H:%i')";
                    break;

                case FiltrosLeitura::ESCALA_HORA:
                    $colunaPeriodo = "DATE_FORMAT(datahora,'%Y-%m-%d %H:00')";
                    break;

                case FiltrosLeitura::ESCALA_DIA:
                    $colunaPeriodo = "DATE_FORMAT(datahora,'%Y-%m-%d')";
                    break;

                case FiltrosLeitura::ESCALA_SEMANA:
                    $colunaPeriodo = "DATE_FORMAT(datahora,'%Y-%V')";
                    break;

                case FiltrosLeitura::ESCALA_MES:
                    $colunaPeriodo = "DATE_FORMAT(datahora,'%Y-%m')";
                    break;

                case FiltrosLeitura::ESCALA_ANO:
                    $colunaPeriodo = "DATE_FORMAT(datahora,'%Y')";
                    break;

                default:
                    throw new Exception('É necessário informar a escala desejada.');
            }

            $this->db->select($colunaPeriodo . ' AS periodo, ' . $agregador . '(' . $colunaTipoInformacao . ') AS valor');
            $this->db->group_by($colunaPeriodo);
            $this->db->order_by('periodo', $filtros->getDirecao());
            $resultado = $this->db->get();

            //echo $this->db->last_query();

            $resultadoArray = $resultado->result_array();
            $retorno        = [];
            foreach ($resultadoArray as $linha)
            {
                if ($tipoInformacao === FiltrosLeitura::TIPO_VELOCIDADE_VENTO)
                {
                    // Converte a velocidade do vento de m/s para km/h se for o tipo de informação 'velocidade_vento'
                    $linha['valor'] = Conversao::velVentoParakmH($linha['valor']);
                }
                $retorno[$linha['periodo']] = $linha['valor'];
            }
            return $retorno;
        }
    }

    public function getLeiturasPorEscala(FiltrosLeitura $filtros = NULL, $retornarTudo = true)
    {
        $this->db->from('leitura L');
        if ($filtros)
        {
            $estacoes    = $filtros->getEstacoes();
            $dataInicial = $filtros->getDataInicial();
            $dataFinal   = $filtros->getDataFinal();
            $escala      = $filtros->getEscala();

            if ($estacoes)
            {
                $this->db->where_in('estacao_id', $estacoes);
            }

            if ($dataInicial)
            {
                $this->db->where('datahora >=', $dataInicial . (strlen($dataInicial) <= 10 ? ' 00:00:00' : ''));
            }

            if ($dataFinal)
            {
                $this->db->where('datahora <=', $dataFinal . (strlen($dataFinal) <= 10 ? ' 23:59:59' : ''));
            }

            switch ($escala)
            {
                case FiltrosLeitura::ESCALA_HORA:
                    $colunaPeriodo = "DATE_FORMAT(datahora,'%Y-%m-%d %H:00')";
                    break;

                case FiltrosLeitura::ESCALA_DIA:
                    $colunaPeriodo = "DATE_FORMAT(datahora,'%Y-%m-%d')";
                    break;

                case FiltrosLeitura::ESCALA_MES:
                    $colunaPeriodo = "DATE_FORMAT(datahora,'%Y-%m')";
                    break;

                case FiltrosLeitura::ESCALA_ANO:
                    $colunaPeriodo = "DATE_FORMAT(datahora,'%Y')";
                    break;

                default:
                    throw new Exception('É necessário informar a escala desejada.');
            }

            $this->db->select(
                    $colunaPeriodo . ' AS periodo,
                            E.identificador as estacao_identificador,
                            E.descricao as estacao_descricao,
                            E.endereco as estacao_endereco,
                            E.latitude as estacao_latitude,
                            E.longitude as estacao_longitude,
                            AVG(L.temperatura) as temperatura,
                            AVG(L.umidade_ar) as umidade_ar,
                            AVG(L.velocidade_vento) as velocidade_vento,
                            SUM(L.volume_chuva) as volume_chuva,
                            MAX(L.velocidade_vento) as rajada_vento
                        ');

            $this->db->join('estacao E', 'L.estacao_id = E.id');
            $this->db->group_by(
                    $colunaPeriodo . ',
                            E.identificador,
                            E.descricao,
                            E.endereco,
                            E.latitude,
                            E.longitude

            ');
            $this->db->order_by('periodo', 'ASC');
            $resultado = $this->db->get();
            //echo $this->db->last_query();

            if ($retornarTudo)
            {
                return $resultado->result_array();
            }
            else
            {
                return $resultado;
            }
        }
    }

    /**
     * Retorna a última leitura obtida de cada estação
     *
     * @param FiltrosLeitura $filtros Filtros para obtenção das leituras
     * @param int $tempoLimite Tempo limite (em minutos) a considerar na obtenção das leituras. Leituras mais antigas serão descartadas.
     * @return array
     * @throws Exception
     */
    public function getUltimasLeituras(FiltrosLeitura $filtros = NULL, $tempoLimite = 2440)
    {
        $this->db->from('leitura');
        if ($filtros)
        {
            $estacoes       = $filtros->getEstacoes();
            $dataInicial    = $filtros->getDataInicial();
            $dataFinal      = $filtros->getDataFinal();
            $tipoInformacao = $filtros->getTipoInformacao();

            if (!$tipoInformacao)
            {
                throw new Exception('É necessário informar o tipo de informação desejada.');
            }

            if ($estacoes)
            {
                $this->db->where_in('estacao_id', $estacoes);
            }

            if ($dataInicial)
            {
                $this->db->where('datahora >=', $dataInicial);
            }

            if ($dataFinal)
            {
                $this->db->where('datahora <=', $dataFinal);
            }

            switch ($tipoInformacao)
            {
                case FiltrosLeitura::TIPO_VELOCIDADE_VENTO:
                    $colunaTipoInformacao = 'velocidade_vento';
                    break;

                case FiltrosLeitura::TIPO_TEMPERATURA:
                    $colunaTipoInformacao = 'temperatura';
                    break;

                case FiltrosLeitura::TIPO_VOLUME_CHUVA:
                    $colunaTipoInformacao = 'volume_chuva';
                    break;

                case FiltrosLeitura::TIPO_UMIDADE_AR:
                    $colunaTipoInformacao = 'umidade_ar';
                    break;

                case FiltrosLeitura::TIPO_VOLUME_ACC_CHUVA:
                    $colunaTipoInformacao = 'volume_acc_chuva';
                    break;

                default:
                    throw new Exception('É necessário informar o tipo de informação desejada.');
            }

            $this->db->from('estacao E')
                    ->select("

                    (
                        SELECT
                                {$colunaTipoInformacao}
                        FROM
                                leitura L1
                        WHERE
                                L1.estacao_id = E.id
                                AND datahora >= DATE_SUB(now(), INTERVAL {$tempoLimite} MINUTE)
                        ORDER BY
                                datahora DESC
                        LIMIT 1
                )
                AS valor
            ");
            $resultado = $this->db->get()->result_array();
            return $resultado;
        }
    }

    /**
     * Essa função retorna a ultima leitura registrada no banco
     *
     * Função criada por jaque 31/07. Motivo: retorno de NULL e utilização de data na função getUltimaSleituraS
     *
     * @param FiltrosLeitura $filtros
     * @param int $tempoLimite
     * @return array
     * @throws Exception
     */
    public function getUltimaLeituraRegistrada(FiltrosLeitura $filtros = NULL)
    {
        $this->db->from('leitura');

        if ($filtros)
        {
            $estacoes       = $filtros->getEstacoes();
            $tipoInformacao = $filtros->getTipoInformacao();

            if (!$tipoInformacao)
            {
                throw new Exception('É necessário informar o tipo de informação desejada.');
            }

            if ($estacoes)
            {
                $this->db->where_in('estacao_id', $estacoes);
            }

            switch ($tipoInformacao)
            {
                case FiltrosLeitura::TIPO_VELOCIDADE_VENTO:
                    $colunaTipoInformacao = 'velocidade_vento';
                    break;

                case FiltrosLeitura::TIPO_DIRECAO_VENTO:
                    $colunaTipoInformacao = 'dir_vento';
                    break;

                case FiltrosLeitura::TIPO_TEMPERATURA:
                    $colunaTipoInformacao = 'temperatura';
                    break;

                case FiltrosLeitura::TIPO_VOLUME_CHUVA:
                    $colunaTipoInformacao = 'volume_chuva';
                    break;

                case FiltrosLeitura::TIPO_UMIDADE_AR:
                    $colunaTipoInformacao = 'umidade_ar';
                    break;

                case FiltrosLeitura::TIPO_VOLUME_ACC_CHUVA:
                    $colunaTipoInformacao = 'volume_acc_chuva';
                    break;

                default:
                    throw new Exception('É necessário informar o tipo de informação desejada.');
            }

            $this->db->select("COALESCE({$colunaTipoInformacao}, 0) AS 'valor', datahora")
                    ->order_by("datahora", "DESC")
                    ->limit(1);

            $resultado = $this->db->get()->result_array();
            return $resultado;
        }
    }

    public function getUltimaTemperaturaMedia()
    {
        if (DB_CACHE_ESTATISTICAS)
        {
            $this->db->cache_on();
        }

        $this->db->from('estacao E')
                ->select("
                    AVG(
                        (
                            SELECT
                                    temperatura
                            FROM
                                    leitura L1
                            WHERE
                                    L1.estacao_id = E.id
                                    AND datahora >= date_sub(now(), INTERVAL 30 MINUTE)
                            ORDER BY
                                    datahora DESC
                            LIMIT 1
                        )
                    )
                    AS temperatura_media
                ");
        $linha = $this->db->get()->row_array();

        if (DB_CACHE_ESTATISTICAS)
        {
            $this->db->cache_off();
        }

        return $linha['temperatura_media'];
    }

    public function getVolumeChuvaMinimo()
    {

        if (DB_CACHE_ESTATISTICAS)
        {
            $this->db->cache_on();
        }

        $this->db->from("estacao E")
                ->select("
                    MIN(
                        (
                            SELECT
                                    volume_chuva
                            FROM
                                    leitura L1
                            WHERE
                                    L1.estacao_id = E.id
                                    AND datahora >= date_sub(now(), INTERVAL 30 MINUTE)
                            ORDER BY
                                    datahora DESC
                            LIMIT 1
                        )
                    )
                    AS vol_chuva_min
                ");
        $linha = $this->db->get()->row_array();

        if (DB_CACHE_ESTATISTICAS)
        {
            $this->db->cache_off();
        }

        return $linha['vol_chuva_min'];
    }

    public function getVolumeChuvaMaxima()
    {
        if (DB_CACHE_ESTATISTICAS)
        {
            $this->db->cache_on();
        }

        $this->db->from('estacao E')
                ->select("
            MAX(
                (
                    SELECT
                            volume_chuva
                    FROM
                            leitura L1
                    WHERE
                            L1.estacao_id = E.id
                            AND datahora >= date_sub(now(), INTERVAL 30 MINUTE)
                    ORDER BY
                            datahora DESC
                    LIMIT 1
                )
            )
            AS vol_chuva_max
        ");
        $linha = $this->db->get()->row_array();

        if (DB_CACHE_ESTATISTICAS)
        {
            $this->db->cache_off();
        }

        return $linha['vol_chuva_max'];
    }

    public function getTemperaturaMinima()
    {
        if (DB_CACHE_ESTATISTICAS)
        {
            $this->db->cache_on();
        }

        $this->db->from('estacao E')
                ->select("
                    MIN(
                        (
                            SELECT
                                    temperatura
                            FROM
                                    leitura L1
                            WHERE
                                    L1.estacao_id = E.id
                                    AND datahora >= date_sub(now(), INTERVAL 30 MINUTE)
                            ORDER BY
                                    datahora DESC
                            LIMIT 1
                        )
                    )
                    AS temperatura_minima
                ");
        $linha = $this->db->get()->row_array();

        if (DB_CACHE_ESTATISTICAS)
        {
            $this->db->cache_off();
        }

        return $linha['temperatura_minima'];
    }

    public function getTemperaturaMaxima()
    {
        if (DB_CACHE_ESTATISTICAS)
        {
            $this->db->cache_on();
        }

        $this->db->from('estacao E')
                ->select("
                    MAX(
                        (
                            SELECT
                                    temperatura
                            FROM
                                    leitura L1
                            WHERE
                                L1.estacao_id = E.id
                                AND datahora >= date_sub(now(), INTERVAL 30 MINUTE)
                            ORDER BY
                                    datahora DESC
                            LIMIT 1
                        )
                    )
                    AS temperatura_maxima
                ");
        $linha = $this->db->get()->row_array();

        if (DB_CACHE_ESTATISTICAS)
        {
            $this->db->cache_off();
        }

        return $linha['temperatura_maxima'];
    }

    public function getVelocidadeMinima()
    {
        if (DB_CACHE_ESTATISTICAS)
        {
            $this->db->cache_on();
        }

        $this->db->from("estacao E")
                ->select("
                    MIN(
                        (
                            SELECT
                                velocidade_vento
                            FROM
                                leitura L1
                            WHERE
                                L1.estacao_id = E.id
                                AND datahora >= date_sub(now(), INTERVAL 30 MINUTE)
                            ORDER BY
                                datahora DESC
                            LIMIT 1
                        )
                    )
                    AS velocidade_minima
                    ");
        $linha = $this->db->get()->row_array();

        if (DB_CACHE_ESTATISTICAS)
        {
            $this->db->cache_off();
        }

        return $linha["velocidade_minima"];
    }

    public function getVelocidadeMaxima()
    {
        if (DB_CACHE_ESTATISTICAS)
        {
            $this->db->cache_on();
        }

        $this->db->from("estacao E")
                ->select("
                    MAX(
                        (
                            SELECT
                                velocidade_vento
                            FROM
                                    leitura L1
                            WHERE
                                L1.estacao_id = E.id
                                AND datahora >= date_sub(now(), INTERVAL 30 MINUTE)
                            ORDER BY
                                    datahora DESC
                            LIMIT 1
                        )
                    )
                    AS velocidade_maxima
                    ");
        $linha = $this->db->get()->row_array();

        if (DB_CACHE_ESTATISTICAS)
        {
            $this->db->cache_off();
        }

        return $linha["velocidade_maxima"];
    }

    public function calcularAlertaPluviometria($leitura)
    {
        if ($leitura['volume_chuva_ac_96h'] > 250. || $leitura['volume_chuva_ac_24h'] > 150. || $leitura['volume_chuva_ac_1h'] > 40.)
        {
            return self::PLUVIOMETRIA_NIVEL_ALERTA_MAXIMO;
        }
        elseif ($leitura['volume_chuva_ac_96h'] > 175. && $leitura['volume_chuva_ac_96h'] <= 250. || $leitura['volume_chuva_ac_24h'] > 80. && $leitura['volume_chuva_ac_24h'] <= 150. || $leitura['volume_chuva_ac_1h'] >= 20. && $leitura['volume_chuva_ac_1h'] <= 40.)
        {
            return self::PLUVIOMETRIA_NIVEL_ALERTA;
        }
        elseif ($leitura['volume_chuva_ac_96h'] >= 100. && $leitura['volume_chuva_ac_96h'] < 175. || $leitura['volume_chuva_ac_24h'] >= 40. && $leitura['volume_chuva_ac_24h'] < 80. || $leitura['volume_chuva_ac_1h'] >= 5. && $leitura['volume_chuva_ac_1h'] < 20.)
        {
            return self::PLUVIOMETRIA_NIVEL_ATENCAO;
        }
        else
        {
            return self::PLUVIOMETRIA_NIVEL_NORMALIDADE;
        }
    }

    public static function converterVelocidadeVentoKMH($velocidadeMS)
    {
        return number_format($velocidadeMS * 3.6, 1);
    }
}

class FiltrosLeitura
{

    const ESCALA_ANO            = 'ano';
    const ESCALA_MES            = 'mes';
    const ESCALA_SEMANA         = 'semana';
    const ESCALA_DIA            = 'dia';
    const ESCALA_HORA           = 'hora';
    const ESCALA_MINUTO         = 'minuto';
    const TIPO_VELOCIDADE_VENTO = 'velocidade_vento';
    const TIPO_DIRECAO_VENTO    = 'dir_vento'; //Jaque 31/07 -> monitoramento individual de estações
    const TIPO_TEMPERATURA      = 'temperatura';
    const TIPO_VOLUME_CHUVA     = 'volume_chuva';
    const TIPO_UMIDADE_AR       = 'umidade_ar';
    const TIPO_VOLUME_ACC_CHUVA = 'volume_acc_chuva';
    const DIRECAO_ASC           = 'ASC';
    const DIRECAO_DESC          = 'DESC';

    private $estacoes = array();
    private $dataInicial;
    private $dataFinal;
    private $escala   = self::ESCALA_MES;
    private $tipoInformacao;
    private $direcao  = self::DIRECAO_ASC;

    public static function getTodosTiposInformacao()
    {
        return [
            self::TIPO_VOLUME_CHUVA     => 'Pluviometria',
            self::TIPO_TEMPERATURA      => 'Temperatura',
            self::TIPO_DIRECAO_VENTO    => 'Direção do Vento',
            self::TIPO_UMIDADE_AR       => 'Umidade do Ar',
            self::TIPO_VELOCIDADE_VENTO => 'Velocidade do Vento'
        ];
    }

    public function getEstacoes()
    {
        return $this->estacoes;
    }

    public function setEstacoes($estacoes)
    {
        $this->estacoes = $estacoes;
    }

    public function getDataInicial()
    {
        return $this->dataInicial;
    }

    public function setDataInicial($dataInicial)
    {
        $this->dataInicial = $dataInicial;
    }

    public function getDataFinal()
    {
        return $this->dataFinal;
    }

    public function setDataFinal($dataFinal)
    {
        $this->dataFinal = $dataFinal;
    }

    public function getEscala()
    {
        return $this->escala;
    }

    public function setEscala($escala)
    {
        $this->escala = $escala;
    }

    public function getTipoInformacao()
    {
        return $this->tipoInformacao;
    }

    public function setTipoInformacao($tipoInformacao)
    {
        $this->tipoInformacao = $tipoInformacao;
    }

    public function getDirecao()
    {
        return $this->direcao;
    }

    public function setDirecao($direcao)
    {
        $this->direcao = $direcao;
    }
}
