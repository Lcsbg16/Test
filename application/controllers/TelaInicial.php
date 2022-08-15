<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once 'BaseController.php';

class TelaInicial extends BaseController
{

    public function index()
    {
        $variaveisView = [];

        $this->loadSmartyView('TelaInicial/index', $variaveisView);
    }

}
