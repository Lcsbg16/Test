<?php

namespace Monitoramento
{

    class EntidadeMonitoramento
    {

        protected $titulo;
        protected $mensagem;

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
    }

}