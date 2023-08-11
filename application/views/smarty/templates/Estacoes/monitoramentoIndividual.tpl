{extends file = 'app_logado.tpl'}
{block name = "conteudo_logado"}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js" integrity="sha512-ElRFoEQdI5Ht6kZvyzXhYG9NqjtkmlkfYk0wr6wHxU9JEHakS7UJZNeml5ALk+8IKlU6jDgMabC3vkumRokgJA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<!-- Incluir o plugin de datepicker pra alterar o tempo também -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js"></script>

    <div class="container-fluid mt-3">
    <div class="row">
        <div class="col">
            <div class="card shadow">
                <div class="card-body">
            

                <h5 class="text-center"> Identificação da Estação: </h5> <h2 class="card-title text-center"  id="estacao_id"> </h2> 
                <h6 class="card-subtitle mb-2 text-muted text-center">A última leitura foi feita em:  </h6>
                <h6 class="card-subtitle mb-2 text-muted text-center" id='leitura_label'>data/hora ultima leitura </h6>
                </div>
            </div>
        
            <div class="card shadow" style="height: 100%;">
                <div class="card-body">
                <div class="row">
                    

                <div class="col-xl-4 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-4">
                        <div class="card-body" style="min-height: 100px !important;">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">Temperatura</h5>
                                    <span class="h2 font-weight-bold mb-0" id='card_temperatura'> </span><span class="h3 font-weight-bold mb-0"> ºC </span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-yellow text-white rounded-circle shadow">
                                        <i class="fas fa-temperature-half" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-4">
                        <div class="card-body"  style="min-height: 100px !important;">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">Umidade do ar</h5>

                                    <span class="h2 font-weight-bold mb-0" id="card_umidade"></span> <span class="h3 font-weight-bold mb-0"> g/m³ </span>

                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-blue text-white rounded-circle shadow">
                                        <i class="fas fa-temperature-empty" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-4">
                        <div class="card-body" style="min-height: 100px !important;">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">Velocidade do Vento</h5>

                                    <span class="h2 font-weight-bold mb-0" id="card_vel_vento"></span> <span class="h3 font-weight-bold mb-0"> km/h </span>

                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-gradient-red text-white rounded-circle shadow">
                                        <i class="fas fa-temperature-full" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-4">
                        <div class="card-body" style="min-height: 100px !important;">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">Direção do Vento</h5>
                                    <span class="h2 font-weight-bold mb-0" id="card_dir_vento"></span> <span class="h3 font-weight-bold mb-0"> g/m³ </span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-gradient-blue text-white rounded-circle shadow">
                                        <i class="fas fa-cloud-rain" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-4">
                        <div class="card-body" style="min-height: 100px !important;">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">Volume de Chuva</h5>
                                    <span class="h2 font-weight-bold mb-0" id="card_vol_chuva"> </span> <span class="h3 font-weight-bold mb-0"> mm </span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-gradient-gray text-white rounded-circle shadow">
                                        <i class="fas fa-cloud-rain" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-4">
                        <div class="card-body" style="min-height: 100px !important;">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">Volume Acumulado de Chuva</h5>
                                    <span class="h2 font-weight-bold mb-0" id="card_vol_acc_chuva"></span> <span class="h3 font-weight-bold mb-0"> mm </span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-green text-white rounded-circle shadow">
                                        <i class="fas fa-wind" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
        </div>
            <div class="row d-flex justify-content-center">
                <div class="col-xl-4 col-lg-6">
                    <label>Data Inicial</label>
                    <input type="text" class="form-control form-control-sm datetimepicker" value="{'-1 hour'|strtotime|date_format:'%d/%m/%Y %H:%M'}" id="dataInicial"> <!-- Deixei os values apesar de alterar direto no JS-->
                </div>
                <div class="col-xl-4 col-lg-6">
                    <label>Data Final</label>
                    <input type="text" class="form-control form-control-sm datetimepicker" value="{$smarty.now|date_format:'%d/%m/%Y %H:%M'}" id="dataFinal"/>
                    </div>
            </div>
<br><br>

        <div class="row my-12" >
            <div class="col-md-12 col-lg-12 col-xl-6 py-1">
                        <canvas id="graficoTemp" style="min-height: 350px;"></canvas>
            </div>
            <div class="col-md-12 col-lg-12 col-xl-6 py-1">
                        <canvas id="graficoUmid" style="min-height: 350px;"></canvas>
            </div>

        </div>

    <div class="row my-12">
        <div class="col-md-12 col-lg-12 col-xl-6 py-1">
                    <canvas id="graficoVelVento" style="min-height: 350px;"></canvas>
        </div>

        <div class="col-md-12 col-lg-12 col-xl-6 py-1">
                    <canvas id="graficoDirVento" style="min-height: 350px;"></canvas>
        </div>

    </div>

    <div class="row my-12" >
        <div class="col-md-12 col-lg-12 col-xl-6 py-1">
                    <canvas id="graficoVolChuva" style="min-height: 350px;"></canvas>
        </div>
        <div class="col-md-12 col-lg-12 col-xl-6 py-1" >
                    <canvas id="graficoVolChuvaAcc" ></canvas>
        </div>
    </div>

            </div>
        </div>
    </div>
    
</div>
                

</div>
<script>
    function ajeitaLabelInf(labels) //organiza a label inferior para data ficar em cima e a hora embaixo
    { 
        let result = [];
        let dataFormatada = [];

        for (let i=0; i<labels.length; i++)
            {
                let dateArray = labels[i].split(/[-\s:]/); //quebra a data que chega no formato yyyy-mm
                let date = new Date(dateArray[0], dateArray[1] - 1, dateArray[2], dateArray[3], dateArray[4]); // Cria uma varivel com o ano e o mês (Janeiro = 0)
                dataFormatada.push(date.toLocaleString('pt-BR', { day: 'numeric', month: 'numeric', year: 'numeric', hour: '2-digit', minute: '2-digit' }).replace(", ", "-")); 
                result[i]= dataFormatada[i].split('-');
            }    
        return result;
    }

    var charts = {};

    function geraGrafico(canva, label_sup,label_inf, dados )
     {
        if (charts[canva.id])
         { charts[canva.id].destroy();  }
         
        var config = 
         {
            type: 'line',
            data: {
                    labels: label_inf,
                    datasets: [{
                                label: label_sup,
                                data: dados,
                                fill: false,
                                borderColor: "#8B9DC8",
                                backgroundColor: "#8B9DC8"
                                },]
                    },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                        y: { beginAtZero: true },
                        x: { beginAtZero: true }
                        }
                    }
            };

        charts[canva.id] = new Chart(canva, config);

    }
  
        function HandleAjaxGraficos(estacao, datas, escala, tipo_dados)
        { 
                    $.ajax({
                        url: "{$BASE_URL}/AdminLeituras/getEstatisticasLeiturasJson",
                        dataType: "json",
                        method: "POST",
                        data: {
                                estacao_selecionada: estacao,  //Estação - ID
                                data_inicial: datas[0], //data inicial formatada
                                data_final: datas[1], //data final formatada
                                escala: escala, //Nesse caso, sempre MINUTO
                                tipo_dados: tipo_dados
                             },
                         }).done(function (data)
                         {     
                                if(data.length == 0)
                                {
                                    switch (tipo_dados) 
                                        { 
                                            case 'TIPO_TEMPERATURA':
                                            let graficoTemp = document.getElementById("graficoTemp");
                                            geraGrafico(graficoTemp, "Temperatura", "ERRO", 0); //passa: canva de temperatura, Temperatura como label superior, periodos como label inferior e valores como dados finais
                                            console.log("Dados de temperatura vazios. Não foi possível criar o grafico de temperatura");
                                            break; 

                                            case 'TIPO_UMIDADE_AR':    
                                            let graficoUmid = document.getElementById("graficoUmid");
                                            geraGrafico(graficoUmid, "Umidade", "ERRO", 0); //passa: canva de temperatura, Temperatura como label superior, periodos como label inferior e valores como dados finais
                                            console.log("Dados de umidade vazios. Não foi possível criar o grafico de umidade");
                                            break;

                                            case 'TIPO_VELOCIDADE_VENTO':
                                            let graficoVelVento = document.getElementById("graficoVelVento");
                                            geraGrafico(graficoVelVento, "Velocidade do Vento", "ERRO", 0);
                                            console.log("Dados de velocidade do vento vazios. Não foi possível criar o grafico de velocidade do vento");
                                            break;

                                            case 'TIPO_DIRECAO_VENTO':
                                            let graficoDirVento = document.getElementById("graficoDirVento");
                                            geraGrafico(graficoDirVento, "Direção do Vento", "ERRO", 0);
                                            console.log("Dados de direção do vento vazios. Não foi possível criar o grafico de direção do vento");
                                            break;

                                            case 'TIPO_VOLUME_CHUVA':
                                            let graficoVolChuva = document.getElementById("graficoVolChuva");
                                            geraGrafico(graficoVolChuva, "Volume de Chuva", "ERRO", 0);
                                            console.log("Dados de volume de chuva vazios. Não foi possível criar o grafico de volume de chuva");
                                            break;

                                            case 'TIPO_VOLUME_ACC_CHUVA':
                                            let graficoVolChuvaAcc = document.getElementById("graficoVolChuvaAcc");
                                            geraGrafico(graficoVolChuvaAcc, "Chuva Acumulada","ERRO", 0);
                                            console.log("Dados de volume acumulado de chuva vazios. Não foi possível criar o grafico de volume acumulado de chuvas");
                                            break;
                                            default:
                                        throw new Exception('Tipo de dado não definido');

                                        }  
                                        
                             } 
                             else 
                            {
                                //salva as keys/chaves como os periodos de tempo (no model: o dado vem como 2023=>20.55, ou seja, key = periodo 
                                const periodos = Object.keys(data).map(function(key) { return key;  });
                                //salva os value como valor (no model: 2023=>20.5555, ou seja, value = valor)
                                const valores = Object.values(data).map(function(value) { return value;});
                                
                                switch (tipo_dados) 
                                { 
                                    case 'TIPO_TEMPERATURA':
                                    let graficoTemp = document.getElementById("graficoTemp");
                                    geraGrafico(graficoTemp, "Temperatura", ajeitaLabelInf(periodos), valores); //passa: canva de temperatura, Temperatura como label superior, periodos como label inferior e valores como dados finais
                                    break; 

                                    case 'TIPO_UMIDADE_AR':    
                                    let graficoUmid = document.getElementById("graficoUmid");
                                    geraGrafico(graficoUmid, "Umidade", ajeitaLabelInf(periodos), valores); //passa: canva de temperatura, Temperatura como label superior, periodos como label inferior e valores como dados finais
                                    break;

                                    case 'TIPO_VELOCIDADE_VENTO':
                                    let graficoVelVento = document.getElementById("graficoVelVento");
                                    geraGrafico(graficoVelVento, "Velocidade do Vento", ajeitaLabelInf(periodos), valores);
                                    break;

                                    case 'TIPO_DIRECAO_VENTO':
                                    let graficoDirVento = document.getElementById("graficoDirVento");
                                    geraGrafico(graficoDirVento, "Direção do Vento", ajeitaLabelInf(periodos), valores);
                                    break;

                                    case 'TIPO_VOLUME_CHUVA':
                                    let graficoVolChuva = document.getElementById("graficoVolChuva");
                                    geraGrafico(graficoVolChuva, "Volume de Chuva", ajeitaLabelInf(periodos), valores);
                                    break;

                                    case 'TIPO_VOLUME_ACC_CHUVA':
                                    let graficoVolChuvaAcc = document.getElementById("graficoVolChuvaAcc");
                                    geraGrafico(graficoVolChuvaAcc, "Chuva Acumulada", ajeitaLabelInf(periodos), valores);
                                    break;

                                    default:
                                    throw new Exception('Tipo não especificado - Ajax, MonitoramentoIndividual');

                                }     
                            
                            }
                   }).fail(function (jqXHR, textStatus, errorThrown) {
                    // Tratamento de erro
                    $(card_id).text("Erro leitura");
                    console.log("Ocorreu um erro - " + errorThrown);
                });
    }


        function HandleAjax(card_id, estacao_id, tipo_dados)
    { 
                 $.ajax({
                        url: "{$BASE_URL}/AdminLeituras/getUltimaLeituraRegistrada",
                        dataType: "json",
                        method: "POST",
                        data: {
                            estacao_selecionada: estacao_id,
                            tipo_dados: tipo_dados
                     },
                }).done(function (data)
                 {
                    if(data.length == 0)
                    {
                        $(card_id).text("Erro leitura");
                        console.log("Ocorreu um erro no recebimento dos dados");
                        return;
                    }  
                    else {
                            $(card_id).text(parseFloat(data[0].valor).toFixed(2));
                            $('#leitura_label').text(data[0].datahora);
                        }
                }).fail(function (jqXHR, textStatus, errorThrown) 
                    { // Tratamento de erro
                    $(card_id).text("Erro leitura");
                    console.log("Ocorreu um erro - " + errorThrown);
                     });
    }


        function GetData() //organzia a data no padrão do Bd
    {  
        let dataInicial = $( "#dataInicial").val(); //Guarda esse valor numa variavel
        let dataFinal = $( "#dataFinal" ).val(); //Guarda esse valor numa variavel

        let partesDataHora = dataInicial.replace(/\//g, ' ').split(' '); // dividindo a data em partes (dia, mês e ano e hora)

        let dataInicialFormatada = partesDataHora[2] + "-" + partesDataHora[1] + "-" + partesDataHora[0] + " " + partesDataHora[3]; //Organizando a data para o formato YYYY-MM-DD

        let partesDataHoraFinal = dataFinal.replace(/\//g, ' ').split(' '); // dividindo a data em partes (dia, mês e ano e hora)
        let dataFinalFormatada = partesDataHoraFinal[2] + "-" + partesDataHoraFinal[1] + "-" + partesDataHoraFinal[0] + " " + partesDataHoraFinal[3]; 

        let datas = new Array(); //data em array
        datas.push(dataInicialFormatada); //data[0] = data inicial
        datas.push(dataFinalFormatada); //data [1] = data final 

        return datas; 
    }

    

  $(function () {

        ////////////PEGAR O ID DA ESTAÇÃO VIA URL: //////////////
        var url = window.location.href;
        var partesUrl = url.split('/');
        var idEstacao = partesUrl[partesUrl.length - 1]; //O id é o ultimo elemento do split //

        {foreach $estacoes as $eAtual}
        if ({$eAtual.id} == idEstacao) {
            document.getElementById('estacao_id').innerHTML = "{$eAtual.descricao} ({$eAtual.identificador})";
        }
        {/foreach}

        ///////CARREGAR OS GRÁFICOS /////////////
        function carregarGraficoPorTipo(tipo_dados, estacao) 
                {
                    let datas = GetData();
                    HandleAjaxGraficos(estacao, datas, 'ESCALA_MINUTO', tipo_dados);
                }


        /////Organização de data e tempo atual: bug - data estava certa, horário errado em relação ao atual //////
        let dataAtual = new Date();
        let dataHoraAtualFinal = ("0" + dataAtual.getDate()).slice(-2) + "/" + ("0" + (dataAtual.getMonth() + 1)).slice(-2) + "/" + dataAtual.getFullYear() + " " + ("0" + dataAtual.getHours()).slice(-2) + ":" + ("0" + dataAtual.getMinutes()).slice(-2);
        $('#dataFinal').val(dataHoraAtualFinal);

        let dataInicial = new Date();
        dataInicial.setHours(dataAtual.getHours() - 1) //dataInicial definida como mesmo dia, uma hora antes da hora atual
        let dataHoraInicial = ("0" + dataInicial.getDate()).slice(-2) + "/" + ("0" + (dataInicial.getMonth() + 1)).slice(-2) + "/" + dataInicial.getFullYear() + " " + ("0" + dataInicial.getHours()).slice(-2) + ":" + ("0" + dataInicial.getMinutes()).slice(-2);
        $('#dataInicial').val(dataHoraInicial);

        
        ////O datetimepicker tem um bug de acionamento multiplo, tento corrigir o bug com comparação do valor das datas /////
         let valorAnteriorData = null; 

        $('.datetimepicker').datetimepicker({
                format: 'd/m/Y H:i', // Define o formato da data e hora
                step: 1, // Define o intervalo de minutos para seleção (1 em 1 minutos)
                closeOnWithoutClick: false,
                maxDate: '0', //Não deixa selecionar datas a partir do dia atual
                lazyInit: true,
                onClose:function(dp,$input) //NO CLOSE DO CALENDÁRIO, ATUALIZA AS INFORMAÇÕES DOS GRÁFICOS
                {       let novoValorData = $input.val().split(' ')[0]; //comparação do valor da data selecionada com a data anterior

                        if (novoValorData !== valorAnteriorData)   // Verifica se a data foi alterada
                        { //se for diferente, atualiza o gráfico
                            ["TIPO_TEMPERATURA", "TIPO_UMIDADE_AR", "TIPO_VELOCIDADE_VENTO", "TIPO_DIRECAO_VENTO", "TIPO_VOLUME_CHUVA", "TIPO_VOLUME_ACC_CHUVA"].forEach(function (tipo) {
                            carregarGraficoPorTipo(tipo, idEstacao); });
                               
                            valorAnteriorData = novoValorData;  // Atualiza o valor anterior da data com o novo valor
                        }
                }
            })


                    //COLOCA OS DADOS NOS CARDS 
            const parametrosHandleAjax = [
            ["#card_temperatura", idEstacao, "TIPO_TEMPERATURA"],
            ["#card_umidade", idEstacao, "TIPO_UMIDADE_AR"],
            ["#card_vel_vento", idEstacao, "TIPO_VELOCIDADE_VENTO"],
            ["#card_dir_vento", idEstacao, "TIPO_DIRECAO_VENTO"],
            ["#card_vol_chuva", idEstacao, "TIPO_VOLUME_CHUVA"],
            ["#card_vol_acc_chuva", idEstacao, "TIPO_VOLUME_ACC_CHUVA"]
            ];

            parametrosHandleAjax.forEach(parametro => {
            HandleAjax(...parametro);
            });

            setInterval(() => { //Mantém os cards com os valores att a cada 2 seg **
            parametrosHandleAjax.forEach(parametro => {
                HandleAjax(...parametro);
            });
            }, 5000); //5seg

        //ATUALIZA O GRÁFICO COM OS VALORES PADRÃO NO LOAD DA PAGE
        ["TIPO_TEMPERATURA", "TIPO_UMIDADE_AR", "TIPO_VELOCIDADE_VENTO", "TIPO_DIRECAO_VENTO", "TIPO_VOLUME_CHUVA", "TIPO_VOLUME_ACC_CHUVA"].forEach(function (tipo) {
            carregarGraficoPorTipo(tipo, idEstacao); });


  });


</script>


{/block}
