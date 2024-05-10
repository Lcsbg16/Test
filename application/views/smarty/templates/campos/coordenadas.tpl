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
        height: 600px;
    }
</style>
{* Leaflet.Control.Search *}

<link rel="stylesheet" href="{$BASE_URL}assets/bower_components/leaflet-search/dist/leaflet-search.min.css">
<script src="{$BASE_URL}assets/bower_components/leaflet-search/dist/leaflet-search.min.js"></script>

{* /Leaflet.Control.Search *}

{* Plugin externo para o leaflet, navegação por lupa, canto superior direito*}
<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>

{* /leaflet *}

<div id="map_picker_dialog" title="Selecionar Localiza&ccedil;&atilde;o" >
    {* <input type="text" class="form-control" placeholder="Buscar endere&ccedil;o"> *}
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

function pesquisarEndereco()
{
     $("#field-endereco").change(function()
     { //QUANDO MUDANÇA NO ENDEREÇO
            let enderecoPesquisado = $('#field-endereco').val();
            $.get(location.protocol + '//nominatim.openstreetmap.org/search?addressdetails=1&format=json&q='+enderecoPesquisado, function(result) //transforma  endereço escrito em lat e longitude
            { 
                if(result && result.length > 0)
                {   
                    $('.form-group.endereco_form_group').removeClass('has-error');
                    if (result[0].lat && result[0].lon) 
                        { 
                            ajustarEndereco(result[0].lat, result[0].lon, result[0].address); //Ajuste do endereço para um padrão 
                        }else
                         {
                            console.log("Endereço não encontrado, tente selecionar no mapa"); 
                            $('.form-group.endereco_form_group').addClass('has-error');
                            return;
                        }   
                }
                else {
                    console.log("Endereço não encontrado, tente selecionar no mapa"); 
                    $('.form-group.endereco_form_group').addClass('has-error');
                    return;
                }

              
            });
        });
}pesquisarEndereco();          

    var map = null;
    var map_picker_dialog = null;
    var popup = L.popup();
    var marker = null;

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

        map = L.map('map_picker').setView([-22.368461, -41.774747], 5);

    {literal}
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap'
            }).addTo(map);

            // Essa funcção centraliza o mapa de acordo com a lat  e longitudo nos campos
            if ($('#field-latitude').val() != '' && $('#field-longitude').val() != '')
            {
                lat = Number($('#field-latitude').val().replace(',', '.')); 
                lon = Number($('#field-longitude').val().replace(',', '.'));

                marker = L.marker([lat, lon]).addTo(map);
                var group = new L.featureGroup([marker]);
                map.fitBounds(group.getBounds(), {padding: [50, 50]});
            }

            function onMapClick(e) {
                popup
                        .setLatLng(e.latlng)
                        .setContent("<strong>Coordenadas:</strong> " + e.latlng.toString() + '<br><br><button class="btn btn-success" onclick="selecionarLocalizacaoMapa(' + e.latlng.lat + ',' + e.latlng.lng + ')">Selecionar esta localiza&ccedil;&atilde;o</button>')
                        .openOn(map);
            }

            map.on('click', onMapClick);


         ///////ESSA É A BUSCA VIA PLUGIN EXTERNO //////
           let geocoder = L.Control.geocoder().addTo(map); //adiciona a lupa de pesquisa no mapa 
            
            geocoder.on('markgeocode', function(e) { //quando a lupa de geolocalização for usada
                    onGeocodeResult(e.geocode); //cjama a função de resposta do geocodificador 
                    });

            
            function onGeocodeResult(result) // Função de resposta do geocodificador, após a busca
            { 
                if (result && result.center) // Verifica se o resultado possui uma localização que tenha sido encontrada
                    {
                        ajustarEndereco(result.center.lat, result.center.lng, result.properties.address); //funcão que monta o endereço no formato rua, bairro, cidade, estado, cep, pais
                    }
            }
              

        ///ESSE É O CÓDIGO DA LUPA DE BUSCA ANTIGO:////////////////////////////////////
         /*   map.addControl(new L.Control.Search({
                url: 'https://nominatim.openstreetmap.org/search?format=json&q={*s}',
                jsonpParam: 'json_callback',
                propertyName: 'display_name',
                propertyLoc: ['lat', 'lon'],
                marker: L.circleMarker([0, 0], {radius: 30*}),
                autoCollapse: true,
                autoType: false,
                minLength: 2
            }));*/

        }
    
        function ajustarEndereco(lat, lng, result) 
            { //função que organiza o endereço                       
                        let rua = result.road || result.pedestrian || result.cycleway; // pega a rua 
                        let bairro = result.suburb || result.neighbourhood || result.village; // pega o bairro 
                        let cidade = result.city || result.town;; //pega a cidade 
                        let estado =  result.state; // pega o estado
                        let cep = result.postcode; //pega o cep
                        let pais = result.country; // pega o país

                let endereco = [rua, bairro, cidade, estado, cep, pais].filter(Boolean).join(", ");
                    function settarInfo(lat, lng, endereco) //atualiza as informções de lat e long e endereço nas textboxes
                    { 
                            $('#field-latitude').val(lat);
                            $('#field-longitude').val(lng);
                            $('#field-endereco').val(endereco);
                    }

                    settarInfo(lat, lng, endereco);   
                    return endereco;
            }


    {/literal}

        $(function () {
            map_picker_dialog = $("#map_picker_dialog").dialog({
                autoOpen: false,
                height: 700,
                width: 800,
                modal: true,
                buttons: {
                    'Prosseguir': function () {
                        map_picker_dialog.dialog("close");
                    }
                },
                close: function () {

                }
            });

            $(".button_map_picker").on("click", abrirDialogoMapa);

        });


</script>