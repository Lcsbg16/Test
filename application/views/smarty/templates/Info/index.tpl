{extends file='app_logado.tpl'}
{block name="conteudo_logado"}

    <style>
        .custom-card {
            border: 1px solid rgba(0, 0, 0, 0.125);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .custom-card img {
            height: 200px; 
            object-fit: cover;
        }
    </style>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <div class="container">
        <div class="row">
            {foreach $estacoes as $eAtual}
                {if $eAtual->ativa}
                    <div class="col-md-3">
                        <div class="card bg-light-purple mt-4 custom-card">
                            <img src="{$BASE_URL}assets/images/estacao.jpg" class="card-img-top img-fluid" alt="...">
                            <div class="card-body text-center">
                                <h2 class="card-title" style="color: #6c757d;">{$eAtual->identificador}</h5>
                            
                                <p class="card-text">Leitura dos dados: Há 2 minutos</p>
                                <p class="card-text" style="font-size: 3rem;">{$eAtual->temperatura|number_format:1:","}&deg;C</p>

                                <div class="card-footer text-muted">
                                    <p style="margin-bottom: 0;">Vel do vento: {$eAtual->velocidade_vento|number_format:1:","} m/s</p>
                                    <p style="margin-bottom: 0;">Vol acumulado Chuva: {$eAtual->volume_chuva|number_format:1:","} mm</p>
                                </div>
                            </div>
                        </div>
                    </div>
                {/if}
            {/foreach}
        </div>
    </div>
{/block}
