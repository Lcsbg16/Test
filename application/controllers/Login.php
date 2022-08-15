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
        $this->loadSmartyView('Login/index');
    }

    public function loginFacebook()
    {
        $this->load->model('UsuarioModel');

        $fb = new Facebook([
            'app_id'                => FACEBOOK_APP_ID,
            'app_secret'            => FACEBOOK_SECRET_KEY,
            'default_graph_version' => 'v2.10',
        ]);

        // Checa o login
        $helper = $fb->getJavaScriptHelper();

        try
        {
            $accessToken = $helper->getAccessToken();
        }
        catch (Facebook\Exception\ResponseException $e)
        {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        }
        catch (Facebook\Exception\SDKException $e)
        {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        if (!isset($accessToken))
        {
            echo 'No cookie set or no OAuth data could be obtained from cookie.';
            exit;
        }

        // Usuário logado
        $token = $this->LoginModel->setFacebookAccessToken($accessToken);

        // Pega os dados do usuário
        try
        {
            // Returns a `Facebook\Response` object
            $response = $fb->get('/me?fields=id,name,email', $token);
        }
        catch (Facebook\Exception\ResponseException $e)
        {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        }
        catch (Facebook\Exception\SDKException $e)
        {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        $usuarioFb = $response->getGraphUser();
        $this->LoginModel->setFacebookUserData($usuarioFb);

        #var_dump($usuarioFb);

        if ($usuario = $this->UsuarioModel->getUsuarioPorFacebookId($usuarioFb['id']))
        {
            $this->LoginModel->setUsuarioLogado($usuario->id);
        }
        elseif ($usuario = $this->UsuarioModel->getUsuarioPorEmail($usuarioFb['email'])) // Não encontrou o id, mas encontrou o e-mail
        {
            $this->UsuarioModel->atualizaFacebookId($usuario, $usuarioFb['id']);
            $this->LoginModel->setUsuarioLogado($usuario->id);
        }
        else
        {
            redirect('cadastro');
        }

        if (!empty($url_retorno = $this->input->get('url_retorno')))
        {
            redirect($url_retorno);
        }
        else
        {
            redirect('/');
        }
    }

    public function logout()
    {
        $this->LoginModel->logout();
        redirect('/');
    }

}
