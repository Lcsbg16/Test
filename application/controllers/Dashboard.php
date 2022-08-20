<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once 'BasePrivateController.php';

class Dashboard extends BasePrivateController
{

    public function index()
    {
        $variaveisView = [];

        $this->loadSmartyView('Dashboard/index', $variaveisView);
    }

}
