{extends file="app.tpl"}
{block name="conteudo"}
    {if $mensagem_erro}
        <div class="alert alert-danger" role="alert">
            {$mensagem_erro}
            <p>
                <a href="{$link_voltar|default:'javascript:history.back()'}" class="btn btn-default active">&laquo; Voltar</a>
            </p>
        </div>
    {/if}
{/block}
