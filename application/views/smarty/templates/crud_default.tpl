{extends file="app_logado.tpl"}
{block name="conteudo_logado"}
    <div class="container">
        {if isset($screen_title)}
            <h1>{$screen_title}</h1>
        {/if}
        {$output}
    </div>
{/block}
