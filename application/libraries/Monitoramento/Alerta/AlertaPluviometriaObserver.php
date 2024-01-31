<?php

require_once APPPATH . 'libraries/Monitoramento/iMonitoramentoObserver.php';
require_once APPPATH . 'libraries/Monitoramento/Alerta/Alerta.php';

class AlertaPluviometriaObserver implements iMonitoramentoObserver
{

    public function update(): array
    {
        $ci = &get_instance();
        $ci->load->model('EstacoesModel');
        $ci->load->model('LeiturasModel');

        $alertas = [];

        $coresNiveisAlerta = $ci->LeiturasModel->getCoresNiveisAlertasPluviometria();
        $nomesNiveisAlerta = $ci->LeiturasModel->getNomesNiveisAlertasPluviometria();

        $estacoesAtivas = $ci->EstacoesModel->getEstacoes(true);
        foreach ($estacoesAtivas as $estacaoAtual)
        {
            if ($ultimoRegistro = $ci->EstacoesModel->getUltimoRegistro($estacaoAtual['id']))
            {
                $tipoAlerta = $ci->LeiturasModel->calcularAlertaPluviometria($ultimoRegistro);
                if ($tipoAlerta != LeiturasModel::PLUVIOMETRIA_NIVEL_NORMALIDADE)
                {
                    $alerta   = new Alerta();
                    $alerta->setCor($coresNiveisAlerta[$tipoAlerta]);
                    $alerta->setTitulo('Alerta de Chuva');
                    $alerta->setMensagem('Alerta de Chuva na estação ' . $estacaoAtual['identificador'] . '(' . $estacaoAtual['descricao'] . ') - ' . $nomesNiveisAlerta[$tipoAlerta]);
                    $htmlCard = $ci->loadSmartyView('cardsAlerta/cardAlertaPluviometria', ['mensagem' => $alerta->getMensagem()], true);
                    $alerta->setCardHTML($htmlCard);

                    $alertas[] = $alerta;
                }
            }
        }

        return $alertas;
    }
}
