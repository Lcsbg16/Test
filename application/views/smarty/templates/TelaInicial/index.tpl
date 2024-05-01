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
                            <div class="col-sm" style="margin-top: 20px;">
                                <img class="img-fluid" src="{$BASE_URL}assets/images/logo-prefeitura.png" style="max-width: 250px;">
                            </div>
                            <div class="col-sm">
                                <img class="img-fluid" src="{$BASE_URL}assets/images/logo-unidade.png" style="max-width: 210px;">
                            </div>
                            <div class="col-sm">
                                <img class="img-fluid" src="{$BASE_URL}assets/images/logo-ufrj.png" style="max-width: 250px;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

{/block}