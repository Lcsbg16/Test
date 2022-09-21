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
            height: 500px;
        }
    </style>
    {* /leaflet *}

    <div class="container-fluid mt-3">
        <div class="row">
            <div class="col">
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
            {/literal}

                    estacoes = new Array();
            {foreach $estacoes as $eAtual}
                {if ($eAtual.latitude and $eAtual.longitude) or $eAtual.endereco}
                    {if !$eAtual.latitude or !$eAtual.longitude}
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
                    {else}
                    coordenadas = [Number('{$eAtual.latitude}'), Number('{$eAtual.longitude}')];
                    {/if}
                    if (coordenadas != null)
                    {
                        var marker = L.marker(coordenadas).addTo(map);
                        marker.bindPopup("<h3>{$eAtual.descricao|escape:'quotes'} ({$eAtual.identificador|escape:'quotes'})</h3><br><strong>Endere&ccedil;o:</strong> {$eAtual.endereco|default:'-'|escape:'quotes'}<br><strong>Coordendas:</strong> " + coordenadas + '<br><br><a class="btn btn-default" href="{$BASE_URL}AdminEstacoes/index/edit/{$eAtual.id}" target="_blank">Editar Esta&ccedil;&atilde;o</a>&nbsp;<a class="btn btn-warning" href="javascript:alert(\'Ainda não implementado.\')" >Ver Resultados</a>');
                        estacoes.push(marker);
                    }
                {/if}
            {/foreach}

                    var group = new L.featureGroup(estacoes);
                    map.fitBounds(group.getBounds());
                });
        </script>
    {/if}

{/block}