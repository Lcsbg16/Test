<?php

class EntidadeMonitoramento
{

    protected $titulo;
    protected $mensagem;
    private $cardHTML;

    public function getTitulo()
    {
        return $this->titulo;
    }

    public function getMensagem()
    {
        return $this->mensagem;
    }

    public function setTitulo($titulo): void
    {
        $this->titulo = $titulo;
    }

    public function setMensagem($mensagem): void
    {
        $this->mensagem = $mensagem;
    }

    public function getCardHTML()
    {
        return $this->cardHTML;
    }

    public function setCardHTML($cardHTML): void
    {
        $this->cardHTML = $cardHTML;
    }
}
