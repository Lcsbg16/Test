<?php

if (!defined('BASEPATH'))
{
    exit('No direct script access allowed');
}

/**
 * Classe base para os controllers do sistema
 *
 * @property EstacoesModel $EstacoesModel
 * @property LoginModel $LoginModel
 * @property OcorrenciasModel $OcorrenciasModel
 */
abstract class BaseController extends CI_Controller
{

    /**
     *
     * @var SmartyLib
     */
    public $smartylib;
    protected $selected_site_menu = 'home';
    private $alertas              = [];

    public function __construct()
    {
        parent::__construct();
        $this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));

        $this->load->model('LoginModel');
        $this->limparCacheBancoDeDados();
    }

    private function limparCacheBancoDeDados()
    {
        // Define o intervalo em segundos para limpar o cache (por exemplo, a cada 3600 segundos = 1 hora)
        $interval_seconds = 50;

        // Obtém o tempo da última limpeza do cache a partir do cache
        $ultima_limpeza_cache_bd = $this->cache->get('ultima_limpeza_cache_bd');

        // Verifica se é necessário limpar o cache com base no tempo armazenado no cache
        if (!$ultima_limpeza_cache_bd || (time() - $ultima_limpeza_cache_bd) > $interval_seconds)
        {
            clear_database_cache();

            // Atualiza o tempo da última limpeza do cache no cache
            $this->cache->save('ultima_limpeza_cache_bd', time(), $interval_seconds);
        }
    }

    protected function flashMessage($message, $type = 'info')
    {
        $this->session->set_flashdata('alert_' . $type, $message);
    }

    protected function setAlert($message, $type = 'info')
    {
        $this->alertas[$type] = $message;
    }

    protected function gerarVariaveisViewPadrao()
    {
        $view_variables = array();

        $this->load->model('MenusModel');

        $view_variables['menus']            = $this->MenusModel->getMenus();
        $view_variables['usuario_logado']   = $this->LoginModel->getDadosUsuarioLogado();
        $view_variables['menu_selecionado'] = $this->getSelectedMenu();
        $view_variables['flashdata']        = $this->session->flashdata();
        $view_variables['alertas']          = $this->alertas;

        return $view_variables;
    }

    protected function getSelectedMenu()
    {
        return $this->selected_site_menu;
    }

    protected function loadView($view, $variaveis_view = array(), $return = false)
    {
        $variaveis_view = array_merge($variaveis_view, $this->gerarVariaveisViewPadrao());

        return $this->load->view($view, $variaveis_view, $return);
    }

    public function loadSmartyView($view, $variaveis_view = array(), $return = false)
    {
        $variaveis_view = array_merge($variaveis_view, $this->gerarVariaveisViewPadrao());

        return $this->smartylib->view($view, $variaveis_view, $return);
    }

    protected function enviarCabecalhosPdf($nome_arquivo)
    {
        header('Content-type: application/pdf');
        header('Cache-Control: public, must-revalidate, max-age=0');
        header('Pragma: public');
        header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Content-Disposition: inline; filename="' . $nome_arquivo . '";');
    }

    protected function htmlParaPdf($conteudo, $nome_arquivo, $orientacaoPagina = 'P')
    {
        $this->enviarCabecalhosPdf($nome_arquivo);

        $pdf          = new mPDF('', 'A4', 0, $default_font = '', 10, 10, 10, 10, 10, 10, $orientacaoPagina);
        $pdf->AddPage('L');

        $pdf->SetAuthor();
//        $pdf->setAutoBottomMargin = 'pad';
        $pdf->WriteHTML($conteudo);
        $pdf->Output($nome_arquivo, 'I');
    }

    /**
     *
     */
    public function checkLogin()
    {
        if (!$this->LoginModel->usuarioEstaLogado())
        {
            redirect('Login/?url_retorno=' . urlencode(current_url()));
        }
    }

    public function enviarEmail($assunto, $destinatario, $mensagem, $nomeRemetente, $replyTo, $remetente)
    {
        $this->load->library('email');
        $this->load->library('EmailUtil'); //biblioteca que recebe o email que o usuario digitou
        $this->load->library('SmartyLib');
        $this->email->clear(TRUE);
        $this->email->to($destinatario);
        $this->email->subject($assunto);
        $this->email->message($mensagem);
        $this->email->set_mailtype('html');
        $this->email->setFrom($remetente); // adiciona o endereço do remetente
        $this->email->send();
        $this->smartylib->assign('mensagem', $mensagem);
        $mensagemFinal = $this->smartylib->view('BaseController/enviarEmailEsqueciSenha', [], true);

        if ($nomeRemetente)
        {
            $this->EmailUtil->setNomeRemetente($nomeRemetente);
        }

        if ($replyTo)
        {
            $this->EmailUtil->setReplyTo($replyTo);
        }

        $this->EmailUtil->enviarEmail($assunto, $destinatario, $mensagemFinal, $nomeRemetente, $replyTo);
        //echo "Enviado com sucesso para $email";
    }

    public function jsonOutput($output)
    {
        header('Content-type: application/json');
        $this->load->view('json_output', ['output' => $output]);
    }
}

class ViolacaoDeSeguranca extends Exception
{

}
