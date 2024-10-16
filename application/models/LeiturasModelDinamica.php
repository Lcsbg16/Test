<?php

require_once 'BaseModel.php';

class LeiturasModelDinamica extends LeiturasModelAbstract
{


    public function __construct()
    {
        $this->load->model('EstacoesModel');

        parent::__construct();
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

                    case FiltrosLeitura::TIPO_PRESSAO_ATMOSFERICA:
                        $colunaTipoInformacao = 'pressao_atm'; // #adicionando_pressao atm
                        break;

                case FiltrosLeitura::TIPO_UMIDADE_AR:
                    $colunaTipoInformacao = 'umidade_ar';
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
                $this->db->select($colunaPeriodo . ' AS periodo, ' . $agregador . '('.$colunaTipoInformacao.') AS valor');
            }
            else 
            {
                $this->db->from('leitura');  //#adicionando_rajada_vento .
                $this->db->join('leitura_valor lv','lv.leitura_id = leitura.id');
                $this->db->where('lv.leitura_dimensao_tag', $colunaTipoInformacao);
                $this->db->select($colunaPeriodo . ' AS periodo, ' . $agregador . '(valor_texto) AS valor');
             
            }
           
            
            //$this->db->select($colunaPeriodo . ' AS periodo, ' . $agregador . '(valor_texto) AS valor');
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

        $this->db->from('leitura_valor');
        $this->db->select('leitura_dimensao_tag');
        $this->db->group_by('leitura_dimensao_tag');
        $resultado = $this->db->get();
        $res = $resultado->result_array();

                    
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
                            E.longitude as estacao_longitude
                           ');
                        foreach ($res as $key => $value) {
                
                            $varFunc = '';
                            switch ($value['leitura_dimensao_tag']) {
                                case 'temepratura':
                                    $varFunc = 'AVG';
                                    break;
                                    case 'umidade_ar':
                                        $varFunc = 'AVG';
                                        break;
                                        case 'velocidade_vento':
                                            $varFunc = 'AVG';
                                            break;
                                            case 'rajada_vento':
                                                $varFunc = 'MAX';
                                                break;
                                                case 'volume_chuva':
                                                    $varFunc = 'SUM';
                                                    break;
                                                    case 'datahora_cadastro':
                                                        $varFunc = 'MAX';
                                                        break;
                                default:
                                    # code...
                                    break;
                            }
                            if($varFunc != ''){
                            $this->db->select('(select '.$varFunc.'(lv.valor_texto) 
                            from leitura_valor lv where`lv`.`leitura_dimensao_tag` = '.$value['leitura_dimensao_tag'].' 
                            and lv.leitura_id = L.id) as '.$value['leitura_dimensao_tag']);
                            }

                        }
                        $this->db->select('(select AVG(lv.valor_texto) 
                        from leitura_valor lv where`lv`.`leitura_dimensao_tag` = \'velocidade_vento\' 
                        and lv.leitura_id = L.id) as rajada_vento' );
                        

            $this->db->join('estacao E', 'L.estacao_id = E.id');
            //$this->db->join('leitura_valor LV', 'L.id = LV.leitura_id');
           // $this->db->where('LV.leitura_dimensao_tag', $value['leitura_dimensao_tag']);
            $this->db->group_by(
                    $colunaPeriodo );
            $this->db->order_by('periodo', 'ASC');
            $resultado = $this->db->get();

            //echo $this->db->last_query();

           if ($retornarTudo)
            {
                $r =  $resultado->result_array();
                var_dump($r);
                return $r;

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

                default:
                    throw new Exception('É necessário informar o tipo de informação desejada.');
            }

            $this->db->from('estacao E')
                    ->select("

                    (
                        SELECT
                                valor_texto
                        FROM
                                leitura L1
                        JOIN ultima_leitura_valor ulv on ulv.leitura_id = L1.id
                        WHERE
                                ulv.leitura_dimensao_tag = {$colunaTipoInformacao}
                                AND L1.estacao_id = E.id
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
                $this->db->where_in('leitura.estacao_id', $estacoes);
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
                $this->db->from('leitura');  
                $this->db->join('ultima_leitura_valor ulv', 'leitura.id = ulv.leitura_id');
                $this->db->where('leitura_dimensao_tag', $colunaTipoInformacao);
                $this->db->select("COALESCE(valor_texto, 0) AS 'valor', datahora")
                ->order_by("datahora", "DESC")
                ->limit(1);

                $resultado = $this->db->get()->result_array();
               // var_dump($this->db->last_query());
                return $resultado;
            }

           
        }
    }


    public function getUltimaTemperaturaMedia(){

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
                                    valor_texto
                            FROM
                                    leitura L1
                            JOIN    ultima_leitura_valor ulv on ulv.leitura_id = L1.id
                            WHERE
                                    ulv.leitura_dimensao_tag = 'temperatura'
                                    AND L1.estacao_id = E.id
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
                            valor_texto
                            FROM
                                    leitura L1
                                    JOIN    ultima_leitura_valor ulv on ulv.leitura_id = L1.id
                            WHERE
                                    ulv.leitura_dimensao_tag = 'volume_chuva'
                                    AND L1.estacao_id = E.id
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
                            valor_texto
                    FROM
                            leitura L1
                            JOIN    ultima_leitura_valor ulv on ulv.leitura_id = L1.id
                    WHERE
                            ulv.leitura_dimensao_tag = 'volume_chuva'
                            AND L1.estacao_id = E.id
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
                                    valor_texto
                            FROM
                                    leitura L1
                            JOIN    ultima_leitura_valor ulv on ulv.leitura_id = L1.id           
                            WHERE
                                    ulv.leitura_dimensao_tag = 'temperatura'
                                    AND L1.estacao_id = E.id
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
                                    valor_texto
                            FROM
                                    leitura L1
                            JOIN    ultima_leitura_valor ulv on ulv.leitura_id = L1.id   
                            WHERE
                                ulv.leitura_dimensao_tag = 'temperatura'
                                AND L1.estacao_id = E.id
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
                                    valor_texto
                            FROM
                                leitura L1
                            JOIN    ultima_leitura_valor ulv on ulv.leitura_id = L1.id   
                            WHERE
                                ulv.leitura_dimensao_tag = 'velocidade_vento'
                                AND L1.estacao_id = E.id
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
                                    valor_texto
                            FROM
                                    leitura L1
                            JOIN    ultima_leitura_valor ulv on ulv.leitura_id = L1.id   
                             WHERE
                                ulv.leitura_dimensao_tag = 'velocidade_vento'
                                AND L1.estacao_id = E.id
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


    public function getAllLeituras()
    {
        $this->db->select('leitura.id, leitura.datahora, estacao.identificador as estacao_identificador, 
        estacao.descricao as estacao_descricao, estacao.endereco as estacao_edereco, 
        estacao.latitude as estacao_latitude, estacao.longitude as estacao_longitude,
        leitura.temperatura, leitura.umidade_ar, leitura.velocidade_vento, leitura.dir_vento, 
        leitura.volume_chuva,datahora_cadastro, lv.*'); // Adiciona os campos de estacao
        $this->db->from('leitura');
        $this->db->join('leitura_valor lv', 'leitura.id = lv.leitura_id');
        $this->db->join('estacao', 'leitura.estacao_id = estacao.id');
        //$this->db->order_by('leitura.datahora', 'DESC');
        $this->db->order_by('leitura.id', 'ASC');
        $query = $this->db->get();
        $result = $query->result_array();
       // var_dump($this->db->last_query());
        return $result;
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
            $leitura['velocidade_vento'] = self::converterVelocidadeVentoKMH((float)$leitura['velocidade_vento']);
            $leitura['rajada_vento']     = self::converterVelocidadeVentoKMH((float)$leitura['rajada_vento']);

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
}
   