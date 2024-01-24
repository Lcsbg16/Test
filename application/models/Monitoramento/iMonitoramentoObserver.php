<?php

namespace Monitoramento
{

    interface iMonitoramentoObserver
    {

        /**
         * @return EntidadeMonitoramento[]
         */
        public function update();
    }

}