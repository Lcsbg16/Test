{assign var=body_class value='bg-default'}
{extends file="app.tpl"}

{block name="conteudo"}

    <div class="main-content">
        <!-- Navbar -->
        <nav class="navbar navbar-top navbar-horizontal navbar-expand-md navbar-dark">
            <div class="container px-4">
                <a class="navbar-brand" href="{$BASE_URL}assets/temas/argon/index.html">
                    <img src="{$BASE_URL}/assets/temas/argon/assets/img/brand/white.png" class="d-none d-sm-block" >
                    <img src="{$BASE_URL}/assets/temas/argon/assets/img/brand/white-xs.png" class="d-block d-sm-none" >
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
                                    <img src="{$BASE_URL}/assets/temas/argon/assets/img/brand/blue.png" class="d-none d-sm-block" >
                                    <img src="{$BASE_URL}/assets/temas/argon/assets/img/brand/blue-xs.png" class="d-block d-sm-none" >
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

                </div>
            </div>
        </nav>
        <!-- Header -->
        <div class="header bg-gradient-primary py-7 py-lg-8">
            <div class="container">
                <div class="header-body text-center mb-3">
                    <div class="row justify-content-center">
                        <div class="col-lg-5 col-md-6">
                            <h1 class="text-white">Seja Bem-vindo!!</h1>
                            <div class="d-flex justify-content-center"> <!-- Use a classe d-flex para criar um contêiner flexível -->
                                <div class="mx-2">
                                    <img src="{$BASE_URL}assets/images/logo-unidade.png" class="img-fluid" style="max-width: 210px;">
                                </div>
                                <div class="mx-2">
                                    <img src="{$BASE_URL}assets/images/ufrj-horizontal-negativa-telas.png" class="img-fluid" style="max-width: 210px;">
                                </div>
                                <div class="mx-2" style="margin-top: 20px;">
                                    <img src="{$BASE_URL}assets/images/logo-prefeitura.png" class="img-fluid" style="max-width: 210px;">
                                </div>
                            </div>
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
                                        <input class="form-control" placeholder="Usu&aacute;rio" name="username" type="text" value="{set_value('username')}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-group input-group-alternative">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                                        </div>
                                        <input class="form-control" placeholder="Senha" name="password" type="password">
                                    </div>
                                </div>

                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary my-4">Entrar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-6">
                            <a href="{$BASE_URL}/Login/esqueciSenha" class="text-light"><small>Esqueci minha senha</small></a>
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

            </div>
        </footer>
    </div>

{/block}
