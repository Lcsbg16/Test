{extends file = 'app_logado.tpl'}
{assign var=header_especial value=true}
{assign var=refresh_automatico value=900}
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

                    <div class="table-responsive" id="eventos_table">
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
                    <div class="table-responsive" id="ocorrencias_table">
                        <!-- Projects table -->

                    </div>
                </div>
            </div>
        </div>
        <!-- /tabelas -->
        <script>

            {literal}

                function carregarOcorrencias()
                {
                    url = BASE_URL + 'Dashboard/listaOcorrencias/';
                    $.ajax({
                        url: url,
                        method: 'GET',
                        success: function (response) {

                            $('#ocorrencias_table').html(response);

                        },
                        error: function (error) {
                            console.error('Erro na requisição AJAX ao carregar ocorrencias:', error);
                        }
                    });
                }
                carregarOcorrencias();
                setInterval(carregarOcorrencias, 10000);


                function carregarEventos()
                {
                    url = BASE_URL + 'Dashboard/listaEventos/';
                    $.ajax({
                        url: url,
                        method: 'GET',
                        success: function (response) {

                            $('#eventos_table').html(response);

                        },
                        error: function (error) {
                            console.error('Erro na requisição AJAX ao carregar eventos:', error);
                        }
                    });
                }
                carregarEventos();
                setInterval(carregarEventos, 10000);


                /////////////  MONTAGEM DOS CARDS DO PAINEL */////////////////

                /* MONTAGEM DOS CARDS REGULARES*/
                var listaCards = [
                    {id: 1, url: BASE_URL + '/Dashboard/cardContagemEstacoes', largura: '3'},
                    {id: 2, url: BASE_URL + '/Dashboard/cardEstacoesAtivas', largura: '3'},
                    {id: 3, url: BASE_URL + '/Dashboard/cardEstacoesOffline', largura: '3'},
                    {id: 4, url: BASE_URL + '/Dashboard/cardTemperaturaMedia', largura: '3'},
                    {id: 5, url: BASE_URL + '/Dashboard/cardTemperaturaMinima', largura: '3'},
                    {id: 6, url: BASE_URL + '/Dashboard/cardTemperaturaMaxima', largura: '3'},
                    {id: 7, url: BASE_URL + '/Dashboard/cardVolumeChuvaMaximo', largura: '3'},
                    {id: 8, url: BASE_URL + '/Dashboard/cardVelocidadeMaximaVento', largura: '3'},
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

                function desenharCardMonitoramento(url, id)
                {
                    $.ajax({
                        url: url,
                        method: 'GET',
                        success: function (response) {
                            $('#' + 'card_' + id).find(".card-body").html(response);
                        },
                        error: function (error) {
                            console.error('Erro na requisição AJAX ao desenhar card de monitoramento:', error);
                        }
                    });
                }

                /////////* MONTAGEM DO CARD DE ACUMULO DE CHUVA*//////////


                function carregarCardAcumuladoChuva()
                {
                    url = BASE_URL + 'Dashboard/cardAcumuladoChuvaPorPeriodoGeral/';
                    $.ajax({
                        url: url,
                        method: 'GET',
                        success: function (response) {
                            let responseHTML = $(response);  

                            if (responseHTML.find('.carousel-item').length > 0) {
                                $('#alert_cards_row').addClass('acumuladoChuvaPorPeriodo');
                                $('#alert_cards_row').append(response);
                            } else {
                                $('#alert_cards_row .acumuladoChuvaPorPeriodo').remove();
                            }
                        },
                        error: function (error) {
                            console.error('Erro na requisição AJAX ao carregar card de acumulado de chuva:', error);
                        }
                    });
                }
                carregarCardAcumuladoChuva();



                ///////* MONTAGEM DOS CARDS DE ALERTA*//////////

                function carregarCardsAlerta()
                {
                    var alturaCards = $('#alert_cards_row').height();

                    url = BASE_URL + 'Dashboard/listaCardsAlerta/';
                    $.ajax({
                        url: url,
                        method: 'GET',
                        success: function (response) {
                            if (response.length > 0) {
                                $('#alert_cards_row').css('min-height', alturaCards + 'px');
                                $('#alert_cards_row').empty();
                                response.forEach(card => {
                                    desenharCardAlerta(card.url, card.largura, card.id, card.corAlerta);
                                })
                            } else {
                                $('#alert_cards_row').css('min-height', '0');
                                $('#alert_cards_row').empty()
                            }
                        },
                        error: function (error) {
                           
                            console.error('Erro na requisição AJAX ao carregar card de alerta:', error);
                        }
                    });
                }

                function desenharCardAlerta(url, largura, AlertaId, cor) {
                    $('#loading_animation').show();

                    let idCardAlerta = 'alerta_' + AlertaId;
                    let cardAlerta = '<div class="col-xl-' + largura + ' col-lg-6" id="' + idCardAlerta + '"><div class="card card-stats mb-4 mb-xl-4"><div class="card-body" id="card_alerta_body_' + idCardAlerta + '"></div></div></div>';
                    $('#alert_cards_row').append(cardAlerta);

                    $.ajax({
                        url: url,
                        method: 'GET',
                        success: function (response) {
                            $('#loading_animation').hide();
                            $('#card_alerta_body_' + idCardAlerta).html(response);

                            $('#' + idCardAlerta).find('.card').addClass('blink_card').css('animation', 'blink_' + idCardAlerta + ' 2s linear infinite');
                            $('#classeCardAlerta').append('<style>@keyframes blink_' + idCardAlerta + ' { 0% { background-color:' + cor + '; } 30% { background-color: white; } 100% { background-color: ' + cor + '; } }</style>');
                        },
                        error: function (error) {
                            console.error('Erro na requisição AJAX ao desenhar card de alerta:', error);
                        }
                    });


                }

                //Att dos cards e do card de alertas
                function atualizarCards()
                {
                    listaCards.forEach(card => {
                        desenharCardMonitoramento(card.url, card.id);
                    });

                    carregarCardsAlerta();
                    carregarCardAcumuladoChuva();
                }
                atualizarCards();
                setInterval(atualizarCards, 120000);


            {/literal}
        </script>

    </body>
</html>

</div>
{/block}
