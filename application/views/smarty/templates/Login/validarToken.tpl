{assign var=body_class value='bg-default'}
{extends file="app.tpl"}
{block name="conteudo"}

<div class="main-content">
<div class="container-fluid mt-3">
        <div class="row">
            <div class="col">
                <div class="card shadow">
                    <div class="card-body">
<form id="meuFormulario" action="{$BASE_URL}Login/validarToken" method="POST" onsubmit="return onSubmit();">

<h1>Recuperação de Senha</h1>

        <div class="form-group">
            <label >Validar token</label>
            <input type="text" class="form-control" id="token" name="token">
        </div>
        <div id="msgemail"></div>
        
        <button type="submit" class="btn btn-primary">Validar</button>
        

</form>



     </div>
     </div>
                </div>
            </div>
        </div>
    </div>

{/block}