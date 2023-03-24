<?php
    include_once("Conexao.php");

    if(isset($_GET["id"]))
    {
        $result_pessoa = "SELECT * FROM pessoa p WHERE p.id=".intval($_GET['id']);
        $resultado_pessoa = mysqli_query($conn, $result_pessoa);       
        if(mysqli_num_rows($resultado_pessoa) <= 0) 
        {
            die('Pessoa não encontrada!');
        }
        else
        {
            $pessoa = mysqli_fetch_assoc($resultado_pessoa);
        }
    }

?><!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario</title>
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
    
</head>
<body>
    <br>
    <div class="container">
        <form action="save.php" method="POST">
            <div class="form-group">
                <label for="nome"> Primeiro Nome:</label><br>
                <input type="text" id="nome" name="nome" class="form-control" value = "<?=isset($pessoa)?$pessoa['nome']:''?>"><br>
            </div>
            

            <div class="form-group">
                <label for="tipo">Pessoa</label><br>

                <?php
                    $opcoes = ["Pessoa Física" => "PF", "Pessoa Jurídica" => "PJ"];
                ?>
                    <select name="tipo_pessoa" class = "form-control">
                        <?php foreach($opcoes as $label => $valor): ?>
                            <option value="<?=$valor?>" <?=$valor == isset($pessoa)?$pessoa['tipo_pessoa']:''?>><?=$label?></option>
                        <?php endforeach;?>
                    </select>

            </div>

            
           
            <div class="form-group">
                <label for="cpf">CPF</label><br>
                <input type="text" name="cpf" class="form-control" id="cpf" value="<?=isset($pessoa)?$pessoa['cpf']:''?>"><br>
            </div>
            
            <div class="form-group">
                <label for="data"> Data </label><br>
                <input type="date" class = "form-control" name = "data_nascimento" id = "data_nascimento" value="<?=isset($pessoa)?$pessoa['data_nascimento']:''?>"><br>
            </div>
            
            <div class="form-group">
                <label for="endereco"> Endereço </label><br>
                <input type="text" class="form-control" name = "endereco" value = "<?=isset($pessoa)?$pessoa['endereco']:''?>"><br>
            </div>
            
            <div class="form-group">
                <label for="bairro"> Bairro </label><br>
                <input type="text" name="bairro" class="form-control" id="bairro" value="<?=isset($pessoa)?$pessoa['bairro']:''?>"><br>
            </div>
            
            <div class="form-group">
                <label for="cep"> CEP </label><br>
                <input type="text" class="form-control" name = "cep" value ="<?=isset($pessoa)?$pessoa['cep']:''?>"><br>
            </div>

            
            <div class="form-group">
                <label for="estado">Estado</label><br>
                <?php
                    $opcoes = 
                    ["Acre" => "AC", 
                    "Alagoas" => "AL",
                    "Amapá" => "AP",
                    "Amazônia" => "AM",
                    "Bahia" => "BA",
                    "Ceará" => "CE",
                    "Espírito Santo" => "ES",
                    "Goiás" => "GO",
                    "Maranhão" => "MA",
                    "Mato Grosso" => "MT",
                    "Mato Grosso do Sul" => "MS",
                    "Rio de Janeiro" => "RJ",
                    ];
                ?>
                    <select name="estado" class = "form-control">
                        <?php foreach($opcoes as $label => $valor): ?>
                            <option value="<?=$valor?>" <?=$valor == isset($pessoa)?$pessoa['estado']:''?>><?=$label?></option>
                        <?php endforeach;?>
                    </select>
            </div>
            
            <div class="form-group">
                <br><label for="cidade"> Cidade </label><br>
                <input type="cidade" class="form-control" name = "cidade" value ="<?=isset($pessoa)?$pessoa['cidade']:''?>"><br>
            </div>
            
            <div class="form-group">
                <label for="telefone"> Telefone </label><br>
                <input type="text" class="form-control" name = "telefone" value ="<?=isset($pessoa)?$pessoa['telefone']:''?>"><br>
            </div>
            

            <div class="form-group">
                <label for="celular"> Celular </label><br>
                <input type="text" class="form-control" name="celular" value ="<?=isset($pessoa)?$pessoa['celular']:''?>"><br>
            </div>
            

            <div class="form-group">
                <label for="inscricao"> Inscrição Estadual </label><br>
                <input type="text" class="form-control" name="inscricao" value ="<?=isset($pessoa)?$pessoa['inscricao']:''?>"><br>
            </div>
            
            <div class="form-group">
                <label for="observacao"> Observações </label><br>
                <textarea name="observacao" id="" cols="30" rows="5" class = "form-control"><?=isset($pessoa)?$pessoa['observacao']:''?></textarea><br>
            </div>
            
            
            <input type="hidden" name="id" id="id" value ="<?=isset($pessoa)?$pessoa['id']:''?>">  

            <div class="form-group">
                <button type="salvar" class="btn btn-primary" name="salvar" id="salvar">Salvar</button>
            </div>
           
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" 
    integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" 
    crossorigin="anonymous"></script>

</body>
</html>