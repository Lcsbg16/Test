<?php

if (!defined('BASEPATH'))
{
    exit('No direct script access allowed');
}

require_once 'BaseController.php';

/**
 * Base class for all private controllers (available just for logged users)
 *
 * @property AlertaSubject $alertasubject
 */
abstract class BasePrivateController extends BaseController
{

    /**
     *
     * @var LoginModel
     */
    public $LoginModel;

    /**
     * Permissão de acesso ao controller. Use permissão 'GERAL' para liberar acesso para qualquer usuário logado
     *
     * @var string
     */
    protected $permissaoAcesso = 'GERAL';

    /**
     *  Ações a serem liberadas como públicas
     *
     * @var string[]
     */
    protected $acoesPublicas = [];

    public function __construct()
    {
        parent::__construct();

        if (!in_array($this->router->fetch_method(), array_merge(['logout'], $this->acoesPublicas))) // Somente para o caso de logout na sessão local, não carrega o CAS
        {
            try
            {
                $this->checkLogin();
                $this->_checaPermissaoAcesso();
            }
            catch (Exception $e)
            {
                $this->loadSmartyView('mensagem_erro', array('mensagem_erro' => $e->getMessage()));
                return;
            }
        }
        $this->carregarAlertas();
    }

    private function carregarAlertas()
    {
        $this->load->library('Monitoramento/Alerta/AlertaSubject');

        $this->load->library('Monitoramento/Alerta/AlertaPluviometriaObserver');
        $this->alertasubject->attach($this->alertapluviometriaobserver);
    }

    protected function checaPermissaoUsuarioLogado($permissao)
    {
        $this->load->model('LoginModel');
        return $this->LoginModel->checaPermissaoUsuarioLogado($permissao);
    }

    private function _checaPermissaoAcesso()
    {
        if (!$this->checaPermissaoUsuarioLogado($this->permissaoAcesso))
        {
            echo $this->loadSmartyView(
                    'mensagem_erro', array('mensagem_erro' => "Você não possui acesso a este recurso. Caso tenha dúvidas quanto a isso, por favor contate o administrador."), true
            );
            exit();
        }
    }

    /**
     * Faz logout apenas na sessão local do Code Igniter
     */
    public function logout()
    {
        $this->LoginModel->logout();
        if ($url_redir = $this->input->get('url_retorno'))
        {
            header('Location:' . $url_redir);
        }
    }

    protected function getDadosUsuarioLogado()
    {
        return $this->LoginModel->getDadosUsuarioLogado();
    }

    public function testeEnvioEmail()
    {
        $this->load->library('EmailUtil');
        $this->emailutil->enviarEmail("Teste de envio", "adriano.php4@gmail.com", "Isto é um teste.", EMAIL_FROM);
    }

    public function adicionaFiltroAcessoEstacao($crud, $fild, $filtrar = false)
    {

        $this->load->model('EstacoesModel');

        $this->EstacoesModel->getArrayEstacoesComAcesso($filtrar);

        $estacoes = $this->EstacoesModel->estacoesComAcesso;

        $parametro_filtro_estacoes = CONTROLE_ACESSO_ESTACAO;

        if (!$this->checaPermissaoUsuarioLogado('EDITAR_TODAS_AS_ESTACOES') && ($parametro_filtro_estacoes == "true" OR $filtrar == true))
        {
            $imploded = implode(',', $estacoes);

            $crud->where($fild . ' in ', '(' . $imploded . ')', false);
        }


        return $crud;
    }
}
