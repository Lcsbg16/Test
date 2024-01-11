{extends file = 'app_logado.tpl'}
{block name = "conteudo_logado"}

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-multiselect@0.9.16/dist/css/bootstrap-multiselect.min.css">
    <link rel="stylesheet" href="{$BASE_URL}/assets/chosen/bootstrap-multiselect.css"/>
    <script type="text/javascript" src="{$BASE_URL}/assets/chosen/bootstrap-multiselect.js"></script>
    <div class="container-fluid mt-3">
        <form method="GET" action="{$BASE_URL}RelatorioLeituras/">
            <div class="row">
                <div class="col">
                    <div class="card shadow">
                        <div class="card-body">
                            <div class="row my-3">
                                <div class="col">
                                    <label>Estação</label><br>
                                    <select id="estacoesSelect" class="form-control form-control-sm" name="estacoes[]" multiple="multiple">
                                        {foreach $estacoes as $eAtual}
                                            {assign var=corTexto value=($eAtual.ativa == 1) ? 'black' : 'red'}
                                            <option value="{$eAtual.id}" id="estacao_descricao" {if isset($smarty.get.estacoes) && in_array($eAtual.id, $smarty.get.estacoes)} selected {/if} style="color: {$corTexto}">
                                                {$eAtual.descricao} ({$eAtual.identificador})
                                            </option>
                                        {/foreach}
                                    </select>
                                </div>
                                <div class="col">
                                    <label>Data Inicial</label>
                                    <input class="form-control form-control-sm" type="date" name="dataInicial" value="{if isset($smarty.get.dataInicial)}{$smarty.get.dataInicial}{/if}">
                                </div>
                                <div class="col">
                                    <label>Data Final</label>
                                    <input class="form-control form-control-sm" type="date" name="dataFinal"  value="{if isset($smarty.get.dataFinal)}{$smarty.get.dataFinal}{/if}">
                                </div>
                                <div class="col">
                                    <label>Escala</label>

                                    <select class="form-control form-control-sm" name="escala">
                                        <option value="ESCALA_MINUTO" {if isset($smarty.get.escala) && $smarty.get.escala == "ESCALA_MINUTO"} selected{/if}>Minuto</option>
                                        <option value="ESCALA_HORA" {if isset($smarty.get.escala) && $smarty.get.escala == "ESCALA_HORA"} selected{/if}>Hora</option>
                                        <option value="ESCALA_DIA" {if isset($smarty.get.escala) && $smarty.get.escala == "ESCALA_DIA"} selected{/if}>Dia</option>
                                        <option value="ESCALA_SEMANA" {if isset($smarty.get.escala) && $smarty.get.escala == "ESCALA_SEMANA"} selected{/if}>Semana</option>
                                        <option value="ESCALA_MES" {if isset($smarty.get.escala) && $smarty.get.escala == "ESCALA_MES"} selected{/if}>M&ecirc;s</option>
                                        <option value="ESCALA_ANO" {if isset($smarty.get.escala) && $smarty.get.escala == "ESCALA_ANO"} selected{/if}>Ano</option>
                                    </select>
                                </div>
                                <div class="col">
                                    <label>Tipo de Informa&ccedil;&atilde;o</label>
                                    <select class="form-control form-control-sm" name="tipoInformacao">

                                        <option value="TIPO_TEMPERATURA" {if isset($smarty.get.tipoInformacao) && $smarty.get.tipoInformacao == "TIPO_TEMPERATURA"} selected{/if}>Temperatura (&#176;C)</option>
                                        <option value="TIPO_VOLUME_CHUVA" {if isset($smarty.get.tipoInformacao) && $smarty.get.tipoInformacao == "TIPO_VOLUME_CHUVA"} selected{/if}>Volume de Chuva (mm&sup3;)</option>
                                        <option value="TIPO_UMIDADE_AR" {if isset($smarty.get.tipoInformacao) && $smarty.get.tipoInformacao == "TIPO_UMIDADE_AR"} selected{/if}>Umidade do Ar (%)</option>
                                        <option value="TIPO_VELOCIDADE_VENTO" {if isset($smarty.get.tipoInformacao) && $smarty.get.tipoInformacao == "TIPO_VELOCIDADE_VENTO"} selected{/if}> Velocidade do Vento (km/h)</option>
                                    </select>
                                    <br>
                                    <button type="button" class="btn btn-primary" onclick="window.location = '{$BASE_URL}/RelatorioLeituras/'">Limpar</button>
                                    <button type="submit" class="btn btn-primary" >Buscar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    </form>
                    <div class="row my-12">
                        <div class="col-md-12 py-1">
                            <div class="card">
                                <div class="card-body" style="height: 500px">
                                    <table id="tabela" class="display" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th scope="col">Periodo</th>
                                                <th scope="col">Estatistica Média</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {foreach $leituras as $periodo => $valor}
                                                <tr>
                                                    <td data-order="{if $smarty.get.escala == "ESCALA_MINUTO"}{substr($periodo, 0, 16)}{elseif $smarty.get.escala == "ESCALA_HORA"}{substr($periodo, 0, 13)}{elseif $smarty.get.escala == "ESCALA_DIA"}{substr($periodo, 0, 10)}{elseif $smarty.get.escala == "ESCALA_SEMANA"}{substr($periodo, 0, 10)}{elseif $smarty.get.escala == "ESCALA_MES"}{substr($periodo, 0, 7)}{elseif $smarty.get.escala == "ESCALA_ANO"}{substr($periodo, 0, 4)}{/if}">
                                                        {if $smarty.get.escala == "ESCALA_MINUTO"}
                                                            {$periodo|date_format:"%d/%m/%Y %H:%M"}
                                                        {elseif $smarty.get.escala == "ESCALA_HORA"}
                                                            {$periodo|date_format:"%d/%m/%Y %H:00"}
                                                        {elseif $smarty.get.escala == "ESCALA_DIA"}
                                                            {$periodo|date_format:"%d/%m/%Y"}
                                                        {elseif $smarty.get.escala == "ESCALA_SEMANA"}
                                                            {$periodo|date_format:"%d/%m/%Y"}
                                                        {elseif $smarty.get.escala == "ESCALA_MES"}
                                                            {$periodo|date_format:"%m/%Y"}
                                                        {elseif $smarty.get.escala == "ESCALA_ANO"}
                                                            {$periodo}
                                                        {/if}
                                                    </td>
                                                    <td>
                                                        {$valor|number_format:2:",":"."}
                                                    </td>
                                                </tr>
                                            {/foreach}

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>

    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.16/js/bootstrap-multiselect.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $("#estacoesSelect").multiselect({
                includeSelectAllOption: true,
                buttonWidth: '180px',
                enableHTML: true
            });
        });

        $(document).ready(function () {
            $('#tabela').DataTable({
                "order": [[0, 'asc']]
            });
        });
    </script>
{/block}


