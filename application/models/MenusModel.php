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
        $principal->adicionarFilho(new MenuItem('Visualizar Estações', base_url('Info'), 'grip', 'orange'));

        $principal->adicionarFilho(new MenuItem('Gráficos', base_url('grafico'), 'chart-bar-32', 'green'));
        $principal->adicionarFilho(new MenuItem('Reportar Ocorrência', base_url('AdminOcorrencias/index/add'), 'square-pin', 'orange'));

        if ($this->LoginModel->checaPermissaoUsuarioLogado('ADMIN'))
        {
            $cadastros = new MenuItem('Cadastros', '');
            $cadastros->adicionarFilho(new MenuItem('Estações', base_url('AdminEstacoes')));
            $cadastros->adicionarFilho(new MenuItem('-', ''));
            $cadastros->adicionarFilho(new MenuItem('Usuários', base_url('AdminUsuarios')));
            $cadastros->adicionarFilho(new MenuItem('Grupos de Usuários', base_url('AdminGrupos')));

            $relatorios = new MenuItem('Relatórios', '');
            $relatorios->adicionarFilho(new MenuItem('Leituras', base_url('AdminLeituras'), 'collection', 'green'));
            $relatorios->adicionarFilho(new MenuItem('Estatísticas de Leituras', base_url('RelatorioLeituras'), 'collection', 'green'));
            $relatorios->adicionarFilho(new MenuItem('Download dados de leitura', base_url('AdminLeituras/exportarLeitura'), 'collection', 'green'));
            $relatorios->adicionarFilho(new MenuItem('Ocorrências', base_url('RelatorioOcorrencias'), 'collection', 'green'));
            $relatorios->adicionarFilho(new MenuItem('Eventos', base_url('AdminEventos'), 'collection', 'green'));
        }

        $usuario = new MenuItem('Usuário', '');
        $usuario->adicionarFilho(new MenuItem('Meu Perfil', base_url('Usuario/perfil'), 'single-02', 'yellow'));

        $menus = array(
            'lateral' => [
                $principal
            ]
        );

        if ($this->LoginModel->checaPermissaoUsuarioLogado('ADMIN'))
        {
            $menus['lateral'][] = $cadastros;
            $menus['lateral'][] = $relatorios;
        }

        $menus['lateral'][] = $usuario;

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
