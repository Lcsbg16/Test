<?php

require_once 'BaseModel.php';

class MenusModel extends BaseModel
{

    public function getMenus()
    {
        $ci = & get_instance();

        $ci->load->model('LoginModel');

        $principal = new MenuItem('', '');
        $principal->adicionarFilho(new MenuItem('Painel de Controle', base_url('Dashboard'), 'tv-2'));
        $principal->adicionarFilho(new MenuItem('Mapa de Estações', base_url('Estacoes/mapa'), 'map-big', 'yellow'));
        $principal->adicionarFilho(new MenuItem('Mapa de Monitoramento', base_url('Estacoes/mapaMonitoramento'), 'map-big', 'orange'));
        $principal->adicionarFilho(new MenuItem('Gráficos', base_url('grafico'), 'chart-bar-32', 'green'));
        $principal->adicionarFilho(new MenuItem('Reportar Ocorrência', base_url('AdminOcorrencias/index/add'), 'square-pin', 'orange'));

        $cadastros = new MenuItem('Cadastros', '');
        $cadastros->adicionarFilho(new MenuItem('Estações', base_url('AdminEstacoes')));
        $cadastros->adicionarFilho(new MenuItem('-', ''));

        $menus = array(
            'lateral' => [
                $principal,
                $cadastros
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
    private $permissao;
    private $icone;
    private $cor;

    public function __construct($title, $url, $icone = '', $cor = '', $permissao = 'GERAL', $target = '_self')
    {
        $this->title     = $title;
        $this->url       = $url;
        $this->target    = $target;
        $this->icone     = $icone;
        $this->permissao = $permissao;
        $this->cor       = $cor;
    }

    public function adicionarFilho(MenuItem $menu)
    {
        $this->filhos[] = $menu;
    }

    public function getFilhos()
    {
        return $this->filhos;
    }

    public function getTitulo()
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

    public function setTitulo($title)
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

    public function getPermissao()
    {
        return $this->permissao;
    }

    public function setPermissao($permissao): void
    {
        $this->permissao = $permissao;
    }

    public function getCor()
    {
        return $this->cor;
    }

    public function setCor($cor): void
    {
        $this->cor = $cor;
    }

    public function getIcone()
    {
        return $this->icone;
    }

    public function setIcone($icone): void
    {
        $this->icone = $icone;
    }

}
