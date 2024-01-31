<div class="row" style="transition: background-color 0.25s ease;" x-data="{ temperaturaMinima: {$temperatura_minima|default:0} }">
    <template x-if="temperaturaMinima <= 50">
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
    </template>
</div>
</div>


