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
            height: 500px;
        }
        #estacao_selecionada + .btn-group .multiselect { /*ALTERAÇÃO DO CSS DO MULTISELECT BUTTON - SELECIONAR MULTIPLAS ESTAÇÕES*/ 
       /* Deixando modelo do selecionar camada */
       font-size: 0.875rem;
       text-align: left !important;
       height: calc(1.8125rem + 2px);
        }
    </style>
    {* /leaflet *}

    <div class="container-fluid mt-3">
        <div class="row">
            <div class="col">
                <div class="card shadow">
                    <div class="card-body">
                    <label>Esta&ccedil;&atilde;o</label>
                    <select class="form-control form-control-sm change_controler" id="estacao_selecionada" multiple> <!-- Id indica qual estação foi selecionada --> 
                        {foreach $estacoes as $eAtual}
                            <option value="{$eAtual.id}" selected id="estacao_descricao"> {$eAtual.descricao} ({$eAtual.identificador})</option>
                        {/foreach}
                    
                    </select>
                </div>

                    <div class="card-body">
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

            function criaMapa() {
                var map = L.map('map').setView([-22.368461, -41.774747], 13);
        {literal}
                L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '© OpenStreetMap'
                }).addTo(map);
                
                var estacoes = null;
              
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
                    <br><br><a class='btn btn-default' href="+url+" target='_blank'>Editar Esta&ccedil;&atilde;o</a>&nbsp" + "\
                    <a class='btn btn-warning' href="+verUrl+ " target='_blank'>Ver Estação</a>&nbsp" + "\
                        ";
        {/literal}
                    }
                    layer.bindPopup(popupContent);
                }

                function HandleAjax(url, mapa)
                { //Organiza AJAX
                    $.get(url).done( //URL
                        function (data) {
                            estacoes = L.geoJSON([data], {
                                onEachFeature: onEachFeature,
                                pointToLayer: function (feature, latlng) 
                                {
                                    return L.marker(latlng);
                                    
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

                    let estacaoIDS = $( "#estacao_selecionada" ).val(); //Organização da URL pelos IDs selecionados no multiselect menu
                    if(!estacaoIDS){
                        let url = BASE_URL + 'Estacoes/getEstacoesGeoJson/?ids='; //SE NÃO HOUVER ESTAÇÃO MARCADA
                        return url;
                    } else {
                        let url = BASE_URL + 'Estacoes/getEstacoesGeoJson/?ids=' + estacaoIDS.join(','); //SE HOUVER, A URL É ORGANIZADA para retornar os IDs de marcadores selecionados 
                        return url;
                    }
             
                 }

        $(function () { //onload da page

            $("#estacao_selecionada").multiselect({ //Configurações do Multiselect
                includeSelectAllOption: true,
                buttonWidth: '100%'
                        });

            let mapa = criaMapa(); //ciação do mapa
            let url = GerenciaMarcador(mapa); //organização dos macadores 
            let result = HandleAjax(url, mapa); //Requisições + adiciona os markers

         //////EVENTO DE CHANGE DAS ESTAÇÕES 
            $('.change_controler').change(function() { 
                let url = GerenciaMarcador(mapa); //organização dos macadores 
                let result = HandleAjax(url, mapa); //Requisições + adiciona os markers
                });

        });
        </script>
            
    
            
      {/if}

 {/block}

        <!-- 
    
        <script>
            $(function () {
                var map = L.map('map').setView([-22.368461, -41.774747], 13);
            { literal}
                    L.tileLayer('https://tile.openstreetmap.org/{ z}/{ x}/ { y}.png', {
                        maxZoom: 19,
                        attribution: '© OpenStreetMap'
                    }).addTo(map);
            { / literal}

                    estacoes = new Array();
            { foreach $estacoes as $eAtual}
                 { if ($eAtual.latitude and $eAtual.longitude) or $eAtual.endereco}
                     { if !$eAtual.latitude or !$eAtual.longitude}
                    var coordenadas = null;
                        {*
                        $.ajax({
                        url: 'https://nominatim.openstreetmap.org/ui/search.html',
                        type: 'GET',
                        async: false,
                        cache: false,
                        timeout: 30000,
                        data: {
                        'q': '{$eAtual.endereco|escape:'quotes'}'
                        },
                        dataType: 'json',
                        crossDomain: true,
                        headers: {
                        'Access-Control-Allow-Origin': '*',
                        },
                        beforeSend: function (xhr) {
                        xhr.setRequestHeader("Authorization", "Basic " + btoa(""));
                        },
                        fail: function () {
                        console.log('Falha ao buscar coordenadas pelo endereço.')
                        },
                        done: function (data) {
                        var coordenadas = [data[0].lat, data[0].lon];
                        console.log('Endereço encontrado: ' + coordenadas);
                        }
                        });
                        *}
                   /* { else}
                    coordenadas = [Number('{ $eAtual.latitude}'), Number('{ $eAtual.longitude}')];
                     { /if}
                    if (coordenadas != null)
                    {
                        var marker = L.marker(coordenadas).addTo(map);
                        marker.bindPopup("<h3>{ $eAtual.descricao|escape:'quotes'} ({ $eAtual.identificador|escape:'quotes'})</h3><br><strong>Endere&ccedil;o:</strong> { $eAtual.endereco|default:'-'|escape:'quotes'}<br><strong>Coordendas:</strong> " + coordenadas + '<br><br><a class="btn btn-default" href="{$BASE_URL}AdminEstacoes/index/edit/{ $eAtual.id}" target="_blank">Editar Esta&ccedil;&atilde;o</a>&nbsp;<a class="btn btn-warning" href="javascript:alert(\'Ainda não implementado.\')" >Ver Resultados</a>');
                        estacoes.push(marker);
                    }
                { /if}
            { / foreach}

                    var group = new L.featureGroup(estacoes);
                    map.fitBounds(group.getBounds());
                });
        </script>
     { /if} */

{ /block}
        
        
        
        
        --> 