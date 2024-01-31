<div class="row">
    <div class="col">
        <h5 class="card-title text-uppercase text-muted mb-0">Temperatura M&aacute;xima</h5>

        <span class="h2 font-weight-bold mb-0">
            {if $temperatura_maxima === NULL}
                -
            {else}
                {$temperatura_maxima|number_format:1:","}&deg;C
            {/if}
        </span>

    </div>
    <div class="col-auto">
        <div class="icon icon-shape bg-gradient-red text-white rounded-circle shadow">
            <i class="fas fa-temperature-full"></i>
        </div>
    </div>
</div>
