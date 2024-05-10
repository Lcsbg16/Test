<?php

class Conversao
{

    public static function metroPorSegundoParaKmPorHora($velocidadeMPS)
    {
        // Fórmula para converter m/s para km/h
        return $velocidadeMPS * 3.6;
    }

    public function kmPorHoraParaMetroPorSegundo($valorEmKmPorHora)
    {
        // Convertendo km/h para m/s
        $valorEmMetroPorSegundo = $valorEmKmPorHora * 1000 / 3600;

        return $valorEmMetroPorSegundo;
    }
}
