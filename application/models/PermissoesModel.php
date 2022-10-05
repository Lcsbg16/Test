<?php

if (!defined('BASEPATH'))
{
    exit('No direct script access allowed');
}
require_once 'BaseModel.php';

/**
 * Modelo para manipulação de permissões
 */
class PermissoesModel extends BaseModel
{

    public function checaPermissaoGrupos($permissao, $idGrupos)
    {
        if (empty($idGrupos))
        {
            return false;
        }

        $this->db->cache_on();

        $resultado = $this->db->from('grupo_possui_permissao')
                        ->where_in('grupo_usuarios_id', $idGrupos)
                        ->where('(\'' . addslashes($permissao) . '\' LIKE permissao)')
                        ->count_all_results() > 0;

        //echo $this->db->last_query();
        $this->db->cache_off();

        return $resultado;
    }

}
