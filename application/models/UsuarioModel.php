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
        $usuario['grupos'] = $this->getGruposUsuario($user_id);

        return $usuario;
    }

    public function getGruposUsuario($usuarioId)
    {
        $linhas = $this->db->where('usuario_id', $usuarioId)
                ->get('usuario_possui_grupo')
                ->result_array();

        $idGrupos = [];

        foreach ($linhas as $lAtual)
        {
            $idGrupos[] = $lAtual['grupo_usuarios_id'];
        }

        return $idGrupos;
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
