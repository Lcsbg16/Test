<?php

interface iMonitoramentoObserver
{

    /**
     * @return EntidadeMonitoramento[]
     */
    public function update(): array;
}
