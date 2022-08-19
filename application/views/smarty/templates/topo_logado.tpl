<!-- Navbar -->
<nav class="navbar navbar-top navbar-expand-md navbar-dark" id="navbar-main">
    <div class="container-fluid">
        <!-- Brand -->
        <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block" href="{$BASE_URL}/assets/temas/argon/index.html">{$titulo_pagina|default:''}</a>
        {*<!-- Form -->
        <form class="navbar-search navbar-search-dark form-inline mr-3 d-none d-md-flex ml-lg-auto">
        <div class="form-group mb-0">
        <div class="input-group input-group-alternative">
        <div class="input-group-prepend">
        <span class="input-group-text"><i class="fas fa-search"></i></span>
        </div>
        <input class="form-control" placeholder="Search" type="text">
        </div>
        </div>
        </form>*}
        <!-- User -->
        <ul class="navbar-nav align-items-center d-none d-md-flex">
            <li class="nav-item dropdown">
                <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="media align-items-center">
                        <span class="avatar avatar-sm rounded-circle">
                            {$usuario_logado.nome|substr:0:1}
                        </span>
                        <div class="media-body ml-2 d-none d-lg-block">
                            <span class="mb-0 text-sm  font-weight-bold">{$usuario_logado.nome}</span>
                        </div>
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
    </div>
</nav>
<!-- End Navbar -->
