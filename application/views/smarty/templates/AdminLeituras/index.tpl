<div class="container">
    <form method="get" action="{$BASE_URL}AdminLeituras" class="form-inline"> 
        <div class="form-group">
            <label for="data_inicial">Data/hora de:</label>
            <input type="datetime-local" class="form-control" id="data_inicial" name="data_inicial">
        </div>
        <div class="form-group">
            <label for="data_final">a:</label>
            <input type="datetime-local" class="form-control" id="data_final" name="data_final">
        </div>
        <button type="submit" class="btn btn-primary mr-auto">Buscar</button>
    </form>
</div>
