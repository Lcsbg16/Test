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
            height: 470px;
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
                        <label>Camada</label>
                        <select class="form-control form-control-sm">
                            <option value="">Pluviometria</option>
                        </select>
                    </div>
                    <div class="card-body">
                    <label>Esta&ccedil;&atilde;o</label>
                    <select class="form-control form-control-sm change_controler" id="estacao_selecionada" multiple> <!-- Id indica qual estação foi selecionada --> 
                        {foreach $estacoes as $eAtual}
                            <option value="{$eAtual.id}" selected id="estacao_descricao"> {$eAtual.descricao} ({$eAtual.identificador})</option>
                        {/foreach}
                      
                    </select>
                </div>
                </div>
            
                <div class="card shadow">
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
        function criaMapa()
        {
            var map = L.map('map').setView([-22.368461, -41.774747], 13);
            {literal}
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap'
            }).addTo(map);

            
            return map;
        }

        
        function onEachFeature(feature, layer) {
            var popupContent = '';
            if (feature.properties) {
                console.log(feature.properties);
                popupContent += feature.properties.estacao.descricao + ' (' + feature.properties.estacao.identificador + ')';
                popupContent += '<br><br><strong>&Uacute;ltima leitura:</strong> ' + feature.properties.ultimaLeitura.datahora_formatada
                popupContent += "\
                        <br><strong>Temperatura:</strong> " + feature.properties.ultimaLeitura.temperatura + "\
                        <br><strong>Umidade do ar:</strong> " + feature.properties.ultimaLeitura.umidade_ar + "\
                        <br><strong>Velocidade do vento:</strong> " + feature.properties.ultimaLeitura.velocidade_vento + "\
                        <br><strong>Direção do vento:</strong> " + feature.properties.ultimaLeitura.dir_vento + "\
                        <br><strong>Volume de chuva:</strong> " + feature.properties.ultimaLeitura.volume_chuva + "\
                        <br><strong>Volume acumulado de chuva:</strong> " + feature.properties.ultimaLeitura.volume_acc_chuva + "\
                            ";
{/literal}
            }

            layer.bindPopup(popupContent);
        }

                function HandleAjax(url, mapa){
                $.get(url).done(
                    function (data) {
                        console.log(data);
                        estacoes = L.geoJSON([data], {
                            style: function (feature) {
                                return feature.properties && feature.properties.style;
                            },
                            onEachFeature: onEachFeature,
                            pointToLayer: function (feature, latlng) {

                                if (feature.properties.camada.cor)
                                {
                                    cor = feature.properties.camada.cor;
                                } else
                                {
                                    cor = '#0700DF';
                                }

                                return L.circleMarker(latlng, {
                                    radius: 8,
                                    fillColor: cor,
                                    color: '#000',
                                    weight: 1,
                                    opacity: 1,
                                    fillOpacity: 0.8
                                });
                            }
                        }).addTo(mapa);
                        console.log(estacoes);
                        mapa.fitBounds(estacoes.getBounds());
                    })
                        .fail(function (jqXHR, textStatus, errorThrown) {
                            console.error(jqXHR);
                            console.error(textStatus);
                            console.error(errorThrown);
                            alert('Houve erros durante o processamento da solicitação.');
                        });
                    }

                    function GerenciaMarcador(mapa){
                        function isCircleMarker(layer) { //ESSA FUNÇÃO VERIFICA SE A CAMADA (LAYER)
                        return layer instanceof L.CircleMarker; //É DO TIPO CIRCLEMARKER
                        }

                        // Iterar sobre todas as camadas do mapa e remover os marcadores circleMarker
                        mapa.eachLayer(function (layer) {
                        if (isCircleMarker(layer)) { //SE FOR DO TIPO, REMOVE. 
                            mapa.removeLayer(layer); //ISSO É FEITO PARA NÃO APAGAR TODAS AS LAYERS, INCLUINDO A LAYER DO MAPA (MAP)
                        }
                        });  

                    let estacaoIDS = $( "#estacao_selecionada" ).val();
                       
                    if(!estacaoIDS){
                            var url = BASE_URL + 'Estacoes/getEstacoesGeoJson/?ids=';
                            return url;
                            }
                        else {
                            var url = BASE_URL + 'Estacoes/getEstacoesGeoJson/?ids=' + estacaoIDS.join(',');
                            return url;
                        }

                    }


            $(function () {

                $("#estacao_selecionada").multiselect({
                includeSelectAllOption: true,
                buttonWidth: '100%'
                });

              let mapa = criaMapa();
              let url = GerenciaMarcador(mapa);
              let result = HandleAjax(url, mapa);

                   
                //EVENTO DE CHANGE DAS ESTAÇÕES 
                $('.change_controler').change(function() { 
                
                  let url = GerenciaMarcador(mapa); //organização dos macadores 
                  let result = HandleAjax(url, mapa); //Requisições + adiciona os markers
                });
                
                        
            });
                
        </script>
    {/if}

{/block}