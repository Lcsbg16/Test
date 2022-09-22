{* leaflet *}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.8.0/dist/leaflet.css"
      integrity="sha512-hoalWLoI8r4UszCkZ5kL8vayOGVae1oxXe/2A4AO6J9+580uKHDO3JdHb7NzwwzK5xr/Fs0W40kiNHxM9vyTtQ=="
      crossorigin=""/>

<script src="https://unpkg.com/leaflet@1.8.0/dist/leaflet.js"
        integrity="sha512-BB3hKbKWOc9Ez/TAwyWxNXeoV9c1v6FIeYiBieIWkpLjauysF18NzgR1MBNBXf8/KABdlkX68nAhlwcDFLGPCQ=="
crossorigin=""></script>

<style>
    #map_picker{
        width: 100%;
        height: 300px;
    }
</style>
{* /leaflet *}
<div id="map_picker_dialog" title="Selecionar Localiza&ccedil;&atilde;o" >
    <!--<input type="text" class="form-control" placeholder="Buscar endere&ccedil;o">-->
    <div id="map_picker"></div>
</div>
<div class="row">
    <div class="col-sm-4">
        <input id="field-latitude" class="form-control" name="latitude" type="text" value="{$latitude|strtr:'.':','}">
    </div>
    <div class="col-sm-4">
        <input id="field-longitude" class="form-control" name="longitude" type="text" value="{$longitude|strtr:'.':','}">
    </div>
    <div class="col-sm-4">
        <button class="btn btn-default button_map_picker" type="button" >Selecionar no Mapa</button>
    </div>
</div>

<script>


    var map = null;
    var map_picker_dialog = null;
    var popup = L.popup();

    function selecionarLocalizacaoMapa(lat, lon)
    {
        lat = lat.toString().replace('.', ',');
        lon = lon.toString().replace('.', ',');
        $('#field-latitude').val(lat);
        $('#field-longitude').val(lon);
        map_picker_dialog.dialog("close");
    }

    function abrirDialogoMapa()
    {
        map_picker_dialog.dialog("open");

        if (map != null)
        {
            map.off();
            map.remove();
        }

        map = L.map('map_picker').setView([{$latitude|default:'-22.368461'}, {$longitude|default:'-41.774747'}], 13);
    {literal}
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap'
            }).addTo(map);
    {/literal}

            function onMapClick(e) {
                popup
                        .setLatLng(e.latlng)
                        .setContent("<strong>Coordenadas:</strong> " + e.latlng.toString() + '<br><br><button class="btn btn-success" onclick="selecionarLocalizacaoMapa(' + e.latlng.lat + ',' + e.latlng.lng + ')">Selecionar esta localiza&ccedil;&atilde;o</button>')
                        .openOn(map);
            }

            map.on('click', onMapClick);
        }

        $(function () {
            map_picker_dialog = $("#map_picker_dialog").dialog({
                autoOpen: false,
                height: 500,
                width: 600,
                modal: true,
                buttons: {
                    'Cancelar': function () {
                        map_picker_dialog.dialog("close");
                    }
                },
                close: function () {

                }
            });




            $(".button_map_picker").on("click", abrirDialogoMapa);

        });


</script>