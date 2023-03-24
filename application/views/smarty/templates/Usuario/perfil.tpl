{extends file = 'app_logado.tpl'}
{assign var=header_especial value=true}
{block name='conteudo_header'}

<div class="container-fluid mt-3">
        <div class="row">
            <div class="col">
                <div class="card shadow">
                    <div class="card-body">
<h2>Alterar senha</h2>

<form id="meuFormulario" onsubmit="return validarFormulario()" action="{$BASE_URL}Usuario/salvarPerfil" method="POST">

    <div class="form-group">
        <label for="senha">Nova senha</label>
        <input type="password" class="form-control" id="senha" name="senha" required minlength="7">
    </div>

    <div class="form-group">
        <label for="confirmar_senha">Confirmar senha</label>
        <input type="password" class="form-control" id="confirmar_senha" required minlength="7">
    </div>

    <button type="submit" class="btn btn-primary">Salvar</button>


</form>

<script>

    function validarFormulario() 
    {
        var senha = document.getElementById("senha").value;
        var confirmarSenha = document.getElementById("confirmar_senha").value;

        if (senha != confirmarSenha) {
            alert("As senhas são diferentes. Favor conferir.");
            return false;
        }

        return true;
    }

</script>
                    </div>
                </div>
            </div>
        </div>
    </div>

{/block}