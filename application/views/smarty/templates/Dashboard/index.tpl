{extends file = 'app_logado.tpl'}
{assign var=header_especial value=true}
{block name='conteudo_header'}
    <!-- estatisticas_gerais -->
    <div class="container-fluid">
        <div class="header-body">
            <!-- Card stats -->
            <div class="row" id="alert_cards_row">
                <div id="loading_animation" style="display: none; margin: 12px; padding: 12px;">
                    <i class="fas fa-spinner fa-spin" style="color: white;"></i>
                    <span style="color: white;">Carregando...</span>

                </div>
            </div>
            <div class="row" id="cards_row">

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
                                        <td title="{$evento.estacao_identificador} - {$evento.estacao_descricao|escape:'quotes'}">
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
                        {if empty($ocorrencias)}
                            <div class="col">
                                <h3 class="ml-2">Sem ocorrências registradas no momento.</h3>
                            </div>
                        {else}
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
                        {/if}
                    </div>
                </div>
            </div>
        </div>
        <!-- /tabelas -->
        <script>

            {literal}

                /*  MONTAGEM DOS CARDS DO PAINEL */

                // Lista de dicionários para montagem de cards:
                var listaCards = [
                    {id: 1, url: BASE_URL + '/Dashboard/cardContagemEstacoes', largura: '3'},
                    {id: 2, url: BASE_URL + '/Dashboard/cardEstacoesAtivas', largura: '3'},
                    {id: 3, url: BASE_URL + '/Dashboard/cardEstacoesOffline', largura: '3'},
                    {id: 4, url: BASE_URL + '/Dashboard/cardTemperaturaMedia', largura: '3'},
                    {id: 5, url: BASE_URL + '/Dashboard/cardTemperaturaMinima', largura: '3'},
                    {id: 6, url: BASE_URL + '/Dashboard/cardTemperaturaMaxima', largura: '3'},
                    {id: 7, url: BASE_URL + '/Dashboard/cardVolumeChuvaMinimo', largura: '3'},
                    {id: 8, url: BASE_URL + '/Dashboard/cardVolumeChuvaMaximo', largura: '3'},
                    {id: 9, url: BASE_URL + '/Dashboard/cardVelocidadeMinimaVento', largura: '3'},
                    {id: 10, url: BASE_URL + '/Dashboard/cardVelocidadeMaximaVento', largura: '3'},
                ];

                function criarCardsVazios()
                {
                    listaCards.forEach(card => {
                        var idDoCard = 'card_' + card.id;
                        var htmlDoCardVazio = '<div class="col-xl-' + card.largura + ' col-lg-6" id="' + idDoCard + '"><div class="card card-stats mb-4 mb-xl-4"><div class="card-body"></div></div></div>';
                        $('#cards_row').append(htmlDoCardVazio)
                    });
                }
                criarCardsVazios(); //inicializa o esqueleto vazio dos cards

                function desenharCardsMonitoramento(url, id)
                {
                    $.ajax({
                        url: url,
                        method: 'GET',
                        success: function (response) {
                            $('#' + 'card_' + id).find(".card-body").html(response);
                        },
                        error: function (error) {
                            console.error('Erro na requisição AJAX:', error);
                        }
                    });
                }

                // Lista de dicionários para montagem de cards de alerta:
                var listaCardsAlerta = [
                    {id: 1, url: BASE_URL + '/Dashboard/cardAlertas', largura: '3', corAlerta: "green"}
                ];


                function desenharCardsAlerta(url, largura, AlertaId, cor) {
                    $('#loading_animation').show();


                    $.ajax({
                        url: url,
                        method: 'GET',
                        success: function (response) {
                            $('#loading_animation').hide();
                            $('#alert_cards_row').empty(); // Remove todos os cards de alerta

                            let idCardAlerta = 'alerta_' + AlertaId;

                            let cardAlerta = '<div class="col-xl-' + largura + ' col-lg-6" id="' + idCardAlerta + '"><div class="card card-stats mb-4 mb-xl-4"><div class="card-body">' + response + '</div></div></div>';
                            $('#alert_cards_row').append(cardAlerta);

                            setTimeout(function () { //causa dismetria entre a atualização do card e atualização da cor, levando ao efeito de "piscar"
                                $('#' + idCardAlerta).find('.card').css('background-color', cor);
                            }, 250);


                        },
                        error: function (error) {
                            console.error('Erro na requisição AJAX:', error);
                        }
                    });


                }

                //Att dos cards e do card de alertas
                function atualizarCards()
                {
                    listaCards.forEach(card => {
                        desenharCardsMonitoramento(card.url, card.id);
                    });

                    listaCardsAlerta.forEach(card => {
                        desenharCardsAlerta(card.url, card.largura, card.id, card.corAlerta);
                    });
                }
                atualizarCards();
                setInterval(atualizarCards, 10000);


            {/literal}
        </script>

    </body>
</html>

</div>
{/block}
