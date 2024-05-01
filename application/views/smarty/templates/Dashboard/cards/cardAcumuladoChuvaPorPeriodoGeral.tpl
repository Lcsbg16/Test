<div class="col-xl-4 col-lg-6" id="card_acumuladoChuva">
    <div class="card card-stats mb-4 mb-xl-4">
        <div class="card-body" style="max-height: 150px; padding: 0!important;">
            <div class="row">
                <div id="carouselExampleControls" data-interval="10000" class="carousel slide" data-ride="carousel" style="width: 100%; height: 100%;">
                    <div class="carousel-inner">
                        {foreach $acumulados as $acumulado}
                            {if !isset($primeiro)}
                                <div class="carousel-item active">
                                    {$primeiro = true}
                                {else}
                                    <div class="carousel-item">
                                        {$primeiro = false}
                                    {/if}
                                    <h5 class="card-title text-uppercase text-muted mb-0" style="text-align: center;padding-bottom: 10px; padding: 0.5px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Chuva Agora:</h5>
                                    <h5 class="card-title text-uppercase text-muted mb-0" style="text-align: center;padding-bottom: 10px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{$acumulado->descricao}</h5>
                                    <div class="row d-flex justify-content-center align-items-center">
                                        <div class="col-6 d-flex justify-content-center align-items-center flex-column ">
                                            <h6 style="font-weight: bolder;"><i class="fas fa-cloud-rain" style="width: 15px; height: 15px; color:blue;"></i>Volume Acumulado de Chuva: </h6>
                                            <h6><strong>1 hora:</strong> {$acumulado->volume_1h|number_format:1:","}mm</h6>
                                            <h6><strong>24 horas:</strong> {$acumulado->volume_24h|number_format:1:","}mm</h6>
                                            <h6><strong>96 horas:</strong> {$acumulado->volume_96h|number_format:1:","}mm</h6>
                                        </div>
                                        <div class="col-4 d-flex justify-content-center align-items-center flex-column" style="align-self: baseline;">
                                            <h6 style="font-weight: bolder;"><i class="fa-solid fa-temperature-three-quarters" style="width: 15px; height: 15px; color:blue;"></i>Temperatura:</h6>
                                            <h2 style="font-size: 30px;"> {$acumulado->temperatura}ºC</h2>
                                        </div>
                                    </div>
                                </div>
                            {/foreach}
                        </div>
                        <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $('#carouselExampleControls').carousel();
        });
    </script>

    <style>
        .carousel-control-prev-icon {
            color: blue;
            background-image: url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%230008ff' viewBox='0 0 8 8'%3E%3Cpath d='M5.25 0l-4 4 4 4 1.5-1.5-2.5-2.5 2.5-2.5-1.5-1.5z'/%3E%3C/svg%3E");
        }
        .carousel-control-prev {
            left: -16px;
        }
        .carousel-control-next {
            right: -16px;
        }
        .carousel-control-next-icon{
            background-image: url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%230008ff' viewBox='0 0 8 8'%3E%3Cpath d='M2.75 0l-1.5 1.5 2.5 2.5-2.5 2.5 1.5 1.5 4-4-4-4z'/%3E%3C/svg%3E");
        }
    </style>
</div>