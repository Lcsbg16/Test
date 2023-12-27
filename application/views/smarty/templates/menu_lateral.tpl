<nav class="navbar navbar-vertical fixed-left navbar-expand-md navbar-light bg-white" id="sidenav-main" onmouseover="expandirMenu(this)" onmouseleave="retrairMenu(this)">
    <div class="container-fluid">
        <!-- Toggler -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle navigation" >
            <span class="navbar-toggler-icon"></span>
        </button>
        <!-- Brand -->
        <a class="navbar-brand pt-0" href="{$BASE_URL}" style="margin-left: -7px; padding: 0; min-width: 45px; min-height: 45px;">
            <img src="{$BASE_URL}/assets/temas/argon/assets/img/brand/blue-gout.png" class="navbar-brand-img d-none d-sm-block" id="logoImagem" alt="...">
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
                    <a href="{$BASE_URL}Usuario/perfil" class="dropdown-item">
                        <i class="ni ni-single-02"></i>
                        <span>Meu Perfil</span>
                    </a>

                    {*
                    <a href="{$BASE_URL}/assets/temas/argon/examples/profile.html" class="dropdown-item">
                    <i class="ni ni-support-16"></i>
                    <span>Suporte</span>
                    </a>
                    *}
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
            {foreach $menus.lateral as $mAtual}
                {if $mAtual->getTitulo()}
                    <!-- Divider -->
                    <hr class="my-3">
                    <!-- Heading -->
                    <h6 class="navbar-heading text-muted esconder">{$mAtual->getTitulo()}</h6>
                {/if}

                <ul class="navbar-nav">
                    {foreach from=$mAtual->getFilhos() item=fAtual}
                        {if $fAtual->getTitulo() != '-'}
                            <li class="nav-item  active ">
                                {if $fAtual->getUrl()}
                                    <a class="nav-link  active " href="{$fAtual->getUrl()}">
                                    {/if}
                                    <i class="ni ni-{$fAtual->getIcone()|default:'app'} text-primary {if $fAtual->getCor()}text-{$fAtual->getCor()}{/if}"></i> <span class="esconder">{$fAtual->getTitulo()} </span>
                                    {if $fAtual->getUrl()}
                                    </a>
                                {/if}
                            </li>
                        {else}
                        </ul>
                        <!-- Divider -->
                        <hr class="my-3">
                        <ul class="navbar-nav">
                        {/if}
                    {/foreach}
                </ul>
            {/foreach}
        </div>
    </div>
</nav>
<script type="text/javascript">
function expandirMenu(x) {
    if (window.innerWidth > 768) { 
  x.classList.add("menu-lateral-expandido");
  let logoImagem = document.getElementById('logoImagem');
  logoImagem.src="{$BASE_URL}/assets/temas/argon/assets/img/brand/blue-xs.png"; 
}
}

function retrairMenu(x) {
    x.scrollTop = 0;
  x.classList.remove("menu-lateral-expandido");
  let logoImagem = document.getElementById('logoImagem');
  logoImagem.src="{$BASE_URL}/assets/temas/argon/assets/img/brand/blue-gout.png"; 
}

</script>
