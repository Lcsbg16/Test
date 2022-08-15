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
    </head>

    <body class="">
    {block name="conteudo"}{/block}
    <!--   Core   -->
    <script src="{$BASE_URL}/assets/temas/argon/assets/js/plugins/jquery/dist/jquery.min.js"></script>
    <script src="{$BASE_URL}/assets/temas/argon/assets/js/plugins/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <!--   Optional JS   -->
    <script src="{$BASE_URL}/assets/temas/argon/assets/js/plugins/chart.js/dist/Chart.min.js"></script>
    <script src="{$BASE_URL}/assets/temas/argon/assets/js/plugins/chart.js/dist/Chart.extension.js"></script>
    <!--   Argon JS   -->
    <script src="{$BASE_URL}/assets/temas/argon/assets/js/argon-dashboard.min.js?v=1.1.1"></script>

</body>

</html>