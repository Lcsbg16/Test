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
        if (empty($idsGrupo))
        {
            return false;
        }

        $this->db->cache_on();

        $resultado = $this->db->from('grupo_possui_permissao')
                        ->where_in('grupo_usuarios_id', $idsGrupo)
                        ->where('(\'' . addslashes($permissao) . '\' LIKE permissao)')
                        ->count_all_results() > 0;

        $this->db->cache_off();

        return $resultado;
    }

}
