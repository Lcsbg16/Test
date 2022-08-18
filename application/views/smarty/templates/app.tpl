<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>
            {$titulo_pagina|default:$APPLICATION_TITLE}
        </title>
        <!-- Favicon -->
        <link href="{$BASE_URL}/assets/temas/argon/assets/img/brand/favicon.png" rel="icon" type="image/png">
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
        <!-- Icons -->
        <link href="{$BASE_URL}/assets/temas/argon/assets/js/plugins/nucleo/css/nucleo.css" rel="stylesheet" />
        <link href="{$BASE_URL}/assets/temas/argon/assets/js/plugins/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet" />
        <!-- CSS Files -->
        <link href="{$BASE_URL}/assets/temas/argon/assets/css/argon-dashboard.css?v=1.1.1" rel="stylesheet" />
        {if isset($css_files)}
            {foreach from=$css_files item=file}
                <link type="text/css" rel="stylesheet" href="{$file}" />
            {/foreach}
        {/if}

        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.js"></script>

        <!-- Include Date Range Picker -->
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.pt-BR.min.js"></script>



        <link href="{$BASE_URL}/assets/css/app.css" rel="stylesheet" />
        <script src="{$BASE_URL}/assets/js/app.js"></script>

        <script>
            var BASE_URL = "{$BASE_URL}";
        </script>
    </head>

    <body class="{$body_class|default:''}">
        {include file="flashMessage.tpl"}
        {if $mensagem_erro|default:false}
            <div class="alert alert-danger alert-dismissible fade show" >
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                {$mensagem_erro}
            </div>
        {/if}
    {block name="conteudo"}{/block}
    <!--   Core   -->
    <script src="{$BASE_URL}/assets/temas/argon/assets/js/plugins/jquery/dist/jquery.min.js"></script>
    <script src="{$BASE_URL}/assets/temas/argon/assets/js/plugins/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <!--   Optional JS   -->
    <!--   Argon JS   -->
    <script src="{$BASE_URL}/assets/temas/argon/assets/js/argon-dashboard.min.js?v=1.1.1"></script>

    {if isset($js_files)}
        {foreach from=$js_files item=file}
            <script src="{$file}"></script>
        {/foreach}
    {/if}
</body>

</html>