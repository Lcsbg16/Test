{extends file='app_logado.tpl'}
{block name="conteudo_logado"}

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-multiselect@0.9.16/dist/css/bootstrap-multiselect.min.css">
    <link rel="stylesheet" href="{$BASE_URL}/assets/chosen/bootstrap-multiselect.css"/>
    
    <div class="container-fluid mt-3">
        <form method="GET" action="{$BASE_URL}AdminLeituras/exportarLeitura">
            <div class="row">
                <div class="col">
                    <div class="card shadow">
                        <div class="card-body">
                            <div class="row my-3">
                                <div class="col">
                                    <label>Estação</label><br>
                                    <select id="estacoesSelect" class="form-control form-control-sm" name="estacoes[]" multiple="multiple">
                                        {foreach $estacoes as $eAtual}
                                            {assign var=corTexto value=($eAtual.ativa == 1) ? 'black' : 'grey'}
                                            <option value="{$eAtual.id}" id="estacao_descricao" {if isset($smarty.get.estacoes) && in_array($eAtual.id, $smarty.get.estacoes)} selected {/if} style="color: {$corTexto}">
                                                {$eAtual.descricao} ({$eAtual.identificador})
                                            </option>
                                        {/foreach}
                                    </select>
                                </div>
                                <div class="col">
                                    <label>Data Inicial</label>
                                    <input class="form-control form-control-sm" type="date" name="data_inicial" value="">
                                </div>
                                <div class="col">
                                    <label>Data Final</label>
                                    <input class="form-control form-control-sm" type="date" name="data_final" value="">
                                </div>
                                <div class="col">
                                    <label>Escala</label>
                                    <select class="form-control form-control-sm" name="escala">
                                        <option value="hora">Hora</option>
                                        <option value="dia">Dia</option>
                                        <option value="mes">Mês</option>
                                        <option value="ano">Ano</option>
                                    </select>
                                </div>
                                <div class="col mt-4">
                                    <button type="submit" class="btn btn-primary">Exportar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.16/js/bootstrap-multiselect.min.js"></script>
    
    <script>
        $(document).ready(function () {
            $("#estacoesSelect").multiselect({
                includeSelectAllOption: true,
                buttonWidth: '180px',
                enableHTML: true
            });
        });
    </script>
{/block}