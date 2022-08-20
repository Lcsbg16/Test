<nav class="navbar navbar-vertical fixed-left navbar-expand-md navbar-light bg-white" id="sidenav-main">
    <div class="container-fluid">
        <!-- Toggler -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <!-- Brand -->
        <a class="navbar-brand pt-0" href="{$BASE_URL}">
            <img src="{$BASE_URL}/assets/temas/argon/assets/img/brand/blue.png" class="navbar-brand-img d-none d-sm-block" alt="...">
            <img src="{$BASE_URL}/assets/temas/argon/assets/img/brand/blue-xs.png" class="navbar-brand-img d-block d-sm-none" alt="...">
        </a>
        <!-- User -->
        <ul class="nav align-items-center d-md-none">
            <li class="nav-item dropdown">
                <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="media align-items-center">
                        <span class="avatar avatar-sm rounded-circle">
                            {$usuario_logado.nome|substr:0:1}
                        </span>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
                    <div class=" dropdown-header noti-title">
                        <h6 class="text-overflow m-0">Bem-vindo!</h6>
                    </div>
                    <a href="javascript:alert('Funcionalidade em desenvolvimento.')" class="dropdown-item">
                        <i class="ni ni-single-02"></i>
                        <span>Meu Perfil</span>
                    </a>

                    <a href="{$BASE_URL}/assets/temas/argon/examples/profile.html" class="dropdown-item">
                        <i class="ni ni-support-16"></i>
                        <span>Suporte</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="{$BASE_URL}TelaInicial/logout/" class="dropdown-item">
                        <i class="ni ni-user-run"></i>
                        <span>Sair</span>
                    </a>
                </div>
            </li>
        </ul>
        <!-- Collapse -->
        <div class="collapse navbar-collapse" id="sidenav-collapse-main">
            <!-- Collapse header -->
            <div class="navbar-collapse-header d-md-none">
                <div class="row">
                    <div class="col-6 collapse-brand">
                        <a href="{$BASE_URL}">
                            <img src="{$BASE_URL}/assets/temas/argon/assets/img/brand/blue.png" class="d-none d-sm-block" alt="...">
                            <img src="{$BASE_URL}/assets/temas/argon/assets/img/brand/blue-xs.png" class="d-block d-sm-none" alt="...">
                        </a>
                    </div>
                    <div class="col-6 collapse-close">
                        <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle sidenav">
                            <span></span>
                            <span></span>
                        </button>
                    </div>
                </div>
            </div>{*
            <!-- Form -->
            <form class="mt-4 mb-3 d-md-none">
            <div class="input-group input-group-rounded input-group-merge">
            <input type="search" class="form-control form-control-rounded form-control-prepended" placeholder="Search" aria-label="Search">
            <div class="input-group-prepend">
            <div class="input-group-text">
            <span class="fa fa-search"></span>
            </div>
            </div>
            </div>
            </form>*}
            <!-- Navigation -->
            <ul class="navbar-nav">
                <li class="nav-item  active ">
                    <a class="nav-link  active " href="{$BASE_URL}Dashboard">
                        <i class="ni ni-tv-2 text-primary"></i> Painel de Controle
                    </a>
                </li>
                <li class="nav-item  active ">
                    <a class="nav-link  active " href="{$BASE_URL}#">
                        <i class="ni ni-map-big text-primary text-yellow"></i> Mapa de Esta&ccedil;&otilde;es
                    </a>
                </li>
                <li class="nav-item  active ">
                    <a class="nav-link  active " href="{$BASE_URL}grafico">
                        <i class="ni ni-chart-bar-32 text-primary text-orange"></i> Gr&aacute;ficos
                    </a>
                </li>
            </ul>
            <!-- Divider -->
            <hr class="my-3">
            <!-- Heading -->
            <h6 class="navbar-heading text-muted">Cadastros</h6>
            <ul class="navbar-nav ">
                {* <li class="nav-item  active ">
                <a class="nav-link  active " href="{$BASE_URL}AdminLocais/">
                <i class="ni ni-app text-primary"></i> Locais
                </a>
                </li> *}
                <li class="nav-item  active ">
                    <a class="nav-link  active " href="{$BASE_URL}AdminEstacoes/">
                        <i class="ni ni-app text-primary"></i> Esta&ccedil;&otilde;es
                    </a>
                </li>
            </ul>
            <!-- Divider -->
            <hr class="my-3">
            <!-- Heading -->

            <ul class="navbar-nav mb-md-3">
                {* <li class="nav-item  active ">
                <a class="nav-link  active " href="{$BASE_URL}AdminBairros/">
                <i class="ni ni-app text-primary"></i> Bairros
                </a>
                </li>*}
                <li class="nav-item  active ">
                    <a class="nav-link  active " href="{$BASE_URL}AdminUsuarios/">
                        <i class="ni ni-app text-primary"></i> Usu&aacute;rios
                    </a>
                </li>
                <li class="nav-item  active ">
                    <a class="nav-link  active " href="{$BASE_URL}AdminGrupos/">
                        <i class="ni ni-app text-primary"></i> Grupos de Usu&aacute;rios
                    </a>
                </li>
            </ul>
            <!-- Divider -->
            <hr class="my-3">
            <!-- Heading -->
            <h6 class="navbar-heading text-muted">Usu&aacute;rio</h6>
            <ul class="navbar-nav mb-md-3">
                <li class="nav-item">
                    <a class="nav-link " href="javascript:alert('Funcionalidade em desenvolvimento.')">
                        <i class="ni ni-single-02 text-yellow"></i> Meu Perfil
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
