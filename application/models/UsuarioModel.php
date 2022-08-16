<?php

class UsuarioModel extends CI_Model
{

    public function getUsuario($user_id)
    {
        $this->db->where('id', $user_id)
                ->get('usuario')
                ->row_array();
    }

    public function atualizarSenhaUsuario($usuarioId, $senha)
    {
        throw new Exception('Não implementado!');
    }

    public function checarLoginValido($usuario, $senha)
    {
        return $this->db->where('username', $usuario)
                        ->where('senha', sha1($senha))
                        ->get('usuario')
                        ->row_array();
    }

}
