<?php
    include_once("Conexao.php");
    $result_pessoa = "SELECT * FROM pessoa WHERE id='6'"; 
    $resultado_pessoa = mysqli_query($conn, $result_pessoa);
    $row_pessoa = mysqli_fetch_assoc($resultado_pessoa);

    

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario</title>
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
    
</head>
<body>
    <div class="container">
        <form >
            <div class="form-group">
                <label for="nome"> Primeiro Nome:</label><br>
                <input type="text" id="nome" name="nome" class="form-control" value = "<?php echo $row_pessoa['nome'] ?>"><br>
            </div>
            

            <div class="form-group">
                <label for="tipo">Pessoa</label><br>
                <?php
                    $opcoes = ["Pessoa Física" => "PF", "Pessoa Jurídica" => "PJ"];
                ?>
                    <select name="tipo_pessoa" class = "form-control">
                        <?php foreach($opcoes as $label => $valor): ?>
                            <option value="<?=$valor?>" <?=$valor == $row_pessoa['tipo_pessoa'] ? "selected":""?>><?=$label?></option>
                        <?php endforeach;?>
                    </select>

            </div>

            
           

            <div class="form-group">
                <label for="cpf"> CPF: </label><br>
                <input type="text" id="cpf" name="cpf" class="form-control" value = "<?php echo $row_pessoa['cpf'] ?>" ><br>
                
            </div>
            
            <div class="form-group">
                <label for="data"> Data </label><br>
                <input type="date" class="form-control" name = "data" value = "<?php echo $row_pessoa['data_nascimento'] ?>"><br>
            </div>
            
            <div class="form-group">
                <label for="endereco"> Endereço </label><br>
                <input type="text" class="form-control" name = "endereco" value = "<?php echo $row_pessoa['endereco'] ?>"><br>
            </div>
            
            <div class="form-group">
                <label for="bairro"> Bairro </label><br>
                <input type="text" class="form-control" name = "bairro" value = "<?php echo $row_pessoa['bairro'] ?>"><br>
            </div>
            
            <div class="form-group">
                <label for="cep"> CEP </label><br>
                <input type="text" class="form-control" name = "cep" value = "<?php echo $row_pessoa['cep'] ?>"><br>
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
                            <option value="<?=$valor?>" <?=$valor == $row_pessoa['estado'] ? "selected":""?>><?=$label?></option>
                        <?php endforeach;?>
                    </select>
            </div>
            
            <div class="form-group">
                <label for="cidade"> Cidade </label><br>
                <input type="cidade" class="form-control" name = "cidade" value = "<?php echo $row_pessoa['cidade'] ?>"><br>
            </div>
            
            <div class="form-group">
                <label for="telefone"> Telefone </label><br>
                <input type="text" class="form-control" name = "telefone" value = "<?php echo $row_pessoa['telefone'] ?>"><br>
            </div>
            

            <div class="form-group">
                <label for="celular"> Celular </label><br>
                <input type="text" class="form-control" name = "celular" value = "<?php echo $row_pessoa['celular'] ?>"><br>
            </div>
            

            <div class="form-group">
                <label for="inscricao"> Inscrição Estadual </label><br>
                <input type="text" class="form-control" name = "inscricao" value = "<?php echo $row_pessoa['inscricao'] ?>"><br>
            </div>
            
            <div class="form-group">
                <label for="observacao"> Observações </label><br>
                <textarea name="xpto" id="" cols="30" rows="5" class = "form-control"><?=$row_pessoa['observacao'] ?></textarea><br>
            </div>
            
            <div>
                <button type="salvar" class="btn btn-primary">Salvar</button>
            </div>
           
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" 
    integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" 
    crossorigin="anonymous"></script>

</body>
</html>