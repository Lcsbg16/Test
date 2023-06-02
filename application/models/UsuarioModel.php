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
        $dados = ['senha'=>sha1($senha)];  //criptografia
        return $this->db->update('usuario', $dados, ['id' => $usuarioId]); // id deve ser igual a usuarioId
    }


    public function esqueciSenha(){
        
        return 0;
    }
    public function checarLoginValido($usuario, $senha)
    {
        return $this->db->where('username', $usuario)
                        ->where('senha', sha1($senha))
                        ->get('usuario')
                        ->row_array();
    }

    public function updateSenha($usuarioId, $novaSenha)
    {
        $dados = array('senha' => sha1($novaSenha));
        $this->db->where('id',$usuarioId);
        $this->db->update('usuario',$dados);
    }

    // public function getTrocaSenhaPorToken($email, $token)
    // {
    //     $this->db->select('*'); //selecionar todas as colunas da tabela
    //     $this->db->where('email',$email);
    //     $this->db->where('token',$token);
    //     $this->db->where('expirado >=',date('Y-m-d H:i:s'));
    //     $query=$this->db->get('troca_de_senha');

    //     if($query->num_rows()>0)
    //     {
    //         return $query->row_array();
    //     } 
    //     else
    //     {
    //         return false;
    //     }
    // }
}
