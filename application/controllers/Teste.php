<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once 'BasePrivateController.php';

class Teste extends BasePrivateController
{

    protected $permissaoAcesso = 'DEV';

    public function testCarregarDadosWeatherCom()
    {
        $this->load->library('weathercom');

        $dados = $this->weathercom->getDadosEstacao('IMACA43', 'e1f10a1e78da46f5b10a1e78da96f525');

        var_dump($dados);
    }
}
