<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once 'BasePrivateController.php';

class Estacoes extends BasePrivateController
{

    protected $acoesPublicas = ['monitoramentoEstacao'];

    public function mapa()
    {
        $this->load->model('EstacoesModel');

        $variaveisView = [];

        $variaveisView['titulo_pagina'] = 'Mapa de Estações';
        $variaveisView['estacoes']      = $this->EstacoesModel->getEstacoes(true);

        $this->loadSmartyView('Estacoes/mapa', $variaveisView);
    }

    public function mapaMonitoramento()
    {
        $this->load->model('EstacoesModel');

        $variaveisView = [];

        $variaveisView['titulo_pagina'] = 'Mapa de Monitoramento';
        $variaveisView['estacoes']      = $this->EstacoesModel->getEstacoes(true);

        $this->loadSmartyView('Estacoes/mapaMonitoramento', $variaveisView);
    }

    public function monitoramentoIndividual($id_estacao)
    {
        $this->load->model('EstacoesModel');

        $variaveisView = [];

        $variaveisView['titulo_pagina'] = 'Monitoramento individual de estações';
        $variaveisView['estacao']      = $this->EstacoesModel->getEstacao($id_estacao);
        $variaveisView['eventos'] = json_decode(json_encode($this->EstacoesModel->getEventos(4)), true); //Adicionei ao view de monitoramento individual a variavel $eventos, para controlar os ultimos eventos da estação

        $this->loadSmartyView('Estacoes/monitoramentoIndividual', $variaveisView);
    }

    public function getEstacoesGeoJson($idCamada = NULL)
    {
        $this->load->model('EstacoesModel');
        $ids = $this->input->get('ids'); // IDs selecionados
        $atividade = $this->input->get('ativa'); // IDs selecionados
        $atividade = filter_var($atividade, FILTER_VALIDATE_BOOLEAN);

    // Separa os IDs das estações em um array
        $estacoesIds = explode(',', $ids);
        $estacoes = $this->EstacoesModel->getEstacoesGeoJson($idCamada, $atividade, $estacoesIds); //segundo argumento: os IDs das estações

        $this->jsonOutput($estacoes);
    }

    public function monitoramentoEstacao()
    {
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");

        $this->load->model('EstacoesModel');

        $eventos = $this->EstacoesModel->monitorarEstacao(5);
        foreach($eventos as $evento)
        {
            $estacaoId = $evento['estacao_id'];
            $eventoTipo = $evento['tipo_evento_id'];
            $mensagem = $evento['mensagem'];

            $estacao = $this->EstacoesModel->getEstacao($estacaoId);
            $this->EstacoesModel->enviarEmailEvento($estacao['descricao'], $mensagem);
        }
        echo 'OK';
    }
}
