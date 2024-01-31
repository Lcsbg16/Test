
<div class="row">
    <div class="col">
        <h5 class="card-title text-uppercase text-muted mb-0">Temperatura M&iacute;nima</h5>

        <span class="h2 font-weight-bold mb-0">
            {if $temperatura_minima === NULL}
                -
            {else}
                {$temperatura_minima|number_format:1:","}&deg;C
            {/if}
        </span>

    </div>
    <div class="col-auto">
        <div class="icon icon-shape bg-blue text-white rounded-circle shadow">
            <i class="fas fa-temperature-empty"></i>
        </div>
    </div>
</div>

