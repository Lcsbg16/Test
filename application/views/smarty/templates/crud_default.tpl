{extends file="app_logado.tpl"}
{block name="conteudo_logado"}
    <!-- CRUD -->
    <style>
        #report-error, #report-success, #report-error p{
            color: black;
            font-weight: bold ;
        }
    </style>
    <div class="container-fluid mt-3">
        <div class="row">
            <div class="col">
                <div class="card shadow">
                    <div class="card-body">
                        {$output}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /CRUD -->
{/block}
