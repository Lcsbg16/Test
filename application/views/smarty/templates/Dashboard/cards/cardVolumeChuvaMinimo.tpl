<div class="row">
    <div class="col">
        <h5 class="card-title text-uppercase text-muted mb-0">Volume Min. Chuva</h5>
        <span class="h2 font-weight-bold mb-0">
            {if $vol_chuva_min === NULL}
                -
            {else}
                {$vol_chuva_min|number_format:1:","}mm
            {/if}
        </span>
    </div>
    <div class="col-auto">
        <div class="icon icon-shape bg-gradient-gray text-white rounded-circle shadow">
            <i class="fas fa-cloud-rain"></i>
        </div>
    </div>
</div>
