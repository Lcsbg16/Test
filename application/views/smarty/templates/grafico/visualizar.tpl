{extends file = 'app_logado.tpl'}
{block name = "conteudo_logado"}
  
{*<script src="{$BASE_URL}assets/js/grafico.js"></script>*}

    <div class="row my-3">
        <div class="col">
	     <label>Defina a Estação</label>	
            <select class="form-control form-control-sm">
	       <option>Estação A1</option>
	   </select>	
        </div>
	<div class="col">
	     <label>Data Inicial</label>
	       <input class="datepicker">
        </div>
	<div class="col">
       	    <label>Escala</label>	
            <select class="form-control form-control-sm">
               <option>Selecione</option>
	       <option>Dia - 1 Hora</option>
	       <option>Semana-Dia</option>
	       <option>Mês-Dia</option>
	       <option>Ano-Mês</option>
	   </select>	
        </div>
	<div class="col">
       	    <label>Tipo de informação</label>	
            <select class="form-control form-control-sm">
	       <option>Selecione</option>	
	       <option>Temperatura</option>
	       <option>Volume de Chuva</option>	       
	       <option>Volume de Chuva Acumulada</option>
               <option>Umidade do Ar</option>   
	   </select>	
        </div>

	<div class="col">
       	    <label>Tipo de gráfico</label>	
            <select id="tipoGrafico" class="form-control form-control-sm">
	       <option value="bar">Barras</option>
               <option value="line">Linhas</option> 
               <option value="radar">Radar</option>
	   </select>	
        </div>
    </div>

  

    <div class="row my-12">
        <div class="col-md-12 py-1">
            <div class="card">
                <div class="card-body">
                    <canvas id="grafico"></canvas>
                </div>
            </div>
        </div>
    </div>

 



<script>

$('.datepicker').datepicker({
    format: 'dd/mm/yyyy',
    language: "pt-BR"
});

$('#tipoGrafico').on('click', function() {
var colors = ['#007bff','#28a745','#333333','#c3e6cb','#dc3545','#6c757d'];
  var tipoGrafico = $(this).val();
	
  //inicio

	var grafico = document.getElementById('grafico');
	var chartData = {
	  labels: ["Segunda-feira", "Terça-feira", "Quarta-feira", "Quinta-feira", "Sexta-feira", "Sábado", "Domingo"],  
	  datasets: [{
	    data: [29.43, 28.40, 28.50, 29.53, 30.01, 29.45, 28.99],
	    backgroundColor: 'transparent',
	    borderColor: colors[0],
	    borderWidth: 4,
	    pointBackgroundColor: colors[0],
	    label: ["Temperatura C°"]	
	  }]};

  new Chart(grafico, {
  type: tipoGrafico,
  data: chartData,
  options: {
    scales: {
      xAxes: [{
        ticks: {
          beginAtZero: false
        }
      }]
    },
    legend: {
      display: false
    },
    responsive: true
  }
  });

	
  //fim	

});

</script>

{/block}

