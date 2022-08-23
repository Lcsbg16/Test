<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once 'BasePrivateController.php';

class TelaInicial extends BasePrivateController
{

    public function index()
    {
        $variaveisView = [];

        $variaveisView['titulo_pagina'] = 'Home';

        $this->loadSmartyView('TelaInicial/index', $variaveisView);
    }

}
