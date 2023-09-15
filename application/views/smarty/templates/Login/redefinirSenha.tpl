{assign var=body_class value='bg-default'}
{extends file="app.tpl"}
{block name="conteudo"}

    <div class="main-content">
        <div class="container-fluid mt-3">
            <div class="row">
                <div class="col">
                    <div class="card shadow">
                        <div class="card-body">
                            <form id="meuFormulario" action="{$BASE_URL}Login/redefinirSenha" method="POST" onsubmit="return onSubmit();">

                                <h1>Redefinição de senha</h1>

                                <div class="form-group">
                                    <label for="username">Digite aqui sua senha</label>
                                    <input type="password" class="form-control" id="senha" name="senha" minlength="6" required>
                                </div>
                                <div class="form-group">
                                    <label for="username">Confirmar senha</label>
                                    <input type="password" class="form-control" id="confirmarSenha" name="confirmarSenha" minlength="6" required>
                                </div>

                                <input type="hidden" name="token" value="{$smarty.get.token}">

                                <div id="msgsenha"></div>

                                <button type="submit" class="btn btn-primary">Salvar</button>

                            </form>

                            <script>
                                function onSubmit() {
                                    var senha = document.getElementById('senha').value;
                                    var confirmarSenha = document.getElementById('confirmarSenha').value;

                                    if (senha !== confirmarSenha) {
                                        document.getElementById('msgsenha').innerHTML = 'As senhas não coincidem.';
                                        return false; // Impede o envio do formulário
                                    }

                                    return true; // Permite o envio do formulário
                                }
                            </script>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

{/block}