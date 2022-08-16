{assign var=body_class value='bg-default'}
{extends file="app.tpl"}

{block name="conteudo"}

    <div class="main-content">
        <!-- Navbar -->
        <nav class="navbar navbar-top navbar-horizontal navbar-expand-md navbar-dark">
            <div class="container px-4">
                <a class="navbar-brand" href="{$BASE_URL}assets/temas/argon/index.html">
                    <img src="{$BASE_URL}assets/temas/argon/assets/img/brand/white.png" />
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-collapse-main" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbar-collapse-main">
                    <!-- Collapse header -->
                    <div class="navbar-collapse-header d-md-none">
                        <div class="row">
                            <div class="col-6 collapse-brand">
                                <a href="{$BASE_URL}assets/temas/argon/index.html">
                                    <img src="{$BASE_URL}assets/temas/argon/assets/img/brand/blue.png">
                                </a>
                            </div>
                            <div class="col-6 collapse-close">
                                <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbar-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle sidenav">
                                    <span></span>
                                    <span></span>
                                </button>
                            </div>
                        </div>
                    </div>
                    {*
                    <!-- Navbar items -->
                    <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                    <a class="nav-link nav-link-icon" href="{$BASE_URL}assets/temas/argon/index.html">
                    <i class="ni ni-planet"></i>
                    <span class="nav-link-inner--text">Dashboard</span>
                    </a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link nav-link-icon" href="{$BASE_URL}assets/temas/argon/examples/register.html">
                    <i class="ni ni-circle-08"></i>
                    <span class="nav-link-inner--text">Register</span>
                    </a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link nav-link-icon" href="{$BASE_URL}assets/temas/argon/examples/login.html">
                    <i class="ni ni-key-25"></i>
                    <span class="nav-link-inner--text">Login</span>
                    </a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link nav-link-icon" href="{$BASE_URL}assets/temas/argon/examples/profile.html">
                    <i class="ni ni-single-02"></i>
                    <span class="nav-link-inner--text">Profile</span>
                    </a>
                    </li>
                    </ul>
                    *}
                </div>
            </div>
        </nav>
        <!-- Header -->
        <div class="header bg-gradient-primary py-7 py-lg-8">
            <div class="container">
                <div class="header-body text-center mb-2">
                    <div class="row justify-content-center">
                        <div class="col-lg-5 col-md-6">
                            <h1 class="text-white">Seja Bem-vindo!!</h1>
                            <p>
                                <img src="{$BASE_URL}assets/images/logo-unidade.png">
                            </p>
                            {*<p class="text-lead text-light">Use these awesome forms to login or create new account in your project for free.</p>*}
                        </div>
                    </div>
                </div>

            </div>
            <div class="separator separator-bottom separator-skew zindex-100">
                <svg x="0" y="0" viewBox="0 0 2560 100" preserveAspectRatio="none" version="1.1" xmlns="http://www.w3.org/2000/svg">
                <polygon class="fill-default" points="2560 0 2560 100 0 100"></polygon>
                </svg>
            </div>
        </div>
        <!-- Page content -->
        <div class="container mt--8 pb-5">
            <div class="row justify-content-center">
                <div class="col-lg-5 col-md-7">
                    <div class="card bg-secondary shadow border-0">

                        <div class="card-body px-lg-5 py-lg-5">
                            <div class="text-center text-muted mb-4">
                                <small>Entre com seu usuário e senha</small>
                            </div>
                            <form role="form"  method="POST" action="{$BASE_URL}Login">
                                {if $url_retorno}
                                    <input type="hidden" name="url_retorno" value="{$url_retorno}">
                                {/if}
                                <div class="form-group mb-3">
                                    <div class="input-group input-group-alternative">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                                        </div>
                                        <input class="form-control" placeholder="username" type="text" value="{set_value('username')}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-group input-group-alternative">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                                        </div>
                                        <input class="form-control" placeholder="password" type="password">
                                    </div>
                                </div>

                                <div class="text-center">
                                    <button type="button" class="btn btn-primary my-4">Entrar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-6">
                            <a href="#" class="text-light"><small>Esqueci minha senha</small></a>
                        </div>
                        <div class="col-6 text-right">
                            {*<a href="#" class="text-light"><small>Create new account</small></a>*}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <footer class="py-5">
            <div class="container">
                {*
                <div class="row align-items-center justify-content-xl-between">
                <div class="col-xl-6">
                <div class="copyright text-center text-xl-left text-muted">
                © 2018 <a href="https://www.creative-tim.com" class="font-weight-bold ml-1" target="_blank">Creative Tim</a>
                </div>
                </div>
                <div class="col-xl-6">
                <ul class="nav nav-footer justify-content-center justify-content-xl-end">
                <li class="nav-item">
                <a href="https://www.creative-tim.com" class="nav-link" target="_blank">Creative Tim</a>
                </li>
                <li class="nav-item">
                <a href="https://www.creative-tim.com/presentation" class="nav-link" target="_blank">About Us</a>
                </li>
                <li class="nav-item">
                <a href="http://blog.creative-tim.com" class="nav-link" target="_blank">Blog</a>
                </li>
                <li class="nav-item">
                <a href="https://github.com/creativetimofficial/argon-dashboard/blob/master/LICENSE.md" class="nav-link" target="_blank">MIT License</a>
                </li>
                </ul>
                </div>
                </div>*}
            </div>
        </footer>
    </div>

{/block}
