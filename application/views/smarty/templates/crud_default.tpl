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
                    {if isset($screen_title)}
                        <div class="card-header border-0">
                            <h3 class="mb-0">{$screen_title}</h3>
                        </div>
                    {/if}
                    <div class="card-body">
                        {$output}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /CRUD -->
{/block}
