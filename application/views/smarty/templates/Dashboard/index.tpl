{extends file = 'app_logado.tpl'}
{block name = "conteudo_logado"}
    <div class="container-fluid mt-3">

        <!-- Tabelas -->
        <div class="row mt-5">
            <div class="col-xl-5 mb-5 mb-xl-0">
                <div class="card shadow">
                    <div class="card-header border-0">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="mb-0">Eventos</h3>
                            </div>
                            <div class="col text-right">
                                <a href="#!" class="btn btn-sm btn-primary">Ver todos</a>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <!-- Projects table -->
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">Data</th>
                                    <th scope="col">Esta&ccedil;&atilde;o</th>
                                    <th scope="col">Evento</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th scope="row">
                                        22/08/2022 05:00
                                    </th>
                                    <td>
                                        est001
                                    </td>
                                    <td>
                                        <i class="fas fa-arrow-up text-success mr-3"></i>  Novamente online!
                                    </td>

                                </tr>
                                <tr>
                                    <th scope="row">
                                        22/08/2022 02:00
                                    </th>
                                    <td>
                                        est003
                                    </td>
                                    <td>
                                        <i class="fas fa-arrow-down text-danger mr-3"></i> Atraso no recebimento!
                                    </td>

                                </tr>
                                <tr>
                                    <th scope="row">
                                        21/08/2022 23:00
                                    </th>
                                    <td>
                                        est001
                                    </td>
                                    <td>
                                        <i class="fas fa-arrow-down text-danger mr-3"></i> Atraso no recebimento!
                                    </td>

                                </tr>

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