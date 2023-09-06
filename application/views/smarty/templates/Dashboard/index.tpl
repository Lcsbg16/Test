{extends file = 'app_logado.tpl'}
{assign var=header_especial value=true}
{assign var=refresh_automatico value=30}
{block name='conteudo_header'}
    <!-- estatisticas_gerais -->
    <div class="container-fluid">
        <div class="header-body">
            <!-- Card stats -->
            <div class="row">
                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">Esta&ccedil;&otilde;es Ativas</h5>
                                    <span class="h2 font-weight-bold mb-0">{$qtde_estacoes}</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-gradient-gray    text-white rounded-circle shadow">
                                        <i class="fas fa-fan"></i>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">Esta&ccedil;&otilde;es Online</h5>
                                    <span class="h2 font-weight-bold mb-0">{$qtde_estacoes_online}</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-gradient-green text-white rounded-circle shadow">
                                        <i class="fas fa-fan"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">Esta&ccedil;&otilde;es Offline</h5>
                                    <span class="h2 font-weight-bold mb-0">{$qtde_estacoes_offline}</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-gradient-red text-white rounded-circle shadow">
                                        <i class="fas fa-fan"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">Temperatura M&eacute;dia</h5>
                                    <span class="h2 font-weight-bold mb-0">
                                        {if $temperatura_media === NULL}
                                            -
                                        {else}
                                            {$temperatura_media|number_format:1:","}&deg;C
                                        {/if}
                                    </span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-yellow text-white rounded-circle shadow">
                                        <i class="fas fa-temperature-half"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">Temperatura M&iacute;nima</h5>

                                    <span class="h2 font-weight-bold mb-0">
                                        {if $temperatura_minima === NULL}
                                            -
                                        {else}
                                            {$temperatura_minima|number_format:1:","}&deg;C
                                        {/if}
                                    </span>

                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-blue text-white rounded-circle shadow">
                                        <i class="fas fa-temperature-empty"></i>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">Temperatura M&aacute;xima</h5>

                                    <span class="h2 font-weight-bold mb-0">
                                        {if $temperatura_maxima === NULL}
                                            -
                                        {else}
                                            {$temperatura_maxima|number_format:1:","}&deg;C
                                        {/if}
                                    </span>

                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-gradient-red text-white rounded-circle shadow">
                                        <i class="fas fa-temperature-full"></i>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">Volume Min. Chuva</h5>
                                    <span class="h2 font-weight-bold mb-0">
                                        {if $vol_chuva_min === NULL}
                                            -
                                        {else}
                                            {$vol_chuva_min|number_format:1:","}mm
                                        {/if}
                                    </span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-gradient-blue text-white rounded-circle shadow">
                                        <i class="fas fa-cloud-rain"></i>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">Volume M&aacute;x. Chuva</h5>
                                    <span class="h2 font-weight-bold mb-0">
                                        {if $vol_chuva_max === NULL}
                                            -
                                        {else}
                                            {$vol_chuva_max|number_format:1:","}mm
                                        {/if}
                                    </span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-gradient-gray text-white rounded-circle shadow">
                                        <i class="fas fa-cloud-rain"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">Vel. Min. do Vento</h5>
                                    <span class="h2 font-weight-bold mb-0">
                                        {if $velocidade_minima == NULL}
                                            -
                                        {else}
                                            {$velocidade_minima|number_format:1:","}km/h
                                        {/if}
                                    </span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-green text-white rounded-circle shadow">
                                        <i class="fas fa-wind"></i>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">Vel. M&aacute;x. do Vento</h5>
                                    <span class="h2 font-weight-bold mb-0">
                                        {if $velocidade_maxima == NULL}
                                            -
                                        {else}
                                            {$velocidade_maxima|number_format:1:","} km/h
                                        {/if}
                                    </span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-red text-white rounded-circle shadow">
                                        <i class="fas fa-wind"></i>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /estatisticas_gerais -->
{/block}
{block name = "conteudo_logado"}

    <div class="container-fluid container-fluid mt--9">

        <!-- Tabelas -->
        <div class="row mt-5">
            <div class="col-xl-5 mb-5 mb-xl-4">
                <div class="card shadow">
                    <div class="card-header border-0">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="mb-0">Eventos</h3>
                            </div>
                            <div class="col text-right">
                                <a href="{$BASE_URL}AdminEventos" class="btn btn-sm btn-primary">Ver todos</a>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <!-- Projects table -->
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">Data</th>
                                    <th scope="col">Estação</th>
                                    <th scope="col">Evento</th>
                                </tr>
                            </thead>
                            <tbody>
                                {foreach $eventos as $evento}
                                    <tr>
                                        <th scope="row">
                                            {$evento.datahora|date_format:'%d/%m %H:%M'}
                                        </th>
                                        <td title="{$evento.estacao_descricao|escape:'quotes'}">
                                            {$evento.estacao_descricao|truncate:25:"...":true}
                                        </td>
                                        <td>
                                            {if $evento.tipo_evento_id == 1}
                                                <i class="fas fa-arrow-down text-danger mr-3"></i>Offline
                                            {elseif $evento.tipo_evento_id == 2}
                                                <i class="fas fa-arrow-up text-success mr-3"></i>Online
                                            {else}
                                                Tipo de evento desconhecido
                                            {/if}
                                        </td>
                                    </tr>
                                {/foreach}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-xl-7">
                <div class="card shadow">
                    <div class="card-header border-0">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="mb-0">Ocorr&ecirc;ncias</h3>
                            </div>
                            <div class="col text-right">
                                <a href="{$BASE_URL}RelatorioOcorrencias" class="btn btn-sm btn-primary">Ver todas</a>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <!-- Projects table -->
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">Tipo</th>
                                    <th scope="col">Data/hora do ocorrido</th>
                                    <th scope="col">Endere&ccedil;o</th>
                                    <th scope="col">Descri&ccedil;&atilde;o</th>
                                </tr>
                            </thead>
                            <tbody>
                                {foreach $ocorrencias as $oAtual}
                                    <tr>
                                        <th scope="row">
                                            <a href="{$oAtual.url}">{$oAtual.tipo_ocorrencia}</a>
                                        </th>
                                        <td>
                                            <a href="{$oAtual.url}">{$oAtual.datahora_ocorrido|date_format:'%d/%m/%Y %H:%M'}</a>
                                        </td>
                                        <td title="{$oAtual.endereco}">
                                            <a href="{$oAtual.url}">{$oAtual.endereco|truncate:30}</a>
                                        </td>
                                        <td>
                                            <a href="{$oAtual.url}">{$oAtual.descricao|truncate:30}</a>
                                        </td>
                                    </tr>
                                {/foreach}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- /tabelas -->
    </div>
{/block}
