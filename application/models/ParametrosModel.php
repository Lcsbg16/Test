<?php

require_once 'BaseModel.php';

class ParametrosModel extends BaseModel
{

    public function getParametros($name)
    {

        $param = $this->db->where('nome', $name)
                ->get('parametros')
                ->row_array();

      

        return $param;
    }

}
