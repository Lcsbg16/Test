/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$(document).ready(function(){
      var date_input=$('input[name="date"]'); //our date input has the name "date"
      var container=$('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
      var options={
        format: 'dd/mm/yyyy',
        container: container,
        todayHighlight: true,
        autoclose: true,
	language: "pt-BR", 
      };
      date_input.datepicker(options);
    });

$('#tipoGrafico').on('change', function() {
var colors = ['#007bff','#28a745','#333333','#c3e6cb','#dc3545','#6c757d'];
  var tipoGrafico = $(this).val();
  alert(tipoGrafico);
	
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
