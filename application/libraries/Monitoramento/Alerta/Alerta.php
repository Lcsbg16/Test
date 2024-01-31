<?php

require_once APPPATH . 'libraries/Monitoramento/Monitoramento.php';

class Alerta extends Monitoramento
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
