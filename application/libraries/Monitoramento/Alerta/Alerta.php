<?php

require_once APPPATH . 'libraries/Monitoramento/EntidadeMonitoramento.php';

class Alerta extends EntidadeMonitoramento
{

    private $cor;

    public function getCor()
    {
        return $this->cor;
    }

    public function setCor($cor): void
    {
        $this->cor = $cor;
    }
}
