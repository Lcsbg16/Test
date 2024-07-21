<!DOCTYPE html>
<html>
<head>
    <title>Filtrar Leituras</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-multiselect@0.9.16/dist/css/bootstrap-multiselect.min.css">
    <link rel="stylesheet" href="{$BASE_URL}/assets/chosen/bootstrap-multiselect.css"/>
    <script type="text/javascript" src="{$BASE_URL}/assets/chosen/bootstrap-multiselect.js"></script>
</head>
<body>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <div>
        <form method="get" action="{$BASE_URL}AdminLeituras/">
            <div class="form-row">
                <div class="form-group col-md-2">
                    <label for="data_inicial">Data Hora Inicial:</label>
                    <input type="datetime-local" class="form-control" id="data_inicial" name="data_inicial" value="{if isset($smarty.get.data_inicial)}{$smarty.get.data_inicial}{/if}">
                </div>
                <div class="form-group col-md-2">
                    <label for="data_final">Data Hora Final:</label>
                    <input type="datetime-local" class="form-control" id="data_final" name="data_final" value="{if isset($smarty.get.data_final)}{$smarty.get.data_final}{/if}">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Filtrar</button>
            <button type="button" class="btn btn-secondary" onclick="window.location.href='{$BASE_URL}AdminLeituras/resetFilters'">Resetar Filtro</button>
        </form>

        <div class="mt-4">
            {$output}
        </div>
    </div>

</body>
</html>
