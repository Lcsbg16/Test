<?php

/**
 * Lê dados de estações publicadas no Weather.com
 */
class Weathercom
{

    const BASE_URL = 'https://api.weather.com/v2/pws/observations/current';

    /**
     * Sistema de Medidas
     *
     * e = English units
     * m = Metric units
     * h = Hybrid units (UK)
     * s = Metric SI units
     *
     * @var string
     */
    private $units = 'm';

    public function getDadosEstacao($stationId, $apiKey, $format = 'json')
    {
        $urlRequisicao = self::BASE_URL . '?stationId=' . $stationId . '&format=' . $format . '&units=' . $this->units . '&apiKey=' . $apiKey;
        if ($dadosLeitura  = file_get_contents($urlRequisicao))
        {
            $dadosLeituraArray = json_decode($dadosLeitura, true);
            return $dadosLeituraArray['observations'][0];
        }
        else
        {
            throw WeatherComException('Erro ao recuperar informaçÕes da estação via Weather.com.');
        }
    }

    public function getUnits()
    {
        return $this->units;
    }

    public function setUnits($units): void
    {
        $this->units = $units;
    }
}

class WeatherComException extends Exception
{

}
