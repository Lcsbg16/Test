{extends file="app.tpl"}
{block name="conteudo"}

    {if $mensagem_sucesso}
        <div class="alert alert-success" role="alert">
            {$mensagem_sucesso}
            <p>
                <a href="{$link_ok|default:$BASE_URL}" class="btn btn-default active" target="{$target_ok|default:'_top'}">Ok</a>
            </p>
        </div>
    {/if}

{/block}

