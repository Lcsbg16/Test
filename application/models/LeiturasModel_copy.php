<?php

require_once 'BaseModel.php';

class LeiturasModel_copy extends BaseModel
{

    const PLUVIOMETRIA_NIVEL_NORMALIDADE   = 'normalidade';
    const PLUVIOMETRIA_NIVEL_ATENCAO       = 'atencao';
    const PLUVIOMETRIA_NIVEL_ALERTA        = 'alerta';
    const PLUVIOMETRIA_NIVEL_ALERTA_MAXIMO = 'alerta_maximo';

    public $estacoesComAcesso = array();

    public function __construct()
    {
        $this->load->model('EstacoesModel');

        parent::__construct();
    }

    public function getCoresNiveisAlertasPluviometria()
    {
        return [
            LeiturasModel::PLUVIOMETRIA_NIVEL_ATENCAO       => COR_PLUVIOMETRIA_NIVEL_ATENCAO,
            LeiturasModel::PLUVIOMETRIA_NIVEL_ALERTA        => COR_PLUVIOMETRIA_NIVEL_ALERTA,
            LeiturasModel::PLUVIOMETRIA_NIVEL_ALERTA_MAXIMO => COR_PLUVIOMETRIA_NIVEL_ALERTA_MAXIMO,
            LeiturasModel::PLUVIOMETRIA_NIVEL_NORMALIDADE   => COR_PLUVIOMETRIA_NIVEL_NORMALIDADE
        ];
    }

    public function getNomesNiveisAlertasPluviometria()
    {
        return [
            LeiturasModel::PLUVIOMETRIA_NIVEL_ATENCAO       => 'nível de atenção',
            LeiturasModel::PLUVIOMETRIA_NIVEL_ALERTA        => 'nível de alerta',
            LeiturasModel::PLUVIOMETRIA_NIVEL_ALERTA_MAXIMO => 'nível de alerta máximo',
            LeiturasModel::PLUVIOMETRIA_NIVEL_NORMALIDADE   => 'nível de normalidade'
        ];
    }

    public function inserirLeitura($estacaoId, $dadosLeitura)
    {
        $dadosLeitura['estacao_id'] = $estacaoId;

        $this->db->insert('leitura', $dadosLeitura);

        $insert_id = $this->db->insert_id();

        return $insert_id;
    }
    public function inserirLeituraAPI($estacaoId, $leitura_id, $postData)
    {
         //removendo valores para a tabela leitura_valor
         unset($postData['identidade']);
         unset($postData['timestamp']);
         unset($postData['uid']);

         foreach ($postData as $key => $value) { 
             $this->inserirLeituraValor( $leitura_id, $key, $value);
         }

         foreach ($postData as $key => $value) { 
             $this->inserirUltimaLeitura($estacaoId, $leitura_id, $key, $value);
         }
    }

    public function inserirLeituraValor($leituraId, $tag, $value)
    {

        $dadosLeitura['leitura_id']           = $leituraId;
        $dadosLeitura['leitura_dimensao_tag'] = $tag;

        if (is_string($value))
        {
            $dadosLeitura['valor_texto'] = $value;
        }
        else
        {
            $dadosLeitura['valor'] = $value;
        }

        return $this->db->insert('leitura_valor', $dadosLeitura);
    }

    public function inserirUltimaLeitura($estacaoId, $leituraId, $tag, $value)
    {

        $this->db->where('estacao_id', $estacaoId)->where('leitura_dimensao_tag', $tag);
        $q = $this->db->get('ultima_leitura_valor');

        $dadosLeitura['estacao_id']           = $estacaoId;
        $dadosLeitura['leitura_id']           = $leituraId;
        $dadosLeitura['leitura_dimensao_tag'] = $tag;

        if (is_string($value))
        {
            $dadosLeitura['valor_texto'] = $value;
        }
        else
        {
            $dadosLeitura['valor'] = $value;
        }

        if ($q->num_rows() > 0)
        {
            return $this->db->where('estacao_id', $estacaoId)->where('leitura_dimensao_tag', $tag)->update('ultima_leitura_valor', $dadosLeitura);
        }
        else
        {
            return $this->db->insert('ultima_leitura_valor', $dadosLeitura);
        }
    }

    private function filtrarEstacoesComAcesso($fild)
    {


        $this->EstacoesModel->filtrarEstacoesComAcesso($fild);
    }

    private function getArrayEstacoesComAcesso()
    {

        $this->EstacoesModel->getArrayEstacoesComAcesso();
    }

    public function calcularEstatisticasPorPeriodo(FiltrosLeitura $filtros)
    {

        $this->getArrayEstacoesComAcesso();

        if ($filtros)
        {
            $estacoes = $filtros->getEstacoes();

            $dataInicial    = $filtros->getDataInicial();
            $dataFinal      = $filtros->getDataFinal();
            $escala         = $filtros->getEscala();
            $tipoInformacao = $filtros->getTipoInformacao();

            if ($estacoes)
            {

                $this->db->where_in('estacao_id', $estacoes);
            }
            else
            {
                $this->filtrarEstacoesComAcesso('estacao_id');
                // $imploded = implode(',', array_map('array_pop',  $this->estacoesComAcesso));
                // $this->db->where_in('estacao_id', explode(',',$imploded));
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

                case FiltrosLeitura::TIPO_PRESSAO_ATMOSFERICA: //#adicionando_pressao_atm
                    $colunaTipoInformacao = 'pressao_atm';
                    break;

                    case FiltrosLeitura::TIPO_RAJADA_VENTO: //#adicionando_rajada_vento .
                        $colunaTipoInformacao = "rajada_vento_1h";
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

            if ($colunaTipoInformacao == "rajada_vento_1h")
            {  //#adicionando_rajada_vento .
                $this->db->from('v_leitura_calculada'); 
            }
            else 
            {
                $this->db->from('leitura');  //#adicionando_rajada_vento .
             
            }
            //echo $this->db->last_query();

            $this->db->select($colunaPeriodo . ' AS periodo, ' . $agregador . '(' . $colunaTipoInformacao . ') AS valor');
            $this->db->group_by($colunaPeriodo);
            $this->db->order_by('periodo', $filtros->getDirecao());
            $resultado = $this->db->get();

            $resultadoArray = $resultado->result_array();

            //var_dump( $this->db->last_query());
            $retorno = [];
            foreach ($resultadoArray as $linha)
            {
                if ($tipoInformacao === FiltrosLeitura::TIPO_VELOCIDADE_VENTO)
                {
                    // Converte a velocidade do vento de m/s para km/h se for o tipo de informação 'velocidade_vento'
                    $linha['valor'] = Conversao::metroPorSegundoParaKmPorHora($linha['valor']);
                }
                $retorno[$linha['periodo']] = $linha['valor'];
            }
            return $retorno;
        }

      
    }


    public function getLeiturasPorEscala(FiltrosLeitura $filtros = NULL, $retornarTudo = true)
    {
        $this->getArrayEstacoesComAcesso();
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
            else
            {
                $this->filtrarEstacoesComAcesso('estacao_id');
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
                            E.id as estacao_id,
                            E.identificador as estacao_identificador,
                            E.descricao as estacao_descricao,
                            E.endereco as estacao_endereco,
                            E.latitude as estacao_latitude,
                            E.longitude as estacao_longitude,
                            AVG(L.temperatura) as temperatura,
                            AVG(L.umidade_ar) as umidade_ar,
                            AVG(L.velocidade_vento) as velocidade_vento,
                            MAX(L.velocidade_vento) as rajada_vento,
                            SUM(L.volume_chuva) as volume_chuva,
                            MAX(L.datahora_cadastro) as datahora_cadastro
                        ');

            $this->db->join('estacao E', 'L.estacao_id = E.id');
            $this->db->group_by(
                    $colunaPeriodo . ',
                            E.id,
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

    public function getAcumuladoChuvaPorPeriodoGeral($cacheBD = true) //Acumulos de chuva + descrição da estação + temperatura // jaque
    {
        $this->load->model('EstacoesModel');
        $listaEstacoesLeituras = array();

        $estacoesAtivas = $this->EstacoesModel->getEstacoes(true);

        foreach ($estacoesAtivas as $estacao)
        {
            $ultimoRegistro = $this->EstacoesModel->getUltimoRegistro($estacao['id'], 60, true);

            if ($ultimoRegistro && $ultimoRegistro['volume_chuva_ac_1h'] + $ultimoRegistro['volume_chuva_ac_24h'] + $ultimoRegistro['volume_chuva_ac_96h'] > 0)
            {
                $dadosEstacaoLeitura = [
                    'estacao_id'  => $estacao['id'],
                    'descricao'   => $estacao['descricao'],
                    'volume_1h'   => $ultimoRegistro['volume_chuva_ac_1h'],
                    'volume_24h'  => $ultimoRegistro['volume_chuva_ac_24h'],
                    'volume_96h'  => $ultimoRegistro['volume_chuva_ac_96h'],
                    'temperatura' => $ultimoRegistro['temperatura']
                ];

                $listaEstacoesLeituras[] = (object) $dadosEstacaoLeitura; // Mantendo o padrão de saída em objeto
            }
        }
        return $listaEstacoesLeituras;
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
        $this->getArrayEstacoesComAcesso();
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
            else
            {
                $this->filtrarEstacoesComAcesso('estacao_id');
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

                case FiltrosLeitura::TIPO_PRESSAO_ATMOSFERICA:
                    $colunaTipoInformacao = 'pressao_atm';
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
     *
     * @param FiltrosLeitura $filtros
     * @param int $tempoLimite
     * @return array
     * @throws Exception
     */
    public function getUltimaLeituraRegistrada(FiltrosLeitura $filtros = NULL)
    {
        $this->getArrayEstacoesComAcesso();

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
            else
            {
                $this->filtrarEstacoesComAcesso('estacao_id');
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

                case FiltrosLeitura::TIPO_RAJADA_VENTO:
                    $colunaTipoInformacao = 'rajada_vento_1h'; // #adicionando_rajada_vento .
                        break;

                default:
                    throw new Exception('É necessário informar o tipo de informação desejada.');
            }

            if ($colunaTipoInformacao == "rajada_vento_1h"){  //#adicionando_rajada_vento .
                $this->db->from('v_leitura_calculada'); 
                $this->db->select("COALESCE({$colunaTipoInformacao}, 0) AS 'valor', datahora")
                    ->order_by("datahora", "DESC")
                    ->limit(1);
                $resultado = $this->db->get()->result_array();
                return $resultado;
            }
            else {
                $this->db->from('leitura');  //#adicionando_rajada_vento .
                $this->db->select("COALESCE({$colunaTipoInformacao}, 0) AS 'valor', datahora")
                ->order_by("datahora", "DESC")
                ->limit(1);

                $resultado = $this->db->get()->result_array();
                return $resultado;
            }

           
        }
    }


    public function getUltimaTemperaturaMedia()
    {

        $this->getArrayEstacoesComAcesso();

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
        $this->filtrarEstacoesComAcesso('E.id');
        $linha = $this->db->get()->row_array();

        if (DB_CACHE_ESTATISTICAS)
        {
            $this->db->cache_off();
        }

        return $linha['temperatura_media'];
    }

    public function getVolumeChuvaMinimo()
    {
        $this->getArrayEstacoesComAcesso();

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
        $this->filtrarEstacoesComAcesso('E.id');
        $linha = $this->db->get()->row_array();

        if (DB_CACHE_ESTATISTICAS)
        {
            $this->db->cache_off();
        }

        return $linha['vol_chuva_min'];
    }

    public function getVolumeChuvaMaxima()
    {
        $this->getArrayEstacoesComAcesso();
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
        $this->filtrarEstacoesComAcesso('E.id');
        $linha = $this->db->get()->row_array();

        if (DB_CACHE_ESTATISTICAS)
        {
            $this->db->cache_off();
        }

        return $linha['vol_chuva_max'];
    }

    public function getTemperaturaMinima()
    {
        $this->getArrayEstacoesComAcesso();
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
        $this->filtrarEstacoesComAcesso('E.id');
        $linha = $this->db->get()->row_array();

        if (DB_CACHE_ESTATISTICAS)
        {
            $this->db->cache_off();
        }

        return $linha['temperatura_minima'];
    }

    public function getTemperaturaMaxima()
    {
        $this->getArrayEstacoesComAcesso();
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
        $this->filtrarEstacoesComAcesso('E.id');
        $linha = $this->db->get()->row_array();

        if (DB_CACHE_ESTATISTICAS)
        {
            $this->db->cache_off();
        }

        return $linha['temperatura_maxima'];
    }

    public function getVelocidadeMinima()
    {
        $this->getArrayEstacoesComAcesso();
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
        $this->filtrarEstacoesComAcesso('E.id');
        $linha = $this->db->get()->row_array();

        if (DB_CACHE_ESTATISTICAS)
        {
            $this->db->cache_off();
        }

        return $linha["velocidade_minima"];
    }

    public function getVelocidadeMaxima()
    {
        $this->getArrayEstacoesComAcesso();
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
        $this->filtrarEstacoesComAcesso('E.id');
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

    public function getAllLeituras()
    {
        $this->db->select('leitura.id, leitura.datahora, estacao.identificador as estacao_identificador, estacao.descricao as estacao_descricao,
                           estacao.endereco as estacao_edereco, estacao.latitude as estacao_latitude, estacao.longitude as estacao_longitude,
                           leitura.temperatura, leitura.umidade_ar, leitura.velocidade_vento, leitura.dir_vento, leitura.volume_chuva,datahora_cadastro'); // Adiciona os campos de estacao
        $this->db->from('leitura');
        $this->db->join('estacao', 'leitura.estacao_id = estacao.id');
        //$this->db->order_by('leitura.datahora', 'DESC');
        $this->db->order_by('leitura.id', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function exportarLeiturasParaCSV($filtros)
    {

        $leituras = $this->getLeiturasPorEscala($filtros, false);

        $csvData = array();

        $header = array(
            'periodo',
            'estacao_id',
            'estacao_identificador',
            'estacao_descricao',
            'estacao_endereco',
            'estacao_latitude',
            'estacao_longitude',
            'temperatura',
            'umidade_ar',
            'velocidade_vento',
            'rajada_vento',
            'volume_chuva',
            'datahora_cadastro'
        );

        $csvData[] = $header;

        while ($leitura = $leituras->unbuffered_row('array'))
        {
            // Substituir vírgulas por pontos nas colunas de velocidade do vento e rajada de vento
            $leitura['velocidade_vento'] = str_replace(',', '.', $leitura['velocidade_vento']);
            $leitura['rajada_vento']     = str_replace(',', '.', $leitura['rajada_vento']);

            // Converter velocidade do vento e rajada de vento para km/h
            $leitura['velocidade_vento'] = self::converterVelocidadeVentoKMH($leitura['velocidade_vento']);
            $leitura['rajada_vento']     = self::converterVelocidadeVentoKMH($leitura['rajada_vento']);

            // Substituir o separador decimal de . para ,
            $leitura = array_map(function ($value)
            {
                return str_replace('.', ',', $value);
            }, $leitura);

            // Adicionar a linha ao CSV
            $csvData[] = $leitura;
        }
        //var_dump($csvData);

        return $csvData;
    }

    public static function converterVelocidadeVentoKMH($velocidadeMS)
    {
        return number_format($velocidadeMS * 3.6, 1);
    }

    public function atualizarCacheLeituraCalculada()
    {
        $this->load->model('EstacoesModel');

        $this->db->trans_start();

        $estacoes = $this->EstacoesModel->getEstacoes();

        $registros = [];
        foreach ($estacoes as $estacaoAtual)
        {
            $ultimoRegistroEstacao = $this->EstacoesModel->getUltimoRegistro($estacaoAtual['id'], 60 * 96);
            if ($ultimoRegistroEstacao)
            {
                $registros[] = $ultimoRegistroEstacao;
            }
        }

        $this->db->truncate('leitura_calculada');
        $this->db->insert_batch('leitura_calculada', $registros);

        $this->db->trans_commit();
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
    const TIPO_RAJADA_VENTO    = 'rajada_vento_1h'; //Jaque 15/05 -> #adicionando_rajada_vento 
    const TIPO_PRESSAO_ATMOSFERICA    = 'pressao_atm'; //Jaque 19/09 -> #adicionando_pressao_atm 
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
            self::TIPO_VELOCIDADE_VENTO => 'Velocidade do Vento',
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
