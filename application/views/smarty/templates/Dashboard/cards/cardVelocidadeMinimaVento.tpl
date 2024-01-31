<div class="card card-stats mb-4 mb-xl-4">
    <div class="card-body">
        <div class="row">
            <div class="col">
                <h5 class="card-title text-uppercase text-muted mb-0">Vel. Min. do Vento</h5>
                <span class="h2 font-weight-bold mb-0">
                    {if $velocidade_minima == NULL}
                        -
                    {else}
                        {$velocidade_minima|number_format:1:","} km/h
                    {/if}
                </span>
            </div>
            <div class="col-auto">
                <div class="icon icon-shape bg-green text-white rounded-circle shadow">
                    <i class="fas fa-wind"></i>
                </div>
            </div>
        </div>

    </div>
</div>