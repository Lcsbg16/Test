{if empty($ocorrencias)}
    <div class="col">
        <h3 class="ml-2">Sem ocorrências registradas no momento.</h3>
    </div>
{else}
    <table class="table align-items-center table-flush">
        <thead class="thead-light">
            <tr>
                <th scope="col">Tipo</th>
                <th scope="col">Data/hora do ocorrido</th>
                <th scope="col">Endere&ccedil;o</th>
                <th scope="col">Descri&ccedil;&atilde;o</th>
            </tr>
        </thead>
        <tbody>
            {foreach $ocorrencias as $oAtual}
                <tr>
                    <th scope="row">
                        <a href="{$oAtual.url}">{$oAtual.tipo_ocorrencia}</a>
                    </th>
                    <td>
                        <a href="{$oAtual.url}">{$oAtual.datahora_ocorrido|date_format:'%d/%m/%Y %H:%M'}</a>
                    </td>
                    <td title="{$oAtual.endereco}">
                        <a href="{$oAtual.url}">{$oAtual.endereco|truncate:30}</a>
                    </td>
                    <td>
                        <a href="{$oAtual.url}">{$oAtual.descricao|truncate:30}</a>
                    </td>
                </tr>
            {/foreach}
        </tbody>
    </table>
{/if}