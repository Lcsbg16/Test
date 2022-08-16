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

}
