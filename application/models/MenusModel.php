<?php

class MenusModel extends CI_Model
{

    public function getMenus()
    {
        $ci = & get_instance();

        $ci->load->model('LoginModel');

        $menus = array(
            'principal' => [
                new MenuItem('Home', base_url())
            ]
        );

        return $menus;
    }

}

class MenuItem
{

    private $title;
    private $url;
    private $target;
    private $filhos = array();

    public function __construct($title, $url, $target = '_self')
    {
        $this->title  = $title;
        $this->url    = $url;
        $this->target = $target;
    }

    public function adicionarFilho(MenuItem $menu)
    {
        $this->filhos[] = $menu;
    }

    public function getFilhos()
    {
        return $this->filhos;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function getTarget()
    {
        return $this->target;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function setUrl($url)
    {
        $this->url = $url;
    }

    public function setTarget($target)
    {
        $this->target = $target;
    }

}
