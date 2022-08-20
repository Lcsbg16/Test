<?php

require_once 'BaseModel.php';

class UsuarioModel extends BaseModel
{

    public function getUsuario($user_id)
    {
        $usuario = $this->db->where('id', $user_id)
                ->get('usuario')
                ->row_array();

        // Grupos
        // TODO: Terminar de implementar
        $usuario['grupos'] = [];

        return $usuario;
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
