{extends file = 'app.tpl'}
{block name = "conteudo"}
    {include file="menu_lateral.tpl"}
    <div class="main-content">

        {include file="topo_logado.tpl"}

        <!-- Header -->
        <div class="header bg-gradient-primary {if !$header_especial|default:false}pb-6 pt-5 pt-md-1 d-none d-sm-block{else}pb-8 pt-5 pt-md-8{/if}">
        {block name="conteudo_header"}{/block}
    </div>
{block name="conteudo_logado"}{/block}

</div>
{/block}
