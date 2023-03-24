<?php

require_once 'BaseModel.php';

/**
 * Gerenciador de login
 *
 */
class LoginModel extends BaseModel
{

    /**
     *
     * @return User
     */
    public function getDadosUsuarioLogado()
    {
        $user = unserialize($this->session->dadosUsuarioLogado);

        return $user;
    }

    public function setUsuarioLogado($usuarioId)
    {
        $this->load->model('UsuarioModel');

        $usr                               = $this->UsuarioModel->getUsuario($usuarioId);
        $this->session->dadosUsuarioLogado = serialize($usr);
        $this->session->usuarioEstaLogado  = true;
        return $usr;
    }

    public function efetuarLogin($usuario, $senha)
    {
        $this->load->model('UsuarioModel');
        $usr = $this->UsuarioModel->checarLoginValido($usuario, $senha);

        if (!$usr)
        {
            throw new LoginError('Usuário ou senha não conferem.');
        }

        return $this->setUsuarioLogado($usr['id']);
    }

    public function usuarioEstaLogado()
    {
        return $this->session->usuarioEstaLogado;
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('/');
    }

    public function checaPermissaoUsuarioLogado($permissao)
    {
        $ci = & get_instance();

        $ci->load->model('PermissoesModel');
        $ci->load->model('UsuarioModel');

        $dados_usuario = $this->getDadosUsuarioLogado();

        if (isset($dados_usuario['id']))
        {
            $grupos = $this->UsuarioModel->getGruposUsuario($dados_usuario['id']);
        }
        else
        {
            $grupos = [];
        }

        return $permissao == 'GERAL' || $ci->PermissoesModel->checaPermissaoGrupos($permissao, $grupos);
    }

    

}

   


class LoginError extends Exception
{

}
