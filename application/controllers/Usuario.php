<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once 'BaseController.php';

/**
 * Controller padrão de ações de usuário comum
 */
class Usuario extends BaseController
{
    public function perfil()
    {
        $variaveisView = [];

        $variaveisView['titulo_pagina'] = 'Perfil';

        $this->loadSmartyView('Usuario/perfil', $variaveisView);
    }

    public function salvarPerfil()
    {
        $this->load->model('UsuarioModel');
        $this->load->model('LoginModel');
        $usuario = $this->LoginModel->getDadosUsuarioLogado();
        $resultado = $this->UsuarioModel->atualizarSenhaUsuario($usuario['id'], $this->input->post('senha'));
        if($resultado)
        {
            $this->flashMessage('Senha alterada com sucesso','success');
        }
        else
        {
            $this->flashMessage('Ocorreu erro durante o salvamento!','danger');
        }
        redirect(base_url('Usuario/perfil'));
        
    }
}
