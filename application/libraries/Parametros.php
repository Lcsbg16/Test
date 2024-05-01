<?php

if (!defined('BASEPATH'))
{
    exit('No direct script access allowed');
}

/**
 * Carrega os parâmetros do sistema da base de dados e os transforma em constantes
 */
class Parametros
{

    private $ci;

    public function __construct()
    {
        $this->ci = & get_instance();
        $this->_carregarParametros();
    }

    private function _carregarParametros()
    {
        $parametros = $this->ci->db->get('parametros')->result_array();
        foreach ($parametros as $p_atual)
        {
            if (!defined($p_atual['nome']))
            {
                define($p_atual['nome'], $p_atual['valor']);
            }
        }
        log_message('debug', 'Parâmetros carregados');
    }

}
