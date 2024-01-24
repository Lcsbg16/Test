<?php

class MonitoramentoSubject extends CI_Model
{

    /**
     *
     * @var iMonitoramentoObserver[]
     */
    private $observers = [];

    public function attach(iMonitoramentoObserver $observer)
    {
        $this->observers[] = $observer;
    }

    public function detach(int $indice)
    {
        unset($this->observers[$indice]);
    }

    public function getNotificacoes()
    {
        return $this->notifyObservers();
    }

    /**
     *
     * @return EntidadeMonitoramento[][]
     */
    public function notifyObservers()
    {
        $entidadesMonitoramento = [];
        foreach ($this->observers as $o_atual)
        {
            $entidadesMonitoramento = array_merge($entidadesMonitoramento, $o_atual->update());
        }

        return $entidadesMonitoramento;
    }
}
