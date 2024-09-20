{extends file = 'app_logado.tpl'}
{block name = "conteudo_logado"}


    {*<script src="{$BASE_URL}assets/js/grafico.js"></script>*}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js" integrity="sha512-ElRFoEQdI5Ht6kZvyzXhYG9NqjtkmlkfYk0wr6wHxU9JEHakS7UJZNeml5ALk+8IKlU6jDgMabC3vkumRokgJA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    {*<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>*}
    <link rel="stylesheet" href="{$BASE_URL}/assets/chosen/bootstrap-multiselect.css"/>
    <script type="text/javascript" src="{$BASE_URL}/assets/chosen/bootstrap-multiselect.js"></script>
    <style>
        #estacao_selecionada + .btn-group .multiselect { /*ALTERAÇÃO DO CSS DO MULTISELECT BUTTON - SELECIONAR MULTIPLAS ESTAÇÕES*/
            /* Deixando modelo do selecionar camada */
            font-size: 0.875rem;
            text-align: left !important;
            height: calc(1.8125rem + 2px);
        }

        .col-xl-personalizado{
            max-width: 14.2857%; /* 100% / 7 elementos */
        }
    </style>

    <div class="container-fluid mt-3">
        <div class="row">
            <div class="col">
                <div class="card shadow">
                    <div class="card-body">
                        <div class="row my-3">
                            <div class="col-sm-12 col-md-4 col-xl-personalizado">
                                <label>Esta&ccedil;&atilde;o</label>
                                <select class="form-control form-control-sm change_controller" id="estacao_selecionada" multiple> 
                                    {foreach $estacoes as $eAtual}
                                        {if $eAtual.ativa}
                                            <option value="{$eAtual.id}" id="estacao_descricao"> {$eAtual.descricao} ({$eAtual.identificador})</option>
                                        {/if}
                                    {/foreach}
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-4 col-xl-personalizado">
                                <label>Data Inicial</label>
                                <input class="form-control form-control-sm datepicker" value="{'-5 days'|strtotime|date_format:'%d/%m/%Y'}" id="dataInicial">
                            </div>
                            <div class="col-sm-12 col-md-4 col-xl-personalizado">
                                <label>Data Final</label>
                                <input class="form-control form-control-sm datepicker" value='{$smarty.now|date_format:'%d/%m/%Y'}' id="dataFinal">
                            </div>
                            <div class="col-sm-12 col-md-4 col-xl-personalizado">
                                <label>Escala</label>
                                <select class="form-control form-control-sm change_controller" id="escala_selecionada" >
                                    <option value="">Selecione</option>
                                    <option value="hora" selected>Hora</option>
                                    <option value="dia">Dia</option>
                                    <option value="semana">Semana</option>
                                    <option value="mes">M&ecirc;s</option>
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-4 col-xl-personalizado">
                                <label>Informa&ccedil;&atilde;o</label>
                                <select class="form-control form-control-sm change_controller" id="tipo_informacao" >
                                </select>
                            </div>

                            <div class="col-sm-12 col-md-4 col-xl-personalizado">
                                <label>Tipo de Gr&aacute;fico</label>
                                <select id="tipoGrafico" class="form-control form-control-sm">
                                    <option value="bar" >Barras</option>
                                    <option value="line">Linhas</option>
                                    <option value="radar">Radar</option>
                                </select>
                            </div>

                            <div class="col-sm-12 col-md-4 col-xl-personalizado align-items-center">
                                <label>Seleção Rápida</label><br>
                                <button type="button" class="btn btn-sm btn-primary" style="height: 30px; margin: 0 !important; justify-content: center;" onclick="selecionaDiaAtual()">Dia</button>
                                <button type="button" class="btn btn-sm btn-primary" style="height: 30px; margin: 0 !important; justify-content: center;" onclick="selecionaSemanaAtual()">Semana</button>
                                <button type="button" class="btn btn-sm btn-primary" style="height: 30px; margin: 0 !important; justify-content: center;" onclick="selecionaMesAtual()">Mês</button>

                            </div>

                        </div>
                    </div>
                </div>

                <div class="row my-12">
                    <div class="col-md-12 py-1">
                        <div class="card">
                            <div class="card-body" style="height: 500px">
                                <canvas id="grafico"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function () {
            $("#estacao_selecionada").multiselect({
                includeSelectAllOption: true,
                buttonWidth: '100%'
            });

            function aplicarFiltro() {
                var startDate = $("#dataInicial").val(); 
                var endDate = $("#dataFinal").val();     

                $.ajax({
                    type: "POST",
                    url: "{$BASE_URL}Leituras/index",
                    data: {
                        startDate: startDate, 
                        endDate: endDate     
                    },
                    success: function (data) {
                        console.log("Dados filtrados com sucesso");
                    },
                    error: function (error) {
                        console.error("Erro ao aplicar o filtro de datas: " + error);
                    }
                });
            }
        });
    </script>



    {literal}

        <script>

//////BOTÕES FACILITADORES DE SELECÃO DE DIA MES E SEMANA ////////
            function selecionaDiaAtual() {
                let dataAtual = new Date();
                let dataFinalAtual = ("0" + dataAtual.getDate()).slice(-2) + "/" + ("0" + (dataAtual.getMonth() + 1)).slice(-2) + "/" + dataAtual.getFullYear();
                $('#dataFinal').val(dataFinalAtual);

                let dataInicial = new Date();
                let dataInicialCorrigida = ("0" + dataInicial.getDate()).slice(-2) + "/" + ("0" + (dataInicial.getMonth() + 1)).slice(-2) + "/" + dataInicial.getFullYear();
                $('#dataInicial').val(dataInicialCorrigida);
                HandleAjax();
            }

            function selecionaMesAtual() {
                let dataAtual = new Date();
                let dataFinalAtual = ("0" + dataAtual.getDate()).slice(-2) + "/" + ("0" + (dataAtual.getMonth() + 1)).slice(-2) + "/" + dataAtual.getFullYear();
                $('#dataFinal').val(dataFinalAtual);

                let dataInicial = new Date();
                dataInicial.setDate(1);
                let dataInicialCorrigida = ("0" + dataInicial.getDate()).slice(-2) + "/" + ("0" + (dataInicial.getMonth() + 1)).slice(-2) + "/" + dataInicial.getFullYear();
                $('#dataInicial').val(dataInicialCorrigida);
                HandleAjax();
            }

            function selecionaSemanaAtual() { 
                let dataAtual = new Date();
                let dataFinalAtual = ("0" + dataAtual.getDate()).slice(-2) + "/" + ("0" + (dataAtual.getMonth() + 1)).slice(-2) + "/" + dataAtual.getFullYear();
                $('#dataFinal').val(dataFinalAtual);

                let dataInicial = new Date();
                let diaAtual = dataInicial.getDay();
                let inicioSemana = new Date(dataInicial);
                inicioSemana.setDate(dataInicial.getDate() - diaAtual)
                let dataInicialCorrigida = ("0" + inicioSemana.getDate()).slice(-2) + "/" + ("0" + (inicioSemana.getMonth() + 1)).slice(-2) + "/" + inicioSemana.getFullYear();
                $('#dataInicial').val(dataInicialCorrigida);
                HandleAjax();
            }


        
            const tiposInformacao = {
                "volume_chuva": "Volume de Chuva (mm³)",
                "temperatura": "Temperatura (°C)",
                "velocidade_vento": "Velocidade do Vento (km/h)",
                "umidade_ar": "Umidade do Ar (%)",
                "rajada_vento": "Rajada de Vento (km/h)",
                "pressao_atmosferica": "Pressao Atmosferica (atm)",
            };

            $(document).ready(function () {
                const selectElement = $("#tipo_informacao");

                $.each(tiposInformacao, function (value, label) {
                    selectElement.append($("<option>", {
                        value: value,
                        text: label
                    }));
                });
            });


            //Funções de tratativa de dados:
            function GetTipoDados() {
                let tipo_informacao = $("#tipo_informacao").val(); 
                if (tipo_informacao == "") {
                    let tipo_dados = "TIPO_VOLUME_CHUVA";
                    console.log("Dado de volume de chuva selecionado por padrão");
                    return tipo_dados; 
                } else {
                    let tipo_dados;
                        switch (tipo_informacao)
                        {
                            case 'temperatura':
                                tipo_dados = "TIPO_TEMPERATURA";
                                break;
                            case 'volume_chuva':
                                tipo_dados = "TIPO_VOLUME_CHUVA";
                                break;
                            case 'umidade_ar':
                                tipo_dados = "TIPO_UMIDADE_AR";
                                break;
                            case 'velocidade_vento':
                                tipo_dados = "TIPO_VELOCIDADE_VENTO";
                                break;
                            case 'rajada_vento':
                                tipo_dados = "TIPO_RAJADA_VENTO";
                                break;
                            case 'pressao_atmosferica':
                                tipo_dados = "TIPO_PRESSAO_ATMOSFERICA";
                                break;
                            default:
                                tipo_dados = "TIPO_VOLUME_CHUVA";
                                console.log(`Erro na seleção de dados. Selecionado padrão default "volume de chuvas"`);
                    }
                    return tipo_dados;
                }

            }


            function GetEstacao() { 
                let estacao = $("#estacao_selecionada").val();
                let estacao_infos = new Array(); 
                estacao_infos.push(estacao); 

                if (Object.keys(estacao).length > 0) {
                    var selectedOptions = [];
                    estacao.forEach(function (value) {
                        var selectedOption = $('#estacao_selecionada option[value="' + value + '"]'); 
                        var descricao = selectedOption.text(); 
                        selectedOptions.push(descricao);
                    });

                    let  resultado = [estacao, selectedOptions]  
                    return resultado;
                } else {
                    GerarGrafico(0, 0, "Nenhuma estação selecionada", 0);
                    throw new Error('Necessário informar a estação');
                    return null;
                }
            }

//função que organiza o padrão da data para ser compativel com o banco de dados
            function GetData() {
                let dataInicial = $("#dataInicial").val(); 
                let dataFinal = $("#dataFinal").val(); 

                let partesData = dataInicial.split('/'); 
                let dataInicialFormatada = partesData[2] + "-" + partesData[1] + "-" + partesData[0]; 

                let partesData2 = dataFinal.split('/'); 
                let dataFinalFormatada = partesData2[2] + "-" + partesData2[1] + "-" + partesData2[0];

                let datas = new Array(); 
                datas.push(dataInicialFormatada); 
                datas.push(dataFinalFormatada); 

                return datas;
            }


            function GetEscala() { 
                let escala_selecionada = $("#escala_selecionada").val(); 
                if (!escala_selecionada) {
                    throw new Error('Necessário informar a escala');
                    return;
                }

                let escala;
                if (escala_selecionada != "mes") {
                    escala = "ESCALA_" + escala_selecionada.toUpperCase();
                } else {
                    escala = "ESCALA_MES"; 
                }

                return escala;
            }


            function HandleAjax()
            {
                let escala;
                let tipo_dados;
                let datas = GetData();
                try {
                    estacao = GetEstacao();
                } catch (e) {
                    console.log(e.message);
                    return;
                }
                try {
                    escala = GetEscala();
                } catch (e) {
                    console.log(e.message)
                }
                try {
                    tipo_dados = GetTipoDados();
                } catch (e) {
                    console.log(e.message)
                }


                try {
                    $.ajax({
                        url: "Leituras/getEstatisticasLeiturasJson",
                        dataType: "json",
                        method: "POST",
                        data: {
                            estacao_selecionada: estacao[0], 
                            data_inicial: datas[0], 
                            data_final: datas[1], 
                            escala: escala,
                            tipo_dados: tipo_dados},

                        success: function (data) {

                            const periodos = Object.keys(data).map(function (key) {
                                return key;
                            });

                            const valores = Object.values(data).map(function (value) {
                                return value;
                            });

                            GerarGrafico(periodos, valores, OrganizarLabel(estacao[1]), escala);
                        },

                        error: function (req, status, error)
                        {
                            console.log("Ocorreu um erro no AJAX - " + error);
                        }
                    });
                } catch (error) {
                    console.log(error + " - HandleAjax")
                }

            }


//EVENTO DE CHANGE DAS TAGS (ACOPLADO A ESTAÇÃO, TIPO DE INFORMAÇÃO E ESCALA)
            $('.change_controller').change(function () {
                HandleAjax();
            });

//EVENTO DE CHANGE ACOPLADO À DATA, USANDO A CLASSE DATEPICKER E O EVENTO DATECHANGE - EVITA A DUPLICAÇÃO DO EVENTO NO MOUSEOVER DO CALENDARIO QUE OCORRE AO USAR O CHANGE PURO
            $('.datepicker').on('changeDate', function () {
                HandleAjax();
            });

            $('.datepicker').datepicker({
                format: 'dd/mm/yyyy',
                language: "pt-BR"});

            var myChart;

            function AjeitarLabels(labels, tipo_escala)
            {
                if (labels.length > 0)  
                {
                    let dataFormatada = [];
                    if (tipo_escala == "ESCALA_MES")
                    {
                        for (let i = 0; i < labels.length; i++)
                        {
                            let dateArray = labels[i].split("-"); 
                            let date = new Date(dateArray[0], parseInt(dateArray[1]) - 1); 
                            dataFormatada.push(date.toLocaleString('pt-BR', {month: 'short', year: 'numeric'}).replace(". de ", "/").toUpperCase());  
                                                }
                        return dataFormatada;

                    } else if (tipo_escala == "ESCALA_DIA")
                    {
                        for (let i = 0; i < labels.length; i++)
                        {
                            let dateArray = labels[i].split("-"); 
                            let date = new Date(dateArray[0], dateArray[1] - 1, dateArray[2]); 
                            dataFormatada.push(date.toLocaleString('pt-BR', {day: 'numeric', month: 'numeric', year: 'numeric'}));  
                        }

                        return dataFormatada;


                    } else if (tipo_escala == "ESCALA_HORA")
                    {
                        let result = [];
                        for (let i = 0; i < labels.length; i++)
                        {
                            let dateArray = labels[i].split(/[-\s:]/); 
                            let date = new Date(dateArray[0], dateArray[1] - 1, dateArray[2], dateArray[3]); 
                            dataFormatada.push(date.toLocaleString('pt-BR', {day: 'numeric', month: 'numeric', year: 'numeric', hour: '2-digit', minute: '2-digit'}).replace(", ", "-"));
                            result[i] = dataFormatada[i].split('-');
                        }
                        return result;
                    } else if (tipo_escala == "ESCALA_SEMANA")
                    {
                        for (let i = 0; i < labels.length; i++)
                        {
                            let dateArray = labels[i].split('-'); 
                            let date = new Date(dateArray[0], 0, (dateArray[1] * 7)); 

                            if (date.getDay() == 0)
                            {
                                date.setDate(date.getDate() - 6); 
                            } else if (date.getDay() > 1)
                            { 
                                while (date.getDay() > 1)
                                {
                                    date.setDate(date.getDate() - 1);
                                }
                            } else if (date.getDay() == 1)
                            {
                                date.setDate(date.getDate()); 
                            }

                            dataFormatada.push(date.toLocaleString('pt-BR', {day: 'numeric', month: 'numeric', year: 'numeric'}));  

                        }
                        return dataFormatada
                    }

                } else 
                {
                    return;
                }

            }

            function OrganizarLabel(descricao) { 
                if (descricao.length > 1)
                { 
                    const valoresEst = [];
                    for (let i = 0; i < descricao.length; i++)
                    {
                        const estacao = descricao[i];
                        const match = estacao.match(/est\d+/i);  

                        if (match) {
                            const valorEst = match[0];
                            valoresEst.push(valorEst);
                        }
                    }

                    return valoresEst; 
                } else
                {
                    return descricao; 
                }
            }

            function GerarGrafico(labels_value, data_value, descricao, tipo_escala)
            {
                if (myChart)
                {
                    myChart.destroy();
                }

                let labels_formatada = AjeitarLabels(labels_value, tipo_escala);
                var graficoObj = null;

                var ctx = document.getElementById("grafico").getContext("2d");
                var config = {
                    type: 'line',
                    data: {
                        labels: labels_formatada,
                        datasets: [{
                                label: [descricao.toString()],
                                data: data_value,
                                fill: false,
                                borderColor: "#8B9DC8",
                                backgroundColor: "#8B9DC8"
                            }, ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        },
                        plugins: {
                            title: {
                                display: true,
                                text: $('#tipo_informacao').val() != "" ? tiposInformacao[$('#tipo_informacao').val()] : "-"
                            }
                        }
                    }
                };

                myChart = new Chart(ctx, config);


                $('#tipoGrafico').on('change', function (event) {
                    alterarTipoGrafico($('#tipoGrafico').val())
                });

                function alterarTipoGrafico(newType) {
        
                    if (myChart) {
                        myChart.destroy();
                    }
                    var temp = jQuery.extend(true, {}, config);
                    temp.type = newType;
                    myChart = new Chart(ctx, temp);

                }

                alterarTipoGrafico($('#tipoGrafico').val());

            }
        </script>


    {/literal}

{/block}



