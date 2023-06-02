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

    public function esqueciSenha($email,$token, $criado, $expirado){ //insercao de registro
        $data = array(
            'email' => $email,
            'token' => $token,
            'criado' => $criado,
            'expirado' => $expirado
        );
        $this->db->insert('troca_de_senha', $data);
        return $this->db->affected_rows()==1;
    }

    public function getUsuariosPorToken($token){
        $this->db->select('usuario_id');
        $this->db->from('troca_de_senha');
        $this->db->where('token',$token);
        $this->db->where('expirado>',date('Y-m-d H:i:s'));//expirado maior q a data atual
        $query=$this->db->get(); //armazena o resultado na $query
        if($query->num_rows() == 1){
            return $query->row_array();
        }
        else
        {
            return array();
        }
    }

    public function registrarTokenTrocaSenha($usuario_id, $email, $token, $criado, $expirado)
    {
        $dados = array(
            'usuario_id' => $usuario_id,
            'email' => $email,
            'token' => $token,
            'criado' => $criado,
            'expirado' => $expirado
        );

        $this->db->insert('troca_de_senha', $dados);
    }


    public function getUsuarioPorEmail($email)
    {
        $query = $this->db->get_where('usuario', array('email' => $email));

        if ($query->num_rows() == 1)
        {
            return $query->row_array();
        }
        else
        {
            return false;
        }
    }

    public function checarToken($token)
    {
        $this->db->select('U.*');
        $this->db->from('troca_de_senha T');
        $this->db->join('usuario U', 'T.usuario_id = U.id'); //combinar informações de duas ou mais tabelas
        $this->db->where('T.token', $token);
        $this->db->where('T.expirado >', date('Y-m-d H:i:s'));
        $query = $this->db->get();
        
        if($query->num_rows()==1)//se o token nao tiver espirado retorna true
        {
            return true;
        } 
        else
        {
            return false;
        }
    }





    /*model-> checar o token, 
    apagar token

$this->db->where(...)
->where('expirado <= ',date('Y-m-d H:i:s'))
->from('troca_de_senha T')
->select('U.*')
->join('usuario U','T.usuario_id = U.id')
->get(); */
    
    
    

}

   


class LoginError extends Exception
{

}
