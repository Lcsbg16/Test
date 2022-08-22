<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once 'BasePrivateController.php';

class Grafico extends BasePrivateController
{

    public function index()
    {
        $variaveisView = [];

        $this->loadSmartyView('Grafico/index', $variaveisView);
    }

}
