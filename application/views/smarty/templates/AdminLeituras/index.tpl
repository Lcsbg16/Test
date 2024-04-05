<div class="container">
    <form method="get" action="{$BASE_URL}AdminLeituras" class="form-inline" onsubmit="saveFormValues()"> 
        <div class="form-group">
            <label for="data_inicial">Data/hora de:</label>
            <input type="datetime-local" class="form-control" id="data_inicial" name="data_inicial" value="<?php echo isset($_GET['data_inicial']) ? $_GET['data_inicial'] : ''; ?>">
        </div>
        <div class="form-group">
            <label for="data_final">a:</label>
            <input type="datetime-local" class="form-control" id="data_final" name="data_final" value="<?php echo isset($_GET['data_final']) ? $_GET['data_final'] : ''; ?>">
        </div>
        <button type="submit" class="btn btn-primary mr-auto">Buscar</button>
    </form>
</div>

<script>
    // Função para salvar os valores dos campos de entrada no armazenamento local
    function saveFormValues() {
        var dataInicial = document.getElementById('data_inicial').value;
        var dataFinal = document.getElementById('data_final').value;

        localStorage.setItem('dataInicial', dataInicial);
        localStorage.setItem('dataFinal', dataFinal);
    }

    // Restaurar os valores dos campos de entrada do armazenamento local quando a página for carregada
    window.onload = function() {
        var dataInicial = localStorage.getItem('dataInicial');
        var dataFinal = localStorage.getItem('dataFinal');

        if (dataInicial) {
            document.getElementById('data_inicial').value = dataInicial;
        }

        if (dataFinal) {
            document.getElementById('data_final').value = dataFinal;
        }
    };
</script>
