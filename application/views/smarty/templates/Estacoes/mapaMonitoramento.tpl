{extends file = 'app_logado.tpl'}
{block name = "conteudo_logado"}
    {* leaflet *}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.8.0/dist/leaflet.css"
          integrity="sha512-hoalWLoI8r4UszCkZ5kL8vayOGVae1oxXe/2A4AO6J9+580uKHDO3JdHb7NzwwzK5xr/Fs0W40kiNHxM9vyTtQ=="
          crossorigin=""/>

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
        <div class="row" style="height: auto;" >
            <div class="col">
                <div class="card shadow">
                    <div class="card-body card-body-top" style="padding-bottom: 0;">
                        <div class="row justify-content-center"> 
                            <div class="col-lg-4 col-sm-12 form-group">
                                <label for="camada_id">Camada: </label>
                                <select class="form-control form-control-sm change_controller" id="camada_id" name="camada_id">
                                    {html_options options=$camadas}
                                </select>
                            </div>
                            <div class="col-lg-4 col-sm-12 form-group">
                                <label for="estacao_selecionada">Esta&ccedil;&otilde;es Ativas:</label>
                                <select class="form-control form-control-sm change_controller" id="estacao_selecionada" multiple> 
                                    {foreach $estacoes as $eAtual}
                                        <option value="{$eAtual.id}" selected id="estacao_descricao"> {$eAtual.descricao} ({$eAtual.identificador})</option>
                                    {/foreach}
                                </select>
                            </div>
                            <div class="col-lg-2 col-sm-12  form-group d-flex justify-content-center align-items-center" style="margin: 0;">
                            <!-- Botão de abertura do Modal -->
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#legendasModal" style="margin: 21px!important;"> Legendas </button>
                            </div>
                        </div>
                        <div class="form-check" style="margin: 0px 0px 0px 127px;">
                        <input class="form-check-input" type="checkbox" value="" id="estacoes-online-checkbox">
                        <label class="form-check-label" for="flexCheckDefault">
                            Mostrar apenas estações online
                        </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
            <div class="row" >
                <div class="col">
                <div class="card shadow">
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

    <!-- Modal para as legendas -->
        <div class="modal fade" id="legendasModal" tabindex="-1" role="dialog" aria-labelledby="legendasModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="legendasModalLabel">Legendas: </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body" id="modal-body">
                
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                </div>
            </div>
            </div>
        </div>

    {if $estacoes}

        <script>
            {literal}

                /*AJAX PARA CARREGAMENTO DAS LEGENDAS NO MODAL*/ 
                function buscaLegendaCamada(camada) {
                    let url = BASE_URL + 'Estacoes/getLegendaMonitoramento/' + camada;
                    $.ajax({
                        url: url,
                        dataType: "json",
                        method: "GET"
                    }).done(function (data) {
                        $("#modal-body").html(data);
                    }).fail(function (jqXHR, textStatus, errorThrown) {
                        console.error("Erro na requisição AJAX para gerar as legendas:", errorThrown);
                    });
                }

                $('#legendasModal').on('shown.bs.modal', function (e) {
                    let camada = $('#camada_id').val();
                    buscaLegendaCamada(camada);
                });

                /* FIM DO AJAX PARA CARREGAMENTO DAS LEGENDAS NO MODAL*/


                function criaMapa()
                {
                    var map = L.map('map').setView([-22.368461, -41.774747], 13);
                    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                        attribution: '© OpenStreetMap'
                    }).addTo(map);
                    return map;
                }


                function onEachFeature(feature, layer) {
                    var popupContent = '';
                    if (feature.properties) {
                        popupContent += feature.properties.estacao.descricao + ' (' + feature.properties.estacao.identificador + ')';
                        popupContent += '<br><br><strong>Status:</strong> ' + (feature.properties.estacao.online ? 'Online' : 'Offline');
                        if (feature.properties.ultimaLeitura)
                        { 
                            popupContent += '<br><strong>&Uacute;ltima leitura:</strong> ' + (feature.properties.ultimaLeitura.datahora_formatada ? feature.properties.ultimaLeitura.datahora_formatada : "Sem registro")
                            popupContent += "\
                        <br><strong>Temperatura:</strong> " + (feature.properties.ultimaLeitura.temperatura ? parseFloat(feature.properties.ultimaLeitura.temperatura).toFixed(2).replace(".", ",") + " &#176;C" : "Sem registro") + "\
                        <br><strong>Umidade do ar:</strong> " + (feature.properties.ultimaLeitura.umidade_ar ? parseFloat(feature.properties.ultimaLeitura.umidade_ar).toFixed(2).replace(".", ",") + "%" : "Sem registro") + "\
                        <br><strong>Velocidade do vento:</strong> " + (feature.properties.ultimaLeitura.velocidade_vento ? parseFloat(feature.properties.ultimaLeitura.velocidade_vento).toFixed(2).replace(".", ",") + " km/h" : "Sem registro") + "\
                        <br><strong>Rajada de vento:</strong> " + (feature.properties.ultimaLeitura.rajada_vento_1h ? parseFloat(feature.properties.ultimaLeitura.rajada_vento_1h).toFixed(2).replace(".", ",") + " km/h" : "Sem registro") + "\
                        <br><strong>Direção do vento:</strong> " + (feature.properties.ultimaLeitura.dir_vento ? feature.properties.ultimaLeitura.dir_vento + "&#176;" : "Sem registro") + "\
                        <br><strong>Acúmulo de chuva (1h):</strong> " + (feature.properties.ultimaLeitura.volume_acumulado_1h ? parseFloat(feature.properties.ultimaLeitura.volume_acumulado_1h).toFixed(2).replace(".", ",") + " mm&sup3;" : "Sem registro") + "\
                        <br><strong>Acúmulo de chuva (24h):</strong> " + (feature.properties.ultimaLeitura.volume_acumulado_24h ? parseFloat(feature.properties.ultimaLeitura.volume_acumulado_24h).toFixed(2).replace(".", ",") + " mm&sup3;" : "Sem registro") + "\
                        <br><strong>Acúmulo de chuva (96h):</strong> " + (feature.properties.ultimaLeitura.volume_acumulado_96h ? parseFloat(feature.properties.ultimaLeitura.volume_acumulado_96h).toFixed(2).replace(".", ",") + " mm&sup3;" : "Sem registro") + "\
                            ";
                        } else
                        {
                            popupContent += '<br><br><strong>Não há leituras recentes registradas.</strong>'
                        }
                    }

                    layer.bindPopup(popupContent);
                }

                var controlaAcionamentoBounds = false; 


                function criaCircleMarker(latlng, cor, feature){
                    let options;

                    if (feature.properties.estacao.tipo === "interna") {
                        options = {
                            radius: 16,
                            fillColor: cor,
                            color: '#000',
                            weight: 1,
                            opacity: 1,
                            fillOpacity: 0.8
                        };
                    } else {
                        options = {
                            radius: 16,
                            fillColor: cor,
                            color: '#FF0000',
                            weight: 2,
                            opacity: 1,
                            fillOpacity: 0.8
                        };
                    }

                    let circleMarker = L.circleMarker(latlng, options);

                    circleMarker.bindTooltip(feature.properties.estacao.identificador, {
                        direction: 'right',
                        permanent: true,
                        opacity: 1,
                        offset: [10, 0]
                    });

                    circleMarker.on('tooltipopen', function (e) {
                        let tooltip = e.tooltip._container;
                        tooltip.style.background = 'transparent';
                        tooltip.style.border = 'none';
                        tooltip.style.boxShadow = 'none';
                        tooltip.style.color = 'green';
                    });

                     // Remoção de pointer residual
                    let styles = ` .leaflet-tooltip-right:before, .leaflet-tooltip-left:before {  display: none !important; }`;
                    let styleSheet = document.createElement("style");
                    styleSheet.type = "text/css";
                    styleSheet.innerText = styles;
                    document.head.appendChild(styleSheet);

                    return circleMarker;
                }

                function criaArrowMarker(latlng, feature) {
                    let angulo = feature.properties.ultimaLeitura ? feature.properties.ultimaLeitura.dir_vento : 0;
                    let arrowHead = BASE_URL + 'assets/images/arrowHead.png'
                    let marker = L.marker(latlng, {
                        icon: L.divIcon({
                            className: 'leaflet-rotated-icon', 
                            html: `<div style="transform: rotate(${angulo}deg);"><img src="${arrowHead}" width="23" height="23" /></div>`,
                            iconSize: [32, 32] 
                        })
                    });

                    marker.bindTooltip(feature.properties.estacao.identificador, { 
                        direction: 'right',
                        permanent: true,
                        opacity: 1,
                    });

                    marker.on('tooltipopen', function (e) {
                        let tooltip = e.tooltip._container;
                        tooltip.style.background = 'transparent';
                        tooltip.style.border = 'none';
                        tooltip.style.boxShadow = 'none'; 
                        tooltip.style.color = 'green';
                    });

                    // Remoção de pointer residual
                    let styles = ` .leaflet-tooltip-right:before, .leaflet-tooltip-left:before {  display: none !important; }`;
                    let styleSheet = document.createElement("style");
                    styleSheet.type = "text/css";
                    styleSheet.innerText = styles;
                    document.head.appendChild(styleSheet);

                    return marker;
                }

                function carregarTodasEstacoes(url, mapa) {
                        let camada = $('#camada_id').val();
                        if (camada == "dir_vento") {
                                        carregarEstacoesOnline(url, mapa)
                                        return;
                        }
                        $.get(url).done(
                            function (data) {
                                estacoes = L.geoJSON([data], {
                                    style: function (feature) {
                                        return feature.properties && feature.properties.style;
                                    },
                                    onEachFeature: onEachFeature,
                                    pointToLayer: function (feature, latlng) {
                                        if (feature.properties.estacao.online && feature.properties.camada.cor)
                                        {
                                            cor = feature.properties.camada.cor;
                                        } else {
                                            cor = '#bebebe';
                                        }     
                                      
                                        return criaCircleMarker(latlng, cor, feature);
                                        
                                    }
                        }).addTo(mapa);
                                if (!controlaAcionamentoBounds) {
                                   console.log("bounds: " + controlaAcionamentoBounds);
                                     mapa.fitBounds(estacoes.getBounds());
                                     controlaAcionamentoBounds = true; 
                                     }                         
                                })
                            .fail(function (jqXHR, textStatus, errorThrown) {
                                console.error(jqXHR);
                                console.error(textStatus);
                                console.error(errorThrown);
                                alert('Houve erros durante o processamento da solicitação.');
                            });
                }
            
                function carregarEstacoesOnline(url, mapa) {
                    let camada = $('#camada_id').val();
                    $.get(url).done(function (data) {
                        var estacoesOnline = data.features.filter(function (feature) {
                            return feature.properties.estacao.online;
                        })
                        estacoes = L.geoJSON(estacoesOnline, {
                                    style: function (feature){
                                         return feature.properties && feature.properties.style 
                                    },
                                    onEachFeature: onEachFeature,
                                    pointToLayer: function (feature, latlng) {
                                        if (feature.properties.camada.cor) {
                                            cor = feature.properties.camada.cor 
                                            } else { cor = '#bebebe'
                                            }
                                            if (camada == "dir_vento")
                                                return criaArrowMarker(latlng, feature)
                                            else 
                                                return criaCircleMarker(latlng, cor, feature)
                                    }
                        }).addTo(mapa)
                        if (!controlaAcionamentoBounds) {
                            console.log("bounds: " + controlaAcionamentoBounds)
                            mapa.fitBounds(estacoes.getBounds());
                            controlaAcionamentoBounds = true
                        }       
                    })
                    .fail(function (jqXHR, textStatus, errorThrown) {
                        console.error(jqXHR)
                        console.error(textStatus)
                        console.error(errorThrown)
                        alert('Houve erros durante o processamento de carregamento das estações...')
                     })
                }

                    function removerMarcadores(mapa) {
                        function isMarker(layer) {
                            return layer instanceof L.CircleMarker || layer instanceof L.Marker;
                        }

                        mapa.eachLayer(function (layer) {
                            if (isMarker(layer)) {
                                mapa.removeLayer(layer);
                            }
                        });
                    }

                    function gerarUrlEstacoes() {
                        let idEstacoes = $("#estacao_selecionada").val();
                        let atividade = true; // não mostra as inativas
                        let url;

                        if (!idEstacoes) {
                            url = BASE_URL + 'Estacoes/getEstacoesGeoJson/' + $('#camada_id').val() + '/?ids=&ativa=' + atividade;
                        } else {
                            url = BASE_URL + 'Estacoes/getEstacoesGeoJson/' + $('#camada_id').val() + '/?ids=' + idEstacoes.join(',') + '&ativa=' + atividade;
                        }

                        return url;
                    }

                $(function () 
                {
                    $("#estacao_selecionada").multiselect({
                        includeSelectAllOption: true,
                        buttonWidth: '100%'
                    });
                    
                    let mapa = criaMapa();
                    removerMarcadores(mapa)
                    let url = gerarUrlEstacoes();
                    let result = carregarTodasEstacoes(url, mapa);

                
                        setInterval(() => {
                            removerMarcadores(mapa)
                        let url = gerarUrlEstacoes();
                        var checkboxMarcada = $("#estacoes-online-checkbox").is(":checked");
                        if (checkboxMarcada == true) {
                                let result = carregarEstacoesOnline(url, mapa); 
                            } else {
                                let result = carregarTodasEstacoes(url, mapa); 
                            } 
                        }, 30000);

                    $('#estacoes-online-checkbox, .change_controller').change(function () {
                        var checkboxMarcada = $("#estacoes-online-checkbox").is(":checked");
                        removerMarcadores(mapa)
                        let url = gerarUrlEstacoes(); 
                        if (checkboxMarcada) {
                            carregarEstacoesOnline(url, mapa); 
                        } else {
                            carregarTodasEstacoes(url, mapa); 
                        }
                    })
                })
            {/literal}
        </script>
    {/if}

{/block}