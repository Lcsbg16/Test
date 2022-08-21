<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once 'BasePrivateController.php';

class Estacoes extends BasePrivateController
{

    public function mapa()
    {
        $this->loadSmartyView('Estacoes/mapa', []);
    }

}
