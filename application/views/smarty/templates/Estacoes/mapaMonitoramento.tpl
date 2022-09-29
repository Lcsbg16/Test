{extends file = 'app_logado.tpl'}
{block name = "conteudo_logado"}
    {* leaflet *}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.8.0/dist/leaflet.css"
          integrity="sha512-hoalWLoI8r4UszCkZ5kL8vayOGVae1oxXe/2A4AO6J9+580uKHDO3JdHb7NzwwzK5xr/Fs0W40kiNHxM9vyTtQ=="
          crossorigin=""/>

    <script src="https://unpkg.com/leaflet@1.8.0/dist/leaflet.js"
            integrity="sha512-BB3hKbKWOc9Ez/TAwyWxNXeoV9c1v6FIeYiBieIWkpLjauysF18NzgR1MBNBXf8/KABdlkX68nAhlwcDFLGPCQ=="
    crossorigin=""></script>

    <style>
        #map{
            width: 100%;
            height: 470px;
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
            $(function () {
                var map = L.map('map').setView([-22.368461, -41.774747], 13);
            {literal}
                    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                        attribution: '© OpenStreetMap'
                    }).addTo(map);
                    var estacoes = null;
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
                    $.get(BASE_URL + 'Estacoes/getEstacoesGeoJson/').done(
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
                                }).addTo(map);
                                console.log(estacoes);
                                map.fitBounds(estacoes.getBounds());
                            })
                            .fail(function (jqXHR, textStatus, errorThrown) {
                                console.error(jqXHR);
                                console.error(textStatus);
                                console.error(errorThrown);
                                alert('Houve erros durante o processamento da solicitação.');
                            });
                });

        </script>
    {/if}

{/block}