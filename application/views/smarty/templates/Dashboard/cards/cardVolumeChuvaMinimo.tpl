
<div class="row"  x-data="{ volumeChuva: {$vol_chuva_min|default:0} }">
    <div class="col">
        <h5 class="card-title text-uppercase text-muted mb-0">Volume Min. Chuva</h5>
        <span class="h2 font-weight-bold mb-0">
            <template x-if="volumeChuva !== null">
                <span x-text="volumeChuva.toFixed(1).replace('.', ',') + 'mm'"></span>
            </template>
            <template x-if="volumeChuva === null">
                -
            </template>
        </span>
    </div>
    <div class="col-auto">
        <div class="icon icon-shape bg-gradient-blue text-white rounded-circle shadow">
            <i class="fas fa-cloud-rain"></i>
        </div>
    </div>
</div>

<!-- Card de Alerta -->
<!--<template x-if="volumeChuva == null || volumeChuva >= 40">
    <div class="alerta">
        <p>Tenha cuidado! O volume de chuva passou dos 50 mm</p>
    </div>
</template> -->
