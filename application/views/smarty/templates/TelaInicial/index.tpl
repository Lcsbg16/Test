{extends file = 'app_logado.tpl'}
{block name = "conteudo_logado"}
    <div class="container-fluid mt-3">
        <div class="row">
            <div class="col">
                <div class="card shadow">
                    <div class="card-header border-0">
                        <h3>Seja bem-vindo!</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm">
                                <img class="img-fluid" src="{$BASE_URL}assets/images/logo-prefeitura.png">
                            </div>
                            <div class="col-sm">
                                <img class="img-fluid" src="{$BASE_URL}assets/images/logo-unidade.png">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

{/block}