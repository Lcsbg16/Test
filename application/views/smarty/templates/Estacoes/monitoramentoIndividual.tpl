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
                <!-- INFORMAÇÕES DA ESTAÇÃO -->
                <h5 class="text-center"> Identificação da Estação: </h5> <h2 class="card-title text-center"  id="estacao_id"> </h2> 
                <h6 class="card-subtitle mb-2 text-muted text-center">A última leitura foi realizada em:  </h6>
                <h6 class="card-subtitle mb-2 text-muted text-center" id='leitura_label'>data/hora ultima leitura </h6>
                </div>
            </div>
        
            <!-- INFORMAÇÕES DE LEITURA -->
            <div class="card shadow" style="height: 100%;">
                <div class="card-body">
                  <div class="row">
                    <div class="col-xl-7">
                      <div class="row">
                        <div class="col-xl-6 col-lg-6 p-1">
                            <div class="card card-stats mb-4 mb-xl-4">
                                <div class="card-body" style="min-height: 100px !important;">
                                    <div class="row">
                                        <div class="col">
                                            <h5 class="card-title text-uppercase text-muted mb-0">Temperatura</h5>
                                            <span class="h2 font-weight-bold mb-0" id='card_temperatura'> </span><span class="h3 font-weight-bold mb-0"> ºC </span>
                                        </div>
                                        <div class="col-auto">
                                            <div class="icon icon-shape bg-gradient-red text-white rounded-circle shadow">
                                                <i class="fas fa-temperature-half" aria-hidden="true"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 p-1">
                            <div class="card card-stats mb-4 mb-xl-4">
                                <div class="card-body"  style="min-height: 100px !important;">
                                    <div class="row">
                                        <div class="col">
                                            <h5 class="card-title text-uppercase text-muted mb-0">Umidade do ar</h5>
                                            <span class="h2 font-weight-bold mb-0" id="card_umidade"></span> <span class="h3 font-weight-bold mb-0"> g/m³ </span>
                                        </div>
                                        <div class="col-auto">
                                            <div class="icon icon-shape bg-blue text-white rounded-circle shadow">
                                                <i class="fa-solid fa-droplet" aria-hidden="true"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 p-1">
                            <div class="card card-stats mb-4 mb-xl-4">
                                <div class="card-body" style="min-height: 100px !important;">
                                    <div class="row">
                                        <div class="col">
                                            <h5 class="card-title text-uppercase text-muted mb-0">Velocidade do Vento</h5>
                                            <span class="h2 font-weight-bold mb-0" id="card_vel_vento"></span> <span class="h3 font-weight-bold mb-0"> m/s </span>
                                        </div>
                                        <div class="col-auto">
                                            <div class="icon icon-shape bg-yellow text-white rounded-circle shadow">
                                                <i class="fas fa-wind" aria-hidden="true"></i>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 p-1">
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
                        <div class="col-xl-6 col-lg-6 p-1">
                            <div class="card card-stats mb-4 mb-xl-4">
                                <div class="card-body" style="min-height: 100px !important;">
                                    <div class="row">
                                        <div class="col">
                                            <h5 class="card-title text-uppercase text-muted mb-0">Status da Estação:</h5>
                                            {if $estacao.ativa==1}
                                                <span class="h2 font-weight-bold mb-0" id="status_estacao"> Ativa </span>
                                                {elseif $estacao.ativa==0}
                                                    <span class="h2 font-weight-bold mb-0" id="status_estacao"> Inativa </span>
                                                {else}
                                                    <span class="h2 font-weight-bold mb-0" id="status_estacao"> Erro </span>
                                            {/if}
                                        </div>
                                        <div class="col-auto">
                                            <div class="icon icon-shape bg-green text-white rounded-circle shadow">
                                                <i class="fas fa-fan" aria-hidden="true"></i>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                    </div>

                </div>
            </div>
            <div class="col-xl-5 mb-5">
                <div class="card shadow">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0">Eventos</h3>
                        </div>
                        <div class="col text-right">
                            <a href="http://localhost/telemetria-web/AdminEventos" class="btn btn-sm btn-primary">Ver todos</a>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <!-- Projects table -->
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">Data</th>
                                <th scope="col">Evento</th>
                            </tr>
                        </thead>
                        <tbody>
                                {assign "eventos" $eventos} <!-- EVENTOS DA ESTAÇÃO -->
                                {foreach $eventos as $evento}
                                   {if $evento.estacao_id === $estacao.id }
                                    <tr>
                                        <th scope="row">
                                            {$evento.datahora|date_format:'%d/%m/%Y %H:%M'}
                                        </th>
                                        <td>
                                            {if $evento.tipo_evento_id == 1}
                                                <i class="fas fa-arrow-down text-danger mr-3"></i>Offline
                                            {elseif $evento.tipo_evento_id == 2}
                                                <i class="fas fa-arrow-up text-success mr-3"></i>Online
                                            {else}
                                                Tipo de evento desconhecido
                                            {/if}
                                        </td>
                                    </tr>
                                        {/if}
                                {/foreach}
                            </tbody>
                    </table>
                </div>
            </div>
            </div>
    </div>

            
            <div class="row d-flex justify-content-center align-items-center">
            <div class="col-xl-3 col-lg-6">
                                <label>Escala</label>
                                <select class="form-control form-control-sm change_controller" id="escala_selecionada" >
                                    <option value="">Selecione</option>
                                    <option>Minuto</option>
                                    <option selected>Hora</option>
                                    <option>Dia</option>
                                    <option>Semana</option>
                                    <option >M&ecirc;s</option>
                                </select>
                            </div>
                <div class="col-xl-3 col-lg-6">
                    <label>Data Inicial</label>
                    <input type="text" class="form-control form-control-sm datetimepicker" value="{'-1 hour'|strtotime|date_format:'%d/%m/%Y %H:%M'}" id="dataInicial"> <!-- Deixei os values apesar de alterar direto no JS-->
                </div>
                <div class="col-xl-3 col-lg-6">
                    <label>Data Final</label>
                    <input type="text" class="form-control form-control-sm datetimepicker" value="{$smarty.now|date_format:'%d/%m/%Y %H:%M'}" id="dataFinal"/>
                </div>
                    <div class="col-xl-3 col-lg-6 align-items-center">
                        <label>Seleção Rápida:</label><br>
                    <button type="button" class="btn btn-sm btn-primary" style="height: 39px; margin: 0 !important; justify-content: center;">Dia</button>
                    <button type="button" class="btn btn-sm btn-primary" style="height: 39px; margin: 0 !important; justify-content: center;">Mês</button>
                    <button type="button" class="btn btn-sm btn-primary" style="height: 39px; margin: 0 !important; justify-content: center;">Ano</button>

                    </div>
            </div>
<br><br>

        <div class="row my-12" >
            <div class="col-md-12 col-lg-12 col-xl-6 py-1">
                        <canvas id="graficoTemp" style="min-height: 350px;"></canvas>
            </div>
            <div class="col-md-12 col-lg-12 col-xl-6 py-1">
                        <canvas id="graficoUmid" style="min-height: 350px; max-width: 100%"></canvas>
            </div>

        </div>

    <div class="row my-12">
        <div class="col-md-12 col-lg-12 col-xl-6 py-1">
                    <canvas id="graficoVelVento" style="min-height: 350px;"></canvas>
        </div>

        <div class="col-md-12 col-lg-12 col-xl-6 py-1">
                    <canvas id="graficoVolChuva" style="min-height: 350px;"></canvas>
        </div>

    </div>

    <div class="row my-12" >
        
    </div>

            </div>
        </div>
    </div>
    
</div>
                

</div>
<script>
 function GetEscala(){ //função que devolve os dados da escala 
            let escala_selecionada = $( "#escala_selecionada" ).val(); //Valor da escala 
            if(!escala_selecionada){
                throw new Error('Necessário informar a escala'); 
                return;
            }

            let escala; 
                if (escala_selecionada!="Mês") { 
                    escala = "ESCALA_"+ escala_selecionada.toUpperCase();
                }
                else {
                    escala = "ESCALA_MES"; //Mês tem acento, tem que trocar 
                }

            return escala; }


    var charts = {};

    function geraGrafico(canva, label_sup, label_inf, dados,  tipo_escala)
     {
        
     if (charts[canva.id]) {
        charts[canva.id].destroy();
        delete charts[canva.id]; // Remover a referência do gráfico
    }
        let labels_formatada = AjeitarLabels(label_inf, tipo_escala);
         
        var config = 
         {
            type: 'line',
            data: {
                    labels:  labels_formatada,
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
                        x: { beginAtZero: true, offset: true }
                        }
                    }
            };

        charts[canva.id] = new Chart(canva, config);

    }
  
        function handleAjaxGraficos(estacao_id, datas, escala, tipo_dados)
        { 
            let graficoTemp = document.getElementById("graficoTemp");
            let graficoUmid = document.getElementById("graficoUmid");
            let graficoVelVento = document.getElementById("graficoVelVento");
            let graficoDirVento = document.getElementById("graficoDirVento");
            let graficoVolChuva = document.getElementById("graficoVolChuva");

                    console.log(tipo_dados);
                    $.ajax({
                        url: "{$BASE_URL}/AdminLeituras/getEstatisticasLeiturasJson",
                        dataType: "json",
                        method: "POST",
                        data: {
                                estacao_selecionada: estacao_id,  //Estação - ID
                                data_inicial: datas[0], //data inicial formatada
                                data_final: datas[1], //data final formatada
                                escala: escala, 
                                tipo_dados: tipo_dados
                             },
                         }).done(function (data)
                         {     
                                if(data.length == 0)
                                {
                                    let graficosArray = [graficoTemp, graficoUmid, graficoVelVento, graficoVolChuva];
                                   graficosArray.forEach(grafico => geraGrafico(grafico, "Dados indisponíveis nesse período", "errro", "0"));

                             } 
                             else 
                            {
                                //salva as keys/chaves como os periodos de tempo (no model: o dado vem como 2023=>20.55, ou seja, key = periodo 
                                const periodos = Object.keys(data).map(function(key) { return key;  });
                                //salva os value como valor (no model: 2023=>20.5555, ou seja, value = valor)
                                const valores = Object.values(data).map(function(value) { return value;});
                                let tipo_informação;
                                
                                switch (tipo_dados) 
                                { 
                                    case 'TIPO_TEMPERATURA':
                                    tipo_informação = "Temperatura";
                                    geraGrafico(graficoTemp, tipo_informação, periodos, valores, escala); //passa: canva de temperatura, Temperatura como label superior, periodos como label inferior e valores como dados finais
                                    break; 

                                    case 'TIPO_UMIDADE_AR':    
                                    tipo_informação = "Umidade";
                                    geraGrafico(graficoUmid, tipo_informação, periodos, valores, escala); //passa: canva de temperatura, Temperatura como label superior, periodos como label inferior e valores como dados finais
                                    break;

                                    case 'TIPO_VELOCIDADE_VENTO':
                                    tipo_informação = "Velocidade do Vento";
                                    geraGrafico(graficoVelVento, tipo_informação, periodos, valores, escala);
                                    break;


                                    case 'TIPO_VOLUME_CHUVA':
                                    tipo_informação = "Volume de Chuva";
                                    geraGrafico(graficoVolChuva, tipo_informação, periodos, valores, escala);

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


        function handleAjax(card_id, estacao_id, tipo_dados)
    { console.log("tipo de dados: " + tipo_dados);
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
                    console.log(data);
                    if(data.length == 0)
                    {
                        $(card_id).text("Erro leitura");
                        $('#leitura_label').text('Não foram localizados dados para essa estação:');
                        console.log("Nãp há dados registrados para essa estação");
                        return;
                    }  
                    else {
                            $(card_id).text(parseFloat(data[0].valor).toFixed(2));
                             let [dataOriginal, hora] = data[0].datahora.split(' ');
                            let [ano, mes, dia] = dataOriginal.split('-');
                           $('#leitura_label').text(dia + "/" + mes + "/" + ano + " - " + hora );
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

    
    function AjeitarLabels(labels, tipo_escala)
    {
        if(labels.length>0)  //se tiver labels pra tratar //Essa labels é a inferior, onde indica o tipo de dado. Ex: mes (03-2023), hora, dia, etc
            {        
                let dataFormatada = [];
                if (tipo_escala == "ESCALA_MES") 
                    {
                        for (let i=0; i<labels.length; i++)
                            {
                                let dateArray = labels[i].split("-"); //quebra a data que chega no formato yyyy-mm
                                let date = new Date(dateArray[0], parseInt(dateArray[1]) - 1); // Cria uma varivel com o ano e o mês (Janeiro = 0)
                                dataFormatada.push(date.toLocaleString('pt-BR', { month: 'short', year: 'numeric' }).replace(". de ", "/").toLowerCase());  // Formata a data para exibir o mês por extenso e o ano numerico
                            }   
                        return dataFormatada;

                    } else if (tipo_escala == "ESCALA_MINUTO") 
                        {
                            let result = [];
                            for (let i=0; i<labels.length; i++)
                                {
                                    let dateArray = labels[i].split(/[-\s:]/); //quebra a data que chega no formato yyyy-mm
                                    let date = new Date(dateArray[0], dateArray[1] - 1, dateArray[2], dateArray[3], dateArray[4]); // Cria uma varivel com o ano e o mês (Janeiro = 0)
                                    dataFormatada.push(date.toLocaleString('pt-BR', { day: 'numeric', month: 'numeric', year: 'numeric', hour: '2-digit', minute: '2-digit' }).replace(", ", "-")); 
                                    result[i]= dataFormatada[i].split('-');

                                }    
                        
                            return result;

                     } else if(tipo_escala == "ESCALA_HORA")
                            {
                                let result = [];
                                for (let i=0; i<labels.length; i++)
                                    {
                                        let dateArray = labels[i].split(/[-\s:]/); //quebra a data que chega no formato yyyy-mm
                                        let date = new Date(dateArray[0], dateArray[1] - 1, dateArray[2], dateArray[3]); // Cria uma varivel com o ano e o mês (Janeiro = 0)
                                        dataFormatada.push(date.toLocaleString('pt-BR', { day: 'numeric', month: 'numeric', year: 'numeric', hour: '2-digit', minute: '2-digit' }).replace(", ", "-")); 
                                        result[i]= dataFormatada[i].split('-');
                                    }    
                                return result;
                            }  else if(tipo_escala == "ESCALA_SEMANA")
                                {
                                    for (let i=0; i<labels.length; i++)
                                            {
                                                let dateArray = labels[i].split('-'); //quebra a data que chega no formato yyyy-semana
                                                let date = new Date(dateArray[0], 0, (dateArray[1] *7)); // qnt dias
                                                
                                                if(date.getDay() == 0)
                                                    {
                                                        date.setDate(date.getDate() - 6); //se o dia cair no domingo, tirar 6 dias pra chegar na segunda
                                                    } else if (date.getDay() > 1)
                                                    { //se o dia cair entre dia 2 e 5, diminuir até o dia 1    
                                                        while (date.getDay() > 1)
                                                        {
                                                            date.setDate(date.getDate() - 1);
                                                        }
                                                    } else if (date.getDay() == 1) 
                                                    {
                                                        date.setDate(date.getDate()); //se o dia cair na segunda, manter
                                                    }
                                            
                                                dataFormatada.push(date.toLocaleString('pt-BR', { day: 'numeric', month: 'numeric', year: 'numeric' }));  // Formata a data para exibir o mês por extenso e o ano numerico
                                            
                                            }
                                    return dataFormatada   
                                } else if (tipo_escala == "ESCALA_DIA") 
                                    {
                                        for (let i=0; i<labels.length; i++)
                                            {
                                                let dateArray = labels[i].split("-"); //quebra a data que chega no formato yyyy-mm
                                                let date = new Date(dateArray[0], dateArray[1] - 1, dateArray[2]); // Cria uma varivel com o ano e o mês (Janeiro = 0)
                                                dataFormatada.push(date.toLocaleString('pt-BR', { day: 'numeric', month: 'numeric', year: 'numeric' }));  // Formata a data para exibir o mês por extenso e o ano numerico
                                            }    
                                    
                                        return dataFormatada;
                                    } 
                    
            } 
        else //se o valor de labels vier vazio
           return;
            
}   

    ///////CARREGAR OS GRÁFICOS /////////////
    function carregarGraficoPorTipo(tipo_dados, estacao) 
                {
                    let escala = GetEscala();
                    let datas = GetData();

                    handleAjaxGraficos(estacao, datas, escala, tipo_dados);
                }

    function configureDateTimePicker(escala) 
    {
            let valorAnteriorData = null;

            let padraoConfig = {
                format: 'd/m/Y H:i',
                step: 1,
                maxDate: '0',
                theme: 'default',
                onClose: function(dp, $input) {
                    let novoValorData = $input.val();
                    if (novoValorData !== valorAnteriorData) {
                        console.log("é diferente");

                        ["TIPO_TEMPERATURA", "TIPO_UMIDADE_AR", "TIPO_VELOCIDADE_VENTO", "TIPO_VOLUME_CHUVA"].forEach(function (tipo) {
                            carregarGraficoPorTipo(tipo, {$estacao.id});
                        });

                        valorAnteriorData = novoValorData;
                        return;
                    }
                    else {
                        return;
                    }

                }
            }; //configuração padrão 

            if (escala === "ESCALA_MINUTO") {
                let formattedLastHour = new Date(Date.now() - 60 * 60 * 1000).toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' }).toString();
                organizaLabelDataHora();

                let minutoConfig = {
                    ...padraoConfig,
                    minTime: formattedLastHour,
                    minDate: '0', //Não deixa selecionar datas antes do dia atual
                    maxTime: 0,
                    minDate: '0', //Não deixa selecionar datas antes do dia atual
                    datepicker:false
                };
                $('.datetimepicker').datetimepicker('destroy');
                $('.datetimepicker').datetimepicker(minutoConfig);
            } 
            else {
                let outraEscalaConfig = {
                    ...padraoConfig
                };
                $('.datetimepicker').datetimepicker('destroy');
                $('.datetimepicker').datetimepicker(outraEscalaConfig);
            }
    }


     function organizaLabelDataHora(){
        let dataAtual = new Date();
        let dataHoraAtual = ("0" + dataAtual.getDate()).slice(-2) + "/" + ("0" + (dataAtual.getMonth() + 1)).slice(-2) + "/" + dataAtual.getFullYear() + " " + ("0" + dataAtual.getHours()).slice(-2) + ":" + ("0" + dataAtual.getMinutes()).slice(-2);
        $('#dataFinal').val(dataHoraAtual);

        let dataInicial = new Date();
        dataInicial.setHours(dataAtual.getHours() - 24) //dataInicial definida como mesmo dia, uma hora antes da hora atual
        let dataHoraInicial = ("0" + dataInicial.getDate()).slice(-2) + "/" + ("0" + (dataInicial.getMonth() + 1)).slice(-2) + "/" + dataInicial.getFullYear() + " " + ("0" + dataInicial.getHours()).slice(-2) + ":" + ("0" + dataInicial.getMinutes()).slice(-2);
        $('#dataInicial').val(dataHoraInicial);

     }

     $('.change_controller').change(function()  { //mudanças da escala
           
                    let novaEscala = GetEscala();
                    configureDateTimePicker(novaEscala); //altera o tipo de calendario, se a escala for "minuto" há algumas alterações em relação as outras escalas
                    ["TIPO_TEMPERATURA", "TIPO_UMIDADE_AR", "TIPO_VELOCIDADE_VENTO", "TIPO_VOLUME_CHUVA", "TIPO_VOLUME_ACC_CHUVA"].forEach(function (tipo) {
                    carregarGraficoPorTipo(tipo, {$estacao.id}); }); //carrega os gráficos 

                        
        });


  $(function () {

         configureDateTimePicker(GetEscala()); //Configura o calendário com a escala inicial "mes"

         // coloca as informações dos cards
         document.getElementById("estacao_id").innerHTML = "{$estacao.descricao} ({$estacao.identificador})";


        /////Organização de data e tempo atual no placeholder de datainicial e datafinal: bug - data estava certa, horário errado em relação ao atual //////
        organizaLabelDataHora()
      
            //COLOCA OS DADOS NOS CARDS 
            const parametrosHandleAjax = [
            ["#card_temperatura", {$estacao.id}, "TIPO_TEMPERATURA"],
            ["#card_umidade", {$estacao.id}, "TIPO_UMIDADE_AR"],
            ["#card_vel_vento",{$estacao.id}, "TIPO_VELOCIDADE_VENTO"],
            ["#card_vol_chuva", {$estacao.id}, "TIPO_VOLUME_CHUVA"]
            ];

            parametrosHandleAjax.forEach(parametro => {
            handleAjax(...parametro);
            });

            setInterval(() => { //Mantém os cards de ultima leitura com os valores att 
            parametrosHandleAjax.forEach(parametro => {
                handleAjax(...parametro);
            });
            }, 10000); 

        //ATUALIZA O GRÁFICO COM OS VALORES PADRÃO NO LOAD DA PAGE
        ["TIPO_TEMPERATURA", "TIPO_UMIDADE_AR", "TIPO_VELOCIDADE_VENTO", "TIPO_VOLUME_CHUVA"].forEach(function (tipo) {
            carregarGraficoPorTipo(tipo, {$estacao.id}); });

  });


</script>


{/block}

