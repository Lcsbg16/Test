 <!-- Projects table -->
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">Data</th>
                                    <th scope="col">Esta&ccedil;&atilde;o</th>
                                    <th scope="col">Evento</th>
                                </tr>
                            </thead>
                            <tbody>
                                {foreach $eventos as $evento}
                                    <tr>
                                        <th scope="row">
                                            {$evento.datahora|date_format:'%d/%m %H:%M'}
                                        </th>
                                        <td title="{$evento.estacao_identificador} - {$evento.estacao_descricao|escape:'quotes'}">
                                            {$evento.estacao_descricao|truncate:25:"...":true}
                                        </td>
                                        <td>
                                            {if $evento.tipo_evento_id == 1}
                                                <i class="fas fa-arrow-down text-danger mr-3"></i>Offline
                                            {elseif $evento.tipo_evento_id == 2}
                                                <i class="fas fa-arrow-up text-success mr-3"></i>Online
                                            {else}
                                                Tipo de evento desconhecido
                                            {/if}
                                        </td>
                                    </tr>
                                {/foreach}
                            </tbody>
                        </table>