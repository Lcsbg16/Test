{assign var=body_class value='bg-default'}
{extends file="app.tpl"}
{block name="conteudo"}

    <div class="main-content">
        <div class="container-fluid mt-3">
            <div class="row">
                <div class="col">
                    <div class="card shadow">
                        <div class="card-body">
                            <form id="meuFormulario" action="{$BASE_URL}Login/esqueciSenha" method="POST" onsubmit="return onSubmit();">

                                <h1>Recuperação de Senha</h1>

                                <div class="form-group">
                                    <label for="username">Digite aqui seu e-mail</label>
                                    <input type="text" class="form-control" id="email" name="email" required>
                                </div>
                                <div id="msgemail"></div>

                                <a href="{$BASE_URL}" class="btn btn-warning">Voltar</a>
                                <button type="submit" class="btn btn-success">Recuperar</button>
                            </form>

                            <!--Feito para requerir apenas e-mail-->
                            <script>
                                function validarEmail(email) { //aceita email com .
                                    var re = /\S+@\S+\.\S+/;
                                    return re.test(email);
                                }

                                function onSubmit() {
                                    var email = document.getElementById("email").value;
                                    if (validarEmail(email)) {
                                        document.getElementById("msgemail").innerHTML = "";
                                        return true;
                                    } else {
                                        document.getElementById("msgemail").innerHTML = "<font color='red'>E-mail inválido </font>";
                                        alert("E-mail inválido :(");
                                        return false;
                                    }
                                }
                            </script>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

{/block}