<?php


interface FonteDadosLeitura 
{
  

    public function getCoresNiveisAlertasPluviometria();

    public function getNomesNiveisAlertasPluviometria();

    public function inserirLeitura($estacaoId, $dadosLeitura);
    public function inserirLeituraAPI($estacaoId, $leitura_id, $postData);

    public function inserirLeituraValor($leituraId, $tag, $value);
   
    public function inserirUltimaLeitura($estacaoId, $leituraId, $tag, $value);

    public function filtrarEstacoesComAcesso($fild);

    public function getArrayEstacoesComAcesso();

    public function calcularEstatisticasPorPeriodo(FiltrosLeitura $filtros);
      

    public function getLeiturasPorEscala(FiltrosLeitura $filtros = NULL, $retornarTudo = true);

    public function getAcumuladoChuvaPorPeriodoGeral($cacheBD = true); //Acumulos de chuva + descrição da estação + temperatura // jaque
    

    /**
     * Retorna a última leitura obtida de cada estação
     *
     * @param FiltrosLeitura $filtros Filtros para obtenção das leituras
     * @param int $tempoLimite Tempo limite (em minutos) a considerar na obtenção das leituras. Leituras mais antigas serão descartadas.
     * @return array
     * @throws Exception
     */
    public function getUltimasLeituras(FiltrosLeitura $filtros = NULL, $tempoLimite = 2440);

    /**
     * Essa função retorna a ultima leitura registrada no banco
     *
     *
     * @param FiltrosLeitura $filtros
     * @param int $tempoLimite
     * @return array
     * @throws Exception
     */
    public function getUltimaLeituraRegistrada(FiltrosLeitura $filtros = NULL);


    public function getUltimaTemperaturaMedia();

    public function getVolumeChuvaMinimo();

    public function getVolumeChuvaMaxima();

    public function getTemperaturaMinima();

    public function getTemperaturaMaxima();

    public function getVelocidadeMinima();

    public function getVelocidadeMaxima();

    public function calcularAlertaPluviometria($leitura);

    public function getAllLeituras();

    public function exportarLeiturasParaCSV($filtros);

    public function atualizarCacheLeituraCalculada();
    
}
