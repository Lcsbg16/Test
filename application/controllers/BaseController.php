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
        $this->load->model('LoginModel');
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

        $view_variables['site_menu']      = $this->MenusModel->getMenus();
        $view_variables['usuario_logado'] = $this->LoginModel->getDadosUsuarioLogado();
        $view_variables['selected_menu']  = $this->getSelectedMenu();
        $view_variables['flashdata']      = $this->session->flashdata();
        $view_variables['alertas']        = $this->alertas;

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

    protected function loadSmartyView($view, $variaveis_view = array(), $return = false)
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

    public function enviarEmail($assunto, $destinatario, $mensagem, $nomeRemetente = NULL, $replyTo = NULL)
    {
        $this->load->library('EmailUtil');
        $this->smartylib->assign('mensagem', $mensagem);
        $mensagemFinal = $this->smartylib->view('BaseController/email_padrao', [], true);

        if ($nomeRemetente)
        {
            $this->emailutil->setNomeRemetente($nomeRemetente);
        }

        if ($replyTo)
        {
            $this->emailutil->setReplyTo($replyTo);
        }

        $this->emailutil->enviarEmail($assunto, $destinatario, $mensagemFinal);
    }

}

class ViolacaoDeSeguranca extends Exception
{

}
