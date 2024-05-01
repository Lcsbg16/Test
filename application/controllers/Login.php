<?php

use Facebook\Facebook;

require_once "BaseController.php";

class Login extends BaseController
{

    public function index()
    {

        if ($this->input->post('username'))
        {
            try
            {
                $this->LoginModel->efetuarLogin($this->input->post('username'), $this->input->post('password'));
                if (!empty($url_retorno = $this->input->post('url_retorno')))
                {
                    redirect($url_retorno);
                }
                else
                {
                    redirect('/');
                }
            }
            catch (LoginError $e)
            {
                $this->setAlert($e->getMessage(), 'danger');
            }
        }
        $this->smartylib->assign('url_retorno', $this->input->get('url_retorno'));
        $this->loadSmartyView('Login/index', []);
    }

    public function logout()
    {
        $this->LoginModel->logout();
        redirect('/');
    }

    public function esqueciSenha()
    {
        $this->load->model('LoginModel');

        if ($this->input->post('email'))
        {
            $email   = $this->input->post('email');
            $usuario = $this->LoginModel->getUsuarioPorEmail($email);
            if ($usuario)
            {
                $token             = sha1(mt_rand());
                $_SESSION['token'] = $token; //atriui a variavel session pra poder usar ela novamento na funcao validarToken()
                $criado            = date('Y-m-d H:i:s');
                $expirado          = date('Y-m-d H:i:s', strtotime('+1 day'));

                $this->LoginModel->registrarTokenTrocaSenha($usuario['id'], $email, $token, $criado, $expirado);
                $this->enviarEmailEsqueciSenha($usuario, $email, $token);
                $this->setAlert('Foi enviado um email com instruções para troca de senha', 'success');
            }
            else
            {
                $this->setAlert('Email não encontrado!', 'danger');
            }
        }

        $this->loadSmartyView('Login/esqueciSenha', []);
    }

    public function redefinirSenha()
    {
        // Verifica se o formulário foi submetido
        if ($this->input->server('REQUEST_METHOD') == 'POST')
        {
            $senha = $this->input->post('senha');
            // Verifica se a nova senha foi informada
            if (!empty($senha))
            {
                // Define a nova senha do usuário
                $this->load->model('UsuarioModel');
                $this->load->model('LoginModel');
                $usuario = $this->LoginModel->getUsuariosPorToken($this->input->post('token'));
                $id      = $usuario['usuario_id'];
                $this->UsuarioModel->updateSenha($id, $senha);
                // Redireciona para a página inicial
                $this->loadSmartyView('mensagem_sucesso', ['mensagem_sucesso' => 'Sua senha foi alterada com sucesso!']);
            }
        }
        else
        {
            // Carrega a view de redefinição de senha
            $this->load->model('UsuarioModel');
            $this->loadSmartyView('Login/redefinirSenha', []);
        }
    }

    // public function validarToken()
    // {
    //     //verificar se o form foi enviado e o token nao esta vazio
    //     if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['token']))
    //     {
    //         $tokenEnviado = $_POST['token'];
    //         $tokenArmazenado = $_SESSION['token'];//token da funcao esqueciSenha()
    //         if($tokenEnviado == $tokenArmazenado)
    //         {
    //             echo 'token valido';
    //         }
    //         else
    //         {
    //             echo 'token inválido';
    //         }
    //     }
    // }

    public function enviarEmailEsqueciSenha($usuario, $email, $token)
    {
        $this->load->library('EmailUtil');

        $urlConfirmacao = base_url("Login/redefinirSenha/?email={$email}&token={$token}<br>");

        $assunto       = "Redefinição de senha :)";
        $mensagem      = "Oi, você solicitou a recuperação de senha.<br>";
        $mensagem      .= "Clique no link abaixo para redefinir sua senha<br>";
        $mensagem      .= "<a href=\"{$urlConfirmacao}\">{$urlConfirmacao}</a>";
        $mensagem      .= "Link válido por 1 hora<br>";
        $mensagem      .= "Caso não tenha solicitado a redefinição de senha, por favor, ignore este e-mail.<br>";
        $mensagem      .= "Sistema de Telemetria<br><br>";
        $destinatario  = $email;
        $nomeRemetente = $usuario;
        $replyTo       = $email;
        $this->emailutil->enviarEmail($assunto, $destinatario, $mensagem);

        // echo "Enviado com sucesso para $email";
    }
}
