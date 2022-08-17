<div class="flash-message container">
    {foreach ['danger', 'warning', 'success', 'info'] as $msg}
        {assign var=nomeMsg value="alert_{$msg}"}
        {if isset($flashdata[$nomeMsg])}
            <div class="alert alert-{$msg} alert-dismissible fade show">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                {$flashdata[$nomeMsg]}
            </div>
        {/if}
    {/foreach}
    {foreach ['danger', 'warning', 'success', 'info'] as $msg}
        {if isset($alertas[$msg])}
            <div class="alert alert-{$msg} alert-dismissible fade show">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                {$alertas[$msg]}
            </div>
        {/if}
    {/foreach}
</div>