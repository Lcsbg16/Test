<?php

namespace Monitoramento\Alerta
{

    class Alerta extends Monitoramento\Monitoramento
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

}
