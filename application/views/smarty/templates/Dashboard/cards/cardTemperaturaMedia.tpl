
<div class="card card-stats mb-4 mb-xl-4">
    <div class="card-body">
        <div class="row">
            <div class="col">
                <h5 class="card-title text-uppercase text-muted mb-0">Temperatura M&eacute;dia</h5>
                <span class="h2 font-weight-bold mb-0">
                    {if $temperatura_media === NULL}
                        -
                    {else}
                        {$temperatura_media|number_format:1:","}&deg;C
                    {/if}
                </span>
            </div>
            <div class="col-auto">
                <div class="icon icon-shape bg-yellow text-white rounded-circle shadow">
                    <i class="fas fa-temperature-half"></i>
                </div>
            </div>
        </div>
    </div>
</div>