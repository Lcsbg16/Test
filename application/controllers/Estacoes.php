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

        $this->EstacoesModel->getArrayEstacoesComAcesso();

        $estacoes = $this->EstacoesModel->estacoesComAcesso;
        //$imploded = implode(',', $estacoes);


        $variaveisView['estacoes'] = $this->EstacoesModel->getEstacoes(true, $this->EstacoesModel->estacoesComAcesso);

        $this->loadSmartyView('Estacoes/mapa', $variaveisView);
    }

    public function mapaMonitoramento()
    {
        $this->load->model('EstacoesModel');
        $this->load->model('LeiturasModel');

        $variaveisView = [];

        $variaveisView['titulo_pagina'] = 'Mapa de Monitoramento';

        $this->EstacoesModel->getArrayEstacoesComAcesso();
        
        $estacoes = $this->EstacoesModel->estacoesComAcesso;

        //  $imploded = implode(',', $estacoes);

        $variaveisView['estacoes'] = $this->EstacoesModel->getEstacoes(true, $this->EstacoesModel->estacoesComAcesso);
        $variaveisView['camadas']  = FiltrosLeitura::getTodosTiposInformacao();

        $this->loadSmartyView('Estacoes/mapaMonitoramento', $variaveisView);
    }

    public function monitoramentoIndividual($id_estacao)
    {
        $this->load->model('EstacoesModel');

        $variaveisView = [];

        $variaveisView['titulo_pagina'] = 'Monitoramento individual de estações';
        $variaveisView['estacao']       = $this->EstacoesModel->getEstacao($id_estacao);
        $variaveisView['foto_estacao']  = $this->EstacoesModel->obterFotoEstacao($id_estacao); //jaque 01/05
        $variaveisView['eventos']       = json_decode(json_encode($this->EstacoesModel->getEventos(4)), true); //Adicionei ao view de monitoramento individual a variavel $eventos, para controlar os ultimos eventos da estação

        $this->loadSmartyView('Estacoes/monitoramentoIndividual', $variaveisView);
    }

    public function getEstacoesGeoJson($idCamada = NULL)
    {
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");

        $this->load->model('EstacoesModel');
        $ids = $this->input->get('ids'); 

        $atividade = $this->input->get('ativa'); 
        $atividade = filter_var($atividade, FILTER_VALIDATE_BOOLEAN);

        $estacoesIds = explode(',', $ids);
        $estacoes    = $this->EstacoesModel->getEstacoesGeoJson($idCamada, $atividade, $estacoesIds); 
        
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
            $this->enviarEmailEvento($estacaoId, $estacao['descricao'], $mensagem, $eventoTipo);
        }
        echo 'OK';
    }

    public function enviarEmailEvento($estacaoId, $nomeEstacao, $evento, $tipoEvento)
    {
        $this->load->library('EmailUtil');
        $this->load->model('EstacoesModel');

        $result = $this->EstacoesModel->getEmailsUsuariosPorEstacao($estacaoId);

        if (!empty($result))
        {
            $assunto   = "Evento de Estação: $nomeEstacao $evento";
            $mensagem  = "A estação $nomeEstacao está $evento.";
            $remetente = EMAIL_FROM;

            foreach ($result as $row)
            {
                $destinatario = $row->email;
                $this->emailutil->enviarEmail($assunto, $destinatario, $mensagem, $remetente);
            }

            $adminEmail = ADMIN_EMAIL;
            $this->emailutil->enviarEmail($assunto, $adminEmail, $mensagem, $remetente);
        }
    }

public function getLegendaMonitoramento($camada)
{
    switch ($camada) 
    {
        case 'volume_chuva':
            $baseUrl = base_url();
            $mensagem = '<div style="text-align: center;">Pluviometria</div><br><img src="' . $baseUrl . 'assets/uploads/Legendas/pluviometria.png" alt="Legenda Pluviometria" width=450>';
            break;
        default:
            $mensagem = 'Legenda não disponível';
            break;
    }
    $this->jsonOutput($mensagem);
}

    
    

    //Metodo apenas para testar o envio de emails diretamente
    public function testarEnvioEmailEvento()
    {
        $estacaoId   = 45;
        $nomeEstacao = "Lucas";
        $evento      = "online";

        $this->enviarEmailEvento($estacaoId, $nomeEstacao, $evento);
    }

    public function visualizarEstacoes()
    {
        $this->load->model('EstacoesModel');
        $this->load->model('LeiturasModel');
        $this->load->model('OcorrenciasModel');

        $variaveisView = [];

        $variaveisView['titulo_pagina'] = 'Visualizar Estações';
        $variaveisView['qtde_estacoes'] = $this->EstacoesModel->getContagemEstacoes();

        $variaveisView['estacoes'] = $this->EstacoesModel->getEstacaoComDadosMeteorologicos();

        $this->loadSmartyView('Estacoes/visualizarEstacoes', $variaveisView);
    }
}
