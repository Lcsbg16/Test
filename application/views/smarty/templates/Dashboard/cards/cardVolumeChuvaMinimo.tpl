<script src="https://cdn.jsdelivr.net/npm/alpinejs@2.8.2/dist/alpine.min.js"></script>

<div class="col-xl-{$largura} col-lg-6" x-data="{ volumeChuva: {$vol_chuva_min} }">
    <div class="card card-stats mb-4 mb-xl-4">
        <div class="card-body">
            <div class="row">
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
        </div>
    </div>

    <!-- Card de Alerta -->
    <!--<template x-if="volumeChuva == null || volumeChuva >= 40">
        <div class="alerta">
            <p>Tenha cuidado! O volume de chuva passou dos 50 mm</p>
        </div>
    </template> -->
</div>
