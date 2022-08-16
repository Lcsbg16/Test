{extends file="app.tpl"}
{block name="conteudo"}
    {if $mensagem}
        <div class="alert alert-{$tipo_mensagem|default:'info'}" role="alert">
            {$mensagem}
            <p>
                <a href="{$link_voltar|default:'javascript:history.back()'}" class="btn btn-default active">&laquo; Voltar</a>
            </p>
        </div>
    {/if}
{/block}

