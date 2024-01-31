<?php

require_once APPPATH . 'libraries/Monitoramento/iMonitoramentoObserver.php';

class AlertaPluviometriaObserver implements iMonitoramentoObserver
{

    public function update(): array
    {
        $ci = &get_instance();
    }
}
