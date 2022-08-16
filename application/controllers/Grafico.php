<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once 'BaseController.php';

class Grafico extends BaseController
{

    public function index()
    {
        $variaveisView = [];
        
        $this->loadSmartyView('grafico/visualizar', $variaveisView);
    }

}
