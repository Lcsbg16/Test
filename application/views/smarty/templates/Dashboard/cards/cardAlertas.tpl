<script src="https://cdn.jsdelivr.net/npm/alpinejs@2.8.2/dist/alpine.min.js"></script>

<div  x-data="{ temperaturaMinima: {$temperatura_minima} }">
<template x-if="temperaturaMinima <= 50">
<div class="card card-stats mb-4 mb-xl-4" style="transition: background-color 0.25s ease;">
    <div class="card-body">
        <div class="row">
            <div class="col">
                <h5 class="card-title text-uppercase text-muted mb-0">Alerta</h5>
                <span class="h2 font-weight-bold mb-0">
                ALERTA!!
                </span>
            </div>
            <div class="col-auto">
                <div class="icon icon-shape bg-gradient-gray text-white rounded-circle shadow">
                    <i class="fas fa-cloud-rain"></i>
                </div>
            </div>
        </div>
    </div>
</div>
</template> 
</div>
