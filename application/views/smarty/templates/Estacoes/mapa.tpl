{extends file = 'app_logado.tpl'}
{block name = "conteudo_logado"}
    {* leaflet *}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.8.0/dist/leaflet.css"
          integrity="sha512-hoalWLoI8r4UszCkZ5kL8vayOGVae1oxXe/2A4AO6J9+580uKHDO3JdHb7NzwwzK5xr/Fs0W40kiNHxM9vyTtQ=="
          crossorigin=""/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.label/0.2.4/leaflet.label.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.label/0.2.4/leaflet.label.css" />


    <script src="https://unpkg.com/leaflet@1.8.0/dist/leaflet.js"
            integrity="sha512-BB3hKbKWOc9Ez/TAwyWxNXeoV9c1v6FIeYiBieIWkpLjauysF18NzgR1MBNBXf8/KABdlkX68nAhlwcDFLGPCQ=="
    crossorigin=""></script>

    <link rel="stylesheet" href="{$BASE_URL}/assets/chosen/bootstrap-multiselect.css"/>
    <script type="text/javascript" src="{$BASE_URL}/assets/chosen/bootstrap-multiselect.js"></script>

    <style>
        #map{
            width: 100%;
            height: 99%;
        }
        #estacao_selecionada + .btn-group .multiselect { /*ALTERAÇÃO DO CSS DO MULTISELECT BUTTON - SELECIONAR MULTIPLAS ESTAÇÕES*/
            /* Deixando modelo do selecionar camada */
            font-size: 0.875rem;
            text-align: left !important;
            height: calc(1.8125rem + 2px);
        }
    </style>
    {* /leaflet *}

    <div class="container-fluid container-fluid-mapa mt-3">
        <div class="row">
            <div class="col">
                <div class="card shadow">
                    <div class="card-body card-body-top">
                        <label>Esta&ccedil;&atilde;o</label>
                        <select class="form-control form-control-sm change_controller" id="estacao_selecionada" multiple> <!-- Id indica qual estação foi selecionada -->
                            {foreach $estacoes as $eAtual}
                                <option value="{$eAtual.id}" selected id="estacao_descricao"> {$eAtual.descricao} ({$eAtual.identificador})</option>
                            {/foreach}

                        </select>
                    </div>

                    <div class="card-body card-body-bottom">
                        {if $estacoes}
                            <div id="map"></div>
                        {else}
                            <div class="alert alert-warning">
                                N&atilde;o h&aacute; esta&ccedil;&otilde;es cadastradas no sistema para o seu n&iacute;vel de acesso.
                            </div>
                        {/if}
                    </div>

                </div>
            </div>
        </div>
    </div>
    {if $estacoes}

        <script>
            {literal}
                var offlineIcon = L.icon({
                    iconUrl: BASE_URL + 'assets/images/grey_marker.png',
                    shadowUrl: BASE_URL + 'assets/images/marker-shadow.png',
                    iconSize: [25, 41],
                    iconAnchor: [12, 41],
                    popupAnchor: [1, -34],
                    tooltipAnchor: [16, -28],
                    shadowSize: [41, 41]
                });

                function criaMapa() {
                    var map = L.map('map').setView([-22.368461, -41.774747], 13);

                    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                        attribution: '© OpenStreetMap'
                    }).addTo(map);

                    return map;

                }

                function onEachFeature(feature, layer) { //organiza o pop-up
                    var popupContent = '';
                    if (feature.properties) {
                        var url = BASE_URL + 'AdminEstacoes/index/edit/' + feature.properties.estacao.id; //URL PARA O 'EDITAR ESSA ESTAÇÃO'
                        var verUrl = BASE_URL + 'Estacoes/monitoramentoIndividual/' + feature.properties.estacao.id;
                        popupContent += "\
                    <h3><strong>" + feature.properties.estacao.descricao + ' (' + feature.properties.estacao.identificador + ')' + "</strong></h3>\
                    <br><strong>Endere&ccedil;o:</strong><br>" + feature.properties.estacao.endereco + "\
                    <br><strong>Coordenadas:</strong><br>" + feature.properties.estacao.latitude + ", " + feature.properties.estacao.longitude + "\
                    <br><strong>Status:</strong> " + (feature.properties.estacao.online ? 'Online' : 'Offline') + "\
                    <br><br><a class='btn btn-default' href=" + url + " target='_blank'>Editar Esta&ccedil;&atilde;o</a>&nbsp" + "\n\
                    <a class='btn btn-warning' href=" + verUrl + " target='_blank'>Ver Estação</a>&nbsp" + "\
                        ";

                    }
                    layer.bindPopup(popupContent);
                }

                function HandleAjax(url, mapa)
                { //Organiza AJAX
                    $.get(url).done(//URL
                            function (data) {
                                estacoes = L.geoJSON([data], {
                                    onEachFeature: onEachFeature,
                                    pointToLayer: function (feature, latlng)
                                    {
                                        //console.log(feature)
                                        if (!feature.properties.estacao.online)
                                        {
                                            opcoesIcone = {icon: offlineIcon};
                                        } else
                                        {
                                            opcoesIcone = {};
                                        }
                                        var marker = L.marker(latlng, opcoesIcone);
                                        //    marker.bindTooltip(feature.properties.estacao.identificador, { permanent: true, direction: 'right', opacity: 1, backgroundColor: 'transparent'});

                                        marker.bindTooltip(feature.properties.estacao.identificador, {
                                            direction: 'right',
                                            permanent: true,
                                            opacity: 1,
                                        });


                                        marker.on('tooltipopen', function (e) {
                                            var tooltip = e.tooltip._container;
                                            tooltip.style.background = 'transparent';
                                            tooltip.style.border = 'none';
                                            tooltip.style.boxShadow = 'none';
                                            tooltip.style.color = 'green';  //ALTERAÇÃO DA COR DA FONTE DAS LEGENDAS DOS MARCADORES NO MAPA

                                        });

                                        return marker;
                                    }
                                }).addTo(mapa); //adc os marcadores
                                mapa.fitBounds(estacoes.getBounds());
                            }).fail(function (jqXHR, textStatus, errorThrown)
                    {
                        console.error(jqXHR);
                        console.error(textStatus);
                        console.error(errorThrown);
                        alert('Houve erros durante o processamento da solicitação.');
                    });
                }

                function GerenciaMarcador(mapa)
                {
                    // CORREÇÃO DE BUG: AO DESMARCAR A ESTAÇÃO, O MAP MARKER CONTINUAVA NO MAPA
                    function isMarker(layer) { //ESSA FUNÇÃO VERIFICA SE A CAMADA (LAYER) É DE UM MARKERPOINT
                        return layer instanceof L.Marker;
                    }

                    // Iterar sobre todas as camadas do mapa e remover os marcadores Marker
                    mapa.eachLayer(function (layer) {
                        if (isMarker(layer)) { //SE FOR DO TIPO, REMOVE.
                            mapa.removeLayer(layer); //ISSO É FEITO PARA NÃO APAGAR TODAS AS LAYERS, INCLUINDO A LAYER principal DO MAPA (MAP)
                        }
                    });
                    let estacaoIDS = $("#estacao_selecionada").val(); //Organização da URL pelos IDs selecionados no multiselect menu
                    if (!estacaoIDS) {
                        let atividade = true;
                        let url = BASE_URL + 'Estacoes/getEstacoesGeoJson/?ids=&ativa=' + atividade; //SE NÃO HOUVER ESTAÇÃO MARCADA
                        return url;
                    } else {
                        let atividade = true;
                        let url = BASE_URL + 'Estacoes/getEstacoesGeoJson/?ids=' + estacaoIDS.join(',') + '&ativa=' + atividade; //SE HOUVER, A URL É ORGANIZADA para retornar os IDs de marcadores selecionados
                        return url;
                    }

                }

                $(function () { //onload da page
                    // Configurações do multiselect
                    $("#estacao_selecionada").multiselect({
                        includeSelectAllOption: true,
                        buttonWidth: '100%'
                    });
                    let mapa = criaMapa(); //ciação do mapa
                    let url = GerenciaMarcador(mapa); //organização dos macadores
                    let result = HandleAjax(url, mapa); //Requisições + adiciona os markers

                    //////EVENTO DE CHANGE DAS ESTAÇÕES
                    $('.change_controller').change(function () {
                        let url = GerenciaMarcador(mapa); //organização dos macadores
                        let result = HandleAjax(url, mapa); //Requisições + adiciona os markers
                    });
                });
            {/literal}
        </script>



    {/if}

{/block}
