<?php

require_once 'FonteDadosLeitura.php';
require_once 'LeiturasModelEstatica.php';

class LeiturasModel 
{
    
    private $fonteDados;

   
    public function __construct($fonte)
    {
       
        if($fonte == NULL){
            die('Necessário informar a fonte de dados desejada.');
        }
        switch($fonte){
            case 'estatica' :
                $this->fonteDados = new LeiturasModelEstatica();
                break;
            case 'dinamica' :
                echo 'dinamica';
                break;
            case 'mongo' :
                echo 'mongo';
                break;
            default :
            die('Necessário informar uma fonte de dados valida.');
            

        }

    }

    public function getCoresNiveisAlertasPluviometria()
    {
        return $this->fonteDados->getCoresNiveisAlertasPluviometria();
    }

    public function getNomesNiveisAlertasPluviometria()
    {
        return $this->fonteDados->getNomesNiveisAlertasPluviometria();
    }

    public function inserirLeitura($estacaoId, $dadosLeitura)
    {
        return  $this->fonteDados->inserirLeitura($estacaoId, $dadosLeitura);
    }
    public function inserirLeituraAPI($estacaoId, $leitura_id, $postData)
    {
        $this->fonteDados->inserirLeituraAPI($estacaoId, $leitura_id, $postData);
    }

    public function inserirLeituraValor($leituraId, $tag, $value)
    {
        return  $this->fonteDados->inserirLeituraValor($leituraId, $tag, $value);
    }

    public function inserirUltimaLeitura($estacaoId, $leituraId, $tag, $value)
    {

        return $this->fonteDados->inserirUltimaLeitura($estacaoId, $leituraId, $tag, $value);
    }

    private function filtrarEstacoesComAcesso($fild)
    {
        $this->fonteDados->filtrarEstacoesComAcesso($fild);
    }

    private function getArrayEstacoesComAcesso()
    {
        $this->fonteDados->getArrayEstacoesComAcesso();
    }

    public function calcularEstatisticasPorPeriodo(FiltrosLeitura $filtros)
    {
        return  $this->fonteDados->calcularEstatisticasPorPeriodo($filtros);
      
    }


    public function getLeiturasPorEscala(FiltrosLeitura $filtros = NULL, $retornarTudo = true)
    {
       
        return $this->fonteDados->getLeiturasPorEscala($filtros, $retornarTudo);
           
    }

    public function getAcumuladoChuvaPorPeriodoGeral($cacheBD = true) //Acumulos de chuva + descrição da estação + temperatura // jaque
    {
        return $this->fonteDados->getAcumuladoChuvaPorPeriodoGeral($cacheBD);
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
        return $this->fonteDados->getUltimasLeituras($filtros, $tempoLimite);
      
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
        return $this->fonteDados->getUltimaLeituraRegistrada($filtros);    
    }


    public function getUltimaTemperaturaMedia()
    {
        return $this->fonteDados->getUltimaTemperaturaMedia();
    }

    public function getVolumeChuvaMinimo()
    {
        return $this->fonteDados->getVolumeChuvaMinimo();
    }

    public function getVolumeChuvaMaxima()
    {
        return $this->fonteDados->getVolumeChuvaMaxima();
    }

    public function getTemperaturaMinima()
    {
        return $this->fonteDados->getTemperaturaMinima();
    }

    public function getTemperaturaMaxima()
    {
        return $this->fonteDados->getTemperaturaMaxima();
    }

    public function getVelocidadeMinima()
    {
        return $this->fonteDados->getVelocidadeMinima();
    }

    public function getVelocidadeMaxima()
    {
        return $this->fonteDados->getVelocidadeMaxima();
    }

    public function calcularAlertaPluviometria($leitura)
    {
        return $this->fonteDados->calcularAlertaPluviometria($leitura);
    }

    public function getAllLeituras()
    {
        return $this->fonteDados->getAllLeituras();
    }

    public function exportarLeiturasParaCSV($filtros)
    {
        return $this->fonteDados->exportarLeiturasParaCSV($filtros);
    }

    public static function converterVelocidadeVentoKMH($velocidadeMS)
    {
        return number_format($velocidadeMS * 3.6, 1);
    }

    public function atualizarCacheLeituraCalculada()
    {
        $this->fonteDados->atualizarCacheLeituraCalculada();
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
