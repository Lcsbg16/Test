<div class="row" id="classeCardAlerta" >
    <div class="col">
        <h5 class="card-title text-uppercase text-muted mb-0">Alerta</h5>
        <span class="h2 font-weight-bold mb-0 mensagem_card_alerta">
            {$mensagem}
        </span>
    </div>

    <div class="col-6 d-flex justify-content-center align-items-center flex-column">
        <h6 style="font-weight: bolder;justify-content-center;text-align: center;"><i class="fas fa-cloud-rain" style="width: 15px; height: 15px; color:blue;"></i> Volume Acumulado de Chuva: </h6>
        {foreach $acumulado as $acumulos}
        <h6><strong>1 hora:</strong> {$acumulos->volume_1h|number_format:1:","}mm</h6>
        <h6><strong>24 horas:</strong> {$acumulos->volume_24h|number_format:1:","}mm</h6>
        <h6><strong>96 horas:</strong> {$acumulos->volume_96h|number_format:1:","}mm</h6>
    {/foreach}
</div>
</div>



