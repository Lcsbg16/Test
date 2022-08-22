{extends file = 'app_logado.tpl'}
{block name = "conteudo_logado"}

    {*<script src="{$BASE_URL}assets/js/grafico.js"></script>*}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js" integrity="sha512-ElRFoEQdI5Ht6kZvyzXhYG9NqjtkmlkfYk0wr6wHxU9JEHakS7UJZNeml5ALk+8IKlU6jDgMabC3vkumRokgJA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    {*<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>*}
    <div class="container-fluid mt-3">
        <div class="row">
            <div class="col">
                <div class="card shadow">
                    <div class="card-header">
                        <h3>Estat&iacute;sticas</h3>
                    </div>
                    <div class="card-body">
                        <div class="row my-3">
                            <div class="col">
                                <label>Esta&ccedil;&atilde;o</label>
                                <select class="form-control form-control-sm">
                                    <option>Estação A1</option>
                                </select>
                            </div>
                            <div class="col">
                                <label>Data Inicial</label>
                                <input class="form-control form-control-sm datepicker" value="{'-3 months'|strtotime|date_format:'%d/%m/%Y'}">
                            </div>
                            <div class="col">
                                <label>Data Final</label>
                                <input class="form-control form-control-sm datepicker" value='{$smarty.now|date_format:'%d/%m/%Y'}'>
                            </div>
                            <div class="col">
                                <label>Escala</label>
                                <select class="form-control form-control-sm">
                                    <option value="">Selecione</option>
                                    <option>Hora</option>
                                    <option>Dia</option>
                                    <option>Semana</option>
                                    <option selected>M&ecirc;s</option>
                                </select>
                            </div>
                            <div class="col">
                                <label>Tipo de Informa&ccedil;&atilde;o</label>
                                <select class="form-control form-control-sm">
                                    <option>Selecione</option>
                                    <option>Temperatura</option>
                                    <option>Volume de Chuva</option>
                                    <option>Volume de Chuva Acumulada</option>
                                    <option>Umidade do Ar</option>
                                </select>
                            </div>

                            <div class="col">
                                <label>Tipo de Gr&aacute;fico</label>
                                <select id="tipoGrafico" class="form-control form-control-sm">
                                    <option value="bar" >Barras</option>
                                    <option value="line">Linhas</option>
                                    <option value="radar">Radar</option>
                                </select>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="row my-12">
                    <div class="col-md-12 py-1">
                        <div class="card">
                            <div class="card-body" style="height: 400px">
                                <canvas id="grafico"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {literal}
        <script>
            var graficoObj = null;
            $('.datepicker').datepicker({
                format: 'dd/mm/yyyy',
                language: "pt-BR"
            });

            var config = {
                type: 'line',
                data: {
                    labels: ["Jan/2022", "Fev/2022", "Mar/2022", "Abr/2022", "Mai/2022", "Jun/2022", "Jul/2022"],
                    datasets: [{
                            label: "Estação A1 - Umidade do ar",
                            data: [27.50, 35.10, 29.70, 20., 25., 40., 15.],
                            fill: false,
                            borderColor: "#8B9DC8",
                            backgroundColor: "#8B9DC8"
                        }, ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            };

            var myChart;


            $('#tipoGrafico').on('change', function (event) {
                alterarTipoGrafico($('#tipoGrafico').val())
            });

            function alterarTipoGrafico(newType) {
                var ctx = document.getElementById("grafico").getContext("2d");

                // Remove the old chart and all its event handles
                if (myChart) {
                    myChart.destroy();
                }

                // Chart.js modifies the object you pass in. Pass a copy of the object so we can use the original object later
                var temp = jQuery.extend(true, {}, config);
                temp.type = newType;
                myChart = new Chart(ctx, temp);
            }
            ;
            alterarTipoGrafico($('#tipoGrafico').val());

        </script>


    {/literal}

{/block}



