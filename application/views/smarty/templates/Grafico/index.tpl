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
    </style>
    
    <div class="container-fluid mt-3">
        <div class="row">
            <div class="col">
                <div class="card shadow">
                    <div class="card-body">
                        <div class="row my-3">
                            <div class="col-sm-12 col-md-4 col-xl-2">
                                <label>Esta&ccedil;&atilde;o</label>
                                <select class="form-control form-control-sm change_controler" id="estacao_selecionada" multiple> <!-- Id indica qual estação foi selecionada --> 
                                    {foreach $estacoes as $eAtual}
                                        {if $eAtual.ativa==1}
                                            <option value="{$eAtual.id}" id="estacao_descricao"> {$eAtual.descricao} ({$eAtual.identificador})</option>
                                        {/if}
                                    {/foreach}
                                  
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-4 col-xl-2">
                                <label>Data Inicial</label>
                                <input class="form-control form-control-sm datepicker" value="{'-3 months'|strtotime|date_format:'%d/%m/%Y'}" id="dataInicial">
                            </div>
                            <div class="col-sm-12 col-md-4 col-xl-2">
                                <label>Data Final</label>
                                <input class="form-control form-control-sm datepicker" value='{$smarty.now|date_format:'%d/%m/%Y'}' id="dataFinal">
                            </div>
                            <div class="col-sm-12 col-md-4 col-xl-2">
                                <label>Escala</label>
                                <select class="form-control form-control-sm change_controler" id="escala_selecionada" >
                                    <option value="">Selecione</option>
                                    <option>Hora</option>
                                    <option>Dia</option>
                                    <option>Semana</option>
                                    <option selected>M&ecirc;s</option>
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-4 col-xl-2">
                                <label>Informa&ccedil;&atilde;o</label>
                                <select class="form-control form-control-sm change_controler" id="info_selecionada" >
                                    <option>Selecione</option>
                                    <option>Temperatura</option>
                                    <option>Volume de Chuva</option>
                                    <option>Volume de Chuva Acumulada</option>
                                    <option selected>Umidade do Ar</option>
                                </select>
                            </div>

                            <div class="col-sm-12 col-md-4 col-xl-2">
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

$(document).ready(function(){
            $("#estacao_selecionada").multiselect({
                includeSelectAllOption: true,
                buttonWidth: '100%'
              
            });
        });
     
     
     </script>

    {literal}
    
        <script> 
        //Funções de tratativa de dados: 
    function GetTipoDados(){
            let info_selecionada = $( "#info_selecionada" ).val(); //Guarda esse valor numa variavel
            if(info_selecionada == "Selecione"){
                throw new Error('Necessário informar o tipo de dados desejado'); 
                return;
            }

            let tipo_dados;
                switch(info_selecionada)
                    {
                        case 'Temperatura':
                            tipo_dados = "TIPO_TEMPERATURA";
                        break;
                        case 'Volume de Chuva':
                            tipo_dados = "TIPO_VOLUME_CHUVA";
                        break;
                        case 'Umidade do Ar':
                            tipo_dados = "TIPO_UMIDADE_AR";
                        break;
                        case 'Volume de Chuva Acumulada':
                            tipo_dados = "TIPO_VOLUME_ACC_CHUVA";
                        break;
                                
                    }
            return tipo_dados;}


     function GetEstacao(){ //função que pega a descriçao da estação selecionada
     let estacao = $( "#estacao_selecionada" ).val(); //Guarda esse valor numa variavel
         
            let estacao_infos = new Array(); //Array com a estação selecionada e sua descrição 
                estacao_infos.push(estacao); //estacao_infos[0] == ID da estação


                if (estacao !== null) {
                    var selectedOptions = [];
                    estacao.forEach(function(value) {
                    var selectedOption = $('#estacao_selecionada option[value="' + value + '"]'); //obj de seleção
                        var descricao = selectedOption.text(); //valor de descriçao da estação
                        selectedOptions.push(descricao);
                    });
            
            resultado = [estacao, selectedOptions]  //estação = indice // selectedOption = texto da estação
            return resultado;
               }
               else {
                    GerarGrafico(0,0, "Nenhuma estação selecionada", 0);
                      throw new Error('Necessário informar a estação'); 
                return;
    }
}

//função que organiza o padrão da data para ser compativel com o banco de dados
     function GetData(){  
            let dataInicial = $( "#dataInicial").val(); //Guarda esse valor numa variavel
            let dataFinal = $( "#dataFinal" ).val(); //Guarda esse valor numa variavel
        //fazer tratativa de rros 

            let partesData = dataInicial.split('/'); // dividindo a data em partes (dia, mês e ano)
            let dataInicialFormatada = partesData[2] + "-" + partesData[1] + "-" + partesData[0]; //Organizando a data para o formato YYYY-MM-DD

            let partesData2 = dataFinal.split('/'); // dividindo a data em partes (dia, mês e ano)
            let dataFinalFormatada = partesData2[2] + "-" + partesData2[1] + "-" + partesData2[0];

            let datas = new Array(); //data em array
            datas.push(dataInicialFormatada); //data[0] = data inicial
            datas.push(dataFinalFormatada); //data [1] = data final 
        
            return datas; }


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


    function HandleAjax()
     {
            let escala; 
            let tipo_dados; 
            let datas = GetData(); 
            try {estacao = GetEstacao();} catch (e) {console.log(e.message); return;}
            try{ escala = GetEscala();} catch(e) {console.log(e.message)}
            try{ tipo_dados = GetTipoDados();} catch(e) {console.log(e.message)}
       
           try {
                $.ajax({
                        url: "AdminLeituras/getEstatisticasLeiturasJson",
                        dataType: "json",
                        method: "POST",
                        data: {
                            estacao_selecionada: estacao[0],  //Estação [0] são os IDs da estação
                            data_inicial: datas[0], //data inicial formatada
                            data_final: datas[1], //data final formatada
                            escala: escala,
                            tipo_dados: tipo_dados},

                        success: function(data) {
        
                            //salva as keys/chaves como os periodos de tempo (no model: o dado vem como 2023=>20.55, ou seja, key = periodo 
                            const periodos = Object.keys(data).map(function(key) { return key;  });

                            //salva os value como valor (no model: 2023=>20.5555, ou seja, value = valor)
                            const valores = Object.values(data).map(function(value) { return value;});

                            // Função que gera o grafico, parametros perido para label, valores para montagem e descrição que é o nome da estação
                            GerarGrafico(periodos, valores, OrganizarLabel(estacao[1]), escala); 
                        },
                            
                        error: function (req, status, error) 
                        {   console.log(data);
                            console.log("Ocorreu um erro no AJAX - " + error);
                        }
                    });
           } catch (error){
            console.log(error + " - HandleAjax")
           }
            
     }


//EVENTO DE CHANGE DAS TAGS (ACOPLADO A ESTAÇÃO, TIPO DE INFORMAÇÃO E ESCALA)   
        $('.change_controler').change(function() {       
            HandleAjax();  });

//EVENTO DE CHANGE ACOPLADO À DATA, USANDO A CLASSE DATEPICKER E O EVENTO DATECHANGE - EVITA A DUPLICAÇÃO DO EVENTO NO MOUSEOVER DO CALENDARIO QUE OCORRE AO USAR O CHANGE PURO
        $('.datepicker').on('changeDate', function() {       
            HandleAjax();  });
            
        $('.datepicker').datepicker({
            format: 'dd/mm/yyyy',
             language: "pt-BR"  });

var myChart;

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
                                dataFormatada.push(date.toLocaleString('pt-BR', { month: 'short', year: 'numeric' }).replace(". de ", "/").toUpperCase());  // Formata a data para exibir o mês por extenso e o ano numerico
                            }   
                        return dataFormatada;

                    } else if (tipo_escala == "ESCALA_DIA") 
                        {
                            for (let i=0; i<labels.length; i++)
                                {
                                    let dateArray = labels[i].split("-"); //quebra a data que chega no formato yyyy-mm
                                    let date = new Date(dateArray[0], dateArray[1] - 1, dateArray[2]); // Cria uma varivel com o ano e o mês (Janeiro = 0)
                                    dataFormatada.push(date.toLocaleString('pt-BR', { day: 'numeric', month: 'numeric', year: 'numeric' }));  // Formata a data para exibir o mês por extenso e o ano numerico
                                }    
                        
                            return dataFormatada;

                    
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
                                } 
                    
            } 
        else //se o valor de labels vier vazio
            {
                return;
            }

    }

    function OrganizarLabel(descricao){ //Essa função organiza a label superior do grafico de forma que: Se apenas uma estação for selecionada, na label vai aparecer o nome completo da estação
        if (descricao.length > 1) 
            { //Se mais de uma estação for selecionada
                    const valoresEst = [];
                    for (let i = 0; i < descricao.length; i++)
                        {
                            const estacao = descricao[i];
                            const match = estacao.match(/est\d+/i);    // Expressão regular encontra o "est + numero"
                            
                            if (match) {         
                                const valorEst = match[0]; // Primeira aparição de "est+numero" 
                                valoresEst.push(valorEst); // Adicionar o valor à nova lista
                        }
                    }

                    return valoresEst; //retorna como argumento
                }
            else 
                {
                    return descricao; //retorna o nome inteiriço 
                }
    }

    function GerarGrafico (labels_value, data_value, descricao, tipo_escala)
    {  
            if (myChart) 
            { myChart.destroy(); }

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
                            }
                        }
                         };

            myChart = new Chart(ctx, config);


            $('#tipoGrafico').on('change', function (event) {
                alterarTipoGrafico($('#tipoGrafico').val())
            });
            
            function alterarTipoGrafico(newType) {
                    // Remove the old chart and all its event handles
                    if (myChart) { myChart.destroy();}
                    // Chart.js modifies the object you pass in. Pass a copy of the object so we can use the original object later
                    var temp = jQuery.extend(true, {}, config);
                    temp.type = newType;
                    myChart = new Chart(ctx, temp);
                    
                }

             alterarTipoGrafico($('#tipoGrafico').val());
    
    }
        </script>


    {/literal}

{/block}



