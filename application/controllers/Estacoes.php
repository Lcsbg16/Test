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
        $this->load->model('LeiturasModel');

        $variaveisView = [];

        $variaveisView['titulo_pagina'] = 'Mapa de Monitoramento';
        $variaveisView['estacoes']      = $this->EstacoesModel->getEstacoes(true);
        $variaveisView['camadas']       = FiltrosLeitura::getTodosTiposInformacao();

        $this->loadSmartyView('Estacoes/mapaMonitoramento', $variaveisView);
    }

    public function monitoramentoIndividual($id_estacao)
    {
        $this->load->model('EstacoesModel');

        $variaveisView = [];

        $variaveisView['titulo_pagina'] = 'Monitoramento individual de estações';
        $variaveisView['estacao']       = $this->EstacoesModel->getEstacao($id_estacao);
        $variaveisView['eventos']       = json_decode(json_encode($this->EstacoesModel->getEventos(4)), true); //Adicionei ao view de monitoramento individual a variavel $eventos, para controlar os ultimos eventos da estação

        $this->loadSmartyView('Estacoes/monitoramentoIndividual', $variaveisView);
    }

    public function getEstacoesGeoJson($idCamada = NULL)
    {
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");

        $this->load->model('EstacoesModel');
        $ids = $this->input->get('ids'); // IDs selecionados

        $atividade = $this->input->get('ativa'); //valor de atividade
        $atividade = filter_var($atividade, FILTER_VALIDATE_BOOLEAN);

        // Separa os IDs das estações em um array
        $estacoesIds = explode(',', $ids);
        $estacoes    = $this->EstacoesModel->getEstacoesGeoJson($idCamada, $atividade, $estacoesIds); //segundo argumento: os IDs das estações
        //var_dump($estacoes);
        /* TODO: Filtrar melhor aqui quais informações serão retornadas no json */
        $this->jsonOutput($estacoes);
    }

    public function monitoramentoEstacao()
    {
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");

        $this->load->model('EstacoesModel');

        $eventos = $this->EstacoesModel->monitorarEstacao(5);
        foreach ($eventos as $evento)
        {
            $estacaoId  = $evento['estacao_id'];
            $eventoTipo = $evento['tipo_evento_id'];
            $mensagem   = $evento['mensagem'];

            $estacao = $this->EstacoesModel->getEstacao($estacaoId);
            $this->enviarEmailEvento($estacao['descricao'], $mensagem);
        }
        echo 'OK';
    }

    public function enviarEmailEvento($estacaoId, $nomeEstacao, $evento)
    {
        $this->load->library('EmailUtil');
        $this->load->model('EstacoesModel');

        $result = $this->EstacaoModel->getEmailsUsuariosPorEstacao($estacaoId);

        // $adminEmail   = ADMIN_EMAIL;
        // $assunto      = "Evento de Estação: $nomeEstacao $evento";
        // $destinatario = $usuario->email;
        // $mensagem     = "A estação $nomeEstacao está $evento.";
        // $remetente    = EMAIL_FROM;

        // $this->emailutil->enviarEmail($assunto, $destinatario, $mensagem, $remetente);

        if (!empty($result)) {
            // Monta um array de emails
            $destinatarios = [];
            foreach ($result as $row) {
                $destinatarios[] = $row->email;
            }
    
            // Configuração do e-mail
            $assunto      = "Evento de Estação: $nomeEstacao $evento";
            $mensagem     = "A estação $nomeEstacao está $evento.";
            $remetente    = EMAIL_FROM;
    
            // Envia o e-mail para todos os destinatários associados à estação
            foreach ($destinatarios as $destinatario) {
                $this->emailutil->enviarEmail($assunto, $destinatario, $mensagem, $remetente);
            }
        }
    }

    public function getLegendaMonitoramento($camada)
    {
        $this->jsonOutput('Legenda não disponível');
    }
}
