<!DOCTYPE html>{* Template de teste *}
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        {foreach from=$css_files item=file}
            <link type="text/css" rel="stylesheet" href="{$file}" />
        {/foreach}
    </head>
    <body>
        <div>
            <a href='<?php echo site_url('examples/customers_management') ?>'>Customers</a> |
            <a href='<?php echo site_url('examples/orders_management') ?>'>Orders</a> |
            <a href='<?php echo site_url('examples/products_management') ?>'>Products</a> |
            <a href='<?php echo site_url('examples/offices_management') ?>'>Offices</a> |
            <a href='<?php echo site_url('examples/employees_management') ?>'>Employees</a> |
            <a href='<?php echo site_url('examples/film_management') ?>'>Films</a> |
            <a href='<?php echo site_url('examples/multigrids') ?>'>Multigrid [BETA]</a>

        </div>
        <div style='height:20px;'></div>
        <div style="padding: 10px">
            {$output}
        </div>
        {foreach from=$js_files item=file}
            <script src="{$file}"></script>
        {/foreach}
    </body>
</html>

