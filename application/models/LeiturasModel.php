<?php

require_once 'BaseModel.php';

class LeiturasModel extends BaseModel
{

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

            switch ($escala)
            {
                case FiltrosLeitura::ESCALA_MINUTO:
                     $colunaPeriodo = "DATE_FORMAT(datahora,'%Y-%m-%d %H:%i:00')";
                break;
                
                case FiltrosLeitura::ESCALA_HORA:
                    $colunaPeriodo = "DATE_FORMAT(datahora,'%Y-%m-%d %H:00:00')";
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

            $this->db->select($colunaPeriodo . ' AS periodo, AVG(' . $colunaTipoInformacao . ') AS valor');
            $this->db->group_by($colunaPeriodo);
            $this->db->order_by('periodo', $filtros->getDirecao());
            $resultado = $this->db->get();

            $resultadoArray = $resultado->result_array();
            $retorno        = [];
            foreach ($resultadoArray as $linha)
            {
                $retorno[$linha['periodo']] = $linha['valor'];
            }
            return $retorno;
        }
    }

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
    
    public function getUltimaLeituraRegistrada(FiltrosLeitura $filtros = NULL, $tempoLimite = 2440) //Função criada por jaque 31/07. Motivo: retorno de NULL e utilização de data na função getUltimaSleituraS
    {//Essa função retorna a ultima leitura registrada no banco 
        $this->db->from('leitura');
    
        if ($filtros) {
            $estacoes = $filtros->getEstacoes();
            $tipoInformacao = $filtros->getTipoInformacao();
    
            if (!$tipoInformacao) {
                throw new Exception('É necessário informar o tipo de informação desejada.');
            }
    
            if ($estacoes) {
                $this->db->where_in('estacao_id', $estacoes);
            }
    
            switch ($tipoInformacao) {
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
    
            // Utilize a consulta SQL desejada
            $this->db->select("COALESCE({$colunaTipoInformacao}, 0) AS 'valor', datahora")
            ->order_by("datahora", "DESC")
            ->limit(1);       

    
            $resultado = $this->db->get()->result_array();
            return $resultado;
        }
    }
    

    public function getUltimaTemperaturaMedia()
    {
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
                                    AND datahora >= '" . date('Y-m-d') . " 00:00:00'
                            ORDER BY
                                    datahora DESC
                            LIMIT 1
                        )
                    )
                    AS temperatura_media
                ");
        $linha = $this->db->get()->row_array();
        return $linha['temperatura_media'];
    }

    public function getVolumeChuvaMinimo()
    {
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
                                    AND datahora >= '" . date('Y-m-d') . " 00:00:00'
                            ORDER BY
                                    datahora DESC
                            LIMIT 1
                        )
                    )
                    AS vol_chuva_min
                ");
        $linha = $this->db->get()->row_array();
        return $linha['vol_chuva_min'];
    }

    public function getVolumeChuvaMaxima()
    {
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
                            AND datahora >= '" . date('Y-m-d') . " 00:00:00'
                    ORDER BY
                            datahora DESC
                    LIMIT 1
                )
            )
            AS vol_chuva_max
        ");
        $linha = $this->db->get()->row_array();
        return $linha['vol_chuva_max'];
    }

    public function getTemperaturaMinima()
    {
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
                                    AND datahora >= '" . date('Y-m-d') . " 00:00:00'
                            ORDER BY
                                    datahora DESC
                            LIMIT 1
                        )
                    )
                    AS temperatura_minima
                ");
        $linha = $this->db->get()->row_array();

        return $linha['temperatura_minima'];
    }

    public function getTemperaturaMaxima()
    {
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
                                AND datahora >= '" . date('Y-m-d') . " 00:00:00'
                            ORDER BY
                                    datahora DESC
                            LIMIT 1
                        )
                    )
                    AS temperatura_maxima
                ");
        $linha = $this->db->get()->row_array();

        return $linha['temperatura_maxima'];
    }

    public function getVelocidadeMinima()
    {
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
                                AND datahora >= '" . date('Y-m-d') . " 00:00:00'
                            ORDER BY
                                datahora DESC
                            LIMIT 1
                        )
                    )
                    AS velocidade_minima
                    ");
        $linha = $this->db->get()->row_array();

        return $linha["velocidade_minima"];
    }

    public function getVelocidadeMaxima()
    {
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
                                AND datahora >= '" . date('Y-m-d') . " 00:00:00'
                            ORDER BY
                                    datahora DESC
                            LIMIT 1
                        )
                    )
                    AS velocidade_maxima
                    ");
        $linha = $this->db->get()->row_array();

        return $linha["velocidade_maxima"];
    }

}

class FiltrosLeitura
{

    const ESCALA_ANO    = 'ano';
    const ESCALA_MES    = 'mes';
    const ESCALA_SEMANA = 'semana';
    const ESCALA_DIA    = 'dia';
    const ESCALA_HORA   = 'hora';
    const ESCALA_MINUTO = 'minuto';
    const TIPO_VELOCIDADE_VENTO = 'velocidade_vento';
    const TIPO_DIRECAO_VENTO = 'dir_vento'; //Jaque 31/07 -> monitoramento individual de estações
    const TIPO_TEMPERATURA      = 'temperatura';
    const TIPO_VOLUME_CHUVA     = 'volume_chuva';
    const TIPO_UMIDADE_AR       = 'umidade_ar';
    const TIPO_VOLUME_ACC_CHUVA = 'volume_acc_chuva';
    const DIRECAO_ASC  = 'ASC';
    const DIRECAO_DESC = 'DESC';

    private $estacoes = array();
    private $dataInicial;
    private $dataFinal;
    private $escala   = self::ESCALA_MES;
    private $tipoInformacao;
    private $direcao = self::DIRECAO_ASC;

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
