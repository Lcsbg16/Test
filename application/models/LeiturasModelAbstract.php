<?php

require_once 'BaseModel.php';
require_once 'FonteDadosLeitura.php';

abstract class LeiturasModelAbstract extends BaseModel implements FonteDadosLeitura
{

    const PLUVIOMETRIA_NIVEL_NORMALIDADE   = 'normalidade';
    const PLUVIOMETRIA_NIVEL_ATENCAO       = 'atencao';
    const PLUVIOMETRIA_NIVEL_ALERTA        = 'alerta';
    const PLUVIOMETRIA_NIVEL_ALERTA_MAXIMO = 'alerta_maximo';

    public $estacoesComAcesso = array();

    public function __construct()
    {
        $this->load->model('EstacoesModel');
 
    }

    public function getCoresNiveisAlertasPluviometria()
    {
        return [
            LeiturasModelAbstract::PLUVIOMETRIA_NIVEL_ATENCAO       => COR_PLUVIOMETRIA_NIVEL_ATENCAO,
            LeiturasModelAbstract::PLUVIOMETRIA_NIVEL_ALERTA        => COR_PLUVIOMETRIA_NIVEL_ALERTA,
            LeiturasModelAbstract::PLUVIOMETRIA_NIVEL_ALERTA_MAXIMO => COR_PLUVIOMETRIA_NIVEL_ALERTA_MAXIMO,
            LeiturasModelAbstract::PLUVIOMETRIA_NIVEL_NORMALIDADE   => COR_PLUVIOMETRIA_NIVEL_NORMALIDADE
        ];
    }

    public function getNomesNiveisAlertasPluviometria()
    {
        return [
            LeiturasModelAbstract::PLUVIOMETRIA_NIVEL_ATENCAO       => 'nível de atenção',
            LeiturasModelAbstract::PLUVIOMETRIA_NIVEL_ALERTA        => 'nível de alerta',
            LeiturasModelAbstract::PLUVIOMETRIA_NIVEL_ALERTA_MAXIMO => 'nível de alerta máximo',
            LeiturasModelAbstract::PLUVIOMETRIA_NIVEL_NORMALIDADE   => 'nível de normalidade'
        ];
    }

    public abstract function inserirLeitura($estacaoId, $dadosLeitura);
    public abstract function inserirLeituraAPI($estacaoId, $leitura_id, $postData);

    public abstract function inserirLeituraValor($leituraId, $tag, $value);
   

    public abstract function inserirUltimaLeitura($estacaoId, $leituraId, $tag, $value);

    public function filtrarEstacoesComAcesso($fild)
    {
        $this->EstacoesModel->filtrarEstacoesComAcesso($fild);
    }

    public function getArrayEstacoesComAcesso()
    {

        $this->EstacoesModel->getArrayEstacoesComAcesso();
    }

    public abstract function calcularEstatisticasPorPeriodo(FiltrosLeitura $filtros);
      

    public abstract function getLeiturasPorEscala(FiltrosLeitura $filtros = NULL, $retornarTudo = true);

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
    public abstract function getUltimasLeituras(FiltrosLeitura $filtros = NULL, $tempoLimite = 2440);

    /**
     * Essa função retorna a ultima leitura registrada no banco
     *
     *
     * @param FiltrosLeitura $filtros
     * @param int $tempoLimite
     * @return array
     * @throws Exception
     */
    public abstract function getUltimaLeituraRegistrada(FiltrosLeitura $filtros = NULL);


    public abstract function getUltimaTemperaturaMedia();

    public abstract function getVolumeChuvaMinimo();

    public abstract function getVolumeChuvaMaxima();

    public abstract function getTemperaturaMinima();

    public abstract function getTemperaturaMaxima();

    public abstract function getVelocidadeMinima();

    public abstract function getVelocidadeMaxima();

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

    public abstract function getAllLeituras();

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
