<?php
    require_once("conexao.php");
    // $sql = "SELECT * FROM pessoa";
    // $resultado_pessoa = mysqli_query($conn,$sql);

    $sql = "SELECT * FROM pessoa WHERE 1 ";

    if(isset($_GET['buscar']) && $_GET['buscar'] == 1)
    {
        $nome = ($_GET['nome']);
        $data_inicio = ($_GET['data_inicio']);
        $data_fim = ($_GET['data_fim']);
        $tipo_pessoa = ($_GET['tipo_pessoa']);
        if($nome)
        {
            $sql .= " AND nome LIKE '%{$nome}%'";
        }
        
        if($data_inicio)
        {
            $sql .= " AND data_nascimento >= '{$data_inicio}'";
        }
        
        if($data_fim)
        {
            $sql .= " AND data_nascimento <= '{$data_fim}' ";
        }

        if($tipo_pessoa)
        {
            $sql .= " AND tipo_pessoa LIKE '%{$tipo_pessoa}%'";
        }
        
    }
    $resultado_pessoa = mysqli_query($conn,$sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listagem</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <style>
            h1{
                display:flex;
                justify-content: center;   
            }
    </style>
</head>
<body>
    <div class="container">
        <h1>Buscar Usuario</h1>
        <div>
        <form action="listar.php" method="GET">
            <input type="hidden" name="buscar" value="1">
            Nome: <input type="text" name="nome" class="form-control" value="<?=isset($_GET['nome'])?$_GET['nome']:''?>">
            Dt. Nascimento: <br> De: <input type="date" name="data_inicio" class="form-control" value="<?=isset($_GET['data_inicio'])?$_GET['data_inicio']:''?>"> A: <input class="form-control" type="date" name="data_fim" value="<?=isset($_GET['data_fim'])?$_GET['data_fim']:''?>">
            <br>
            Tipo de Pessoa:
            <?php
                $opcoes = ["-" => "", "PF" => "PF", "PJ" => "PJ" ];
            ?>
            <select class="form-control" name="tipo_pessoa" id="tipo_pessoa">
                <?php foreach($opcoes as $label => $valor): ?>
                    <option value="<?=$valor?>" <?=isset($tipo_pessoa) && $tipo_pessoa == $valor ? 'selected':""?>><?=$label?></option>
                <?php endforeach;?>
            </select> 
            <br>        
            <button type="button" class="btn btn-primary" onclick="window.location='<?=$_SERVER['PHP_SELF']?>'">Limpar</button>
	        <button type="submit" class="btn btn-primary" >Buscar</button>
        </form>
    </div>
        <div>
        <hr>
        <h1>Listar Usuario</h1>    
        <table class="table table-striped table-dark" >
            <thead class="dark">
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Nome</th>
                    <th scope="col">Cpf</th>
                    <th scope="col">Opções</th>
                </tr>
            </thead>
            <tbody>
                <?php
               
                    while($pessoa=mysqli_fetch_assoc($resultado_pessoa)){
                        echo "<tr>";
                        echo "<td>".$pessoa['id']."</td>";
                        echo "<td>".$pessoa['nome']."</td>";
                        echo "<td>".$pessoa['cpf']."</td>";
                        echo "<td>
                            <a class='btn btn-sm btn-primary'    href='formulario.php?id=$pessoa[id]'>
                                <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-pencil-fill' viewBox='0 0 16 16'>
                                    <path d='M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z'/>
                                </svg>
                            </a>

                            <a class='btn btn-sm btn-danger' href='delete.php?id=$pessoa[id]&excluir=1' data-confirm='Quer mesmo apagar esse registro?'>
                                <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-trash' viewBox='0 0 16 16'>
                                    <path d='M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z'/>
                                    <path fill-rule='evenodd' d='M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z'/>
                                </svg>
                            </a>

                            
                        </td>";

                    }
                
                    
                ?>
                <tr>
                    <td>
                        Adicionar
                    </td>
                    <td>
                    </td>
                    <td>
                    </td>
                    <td>
                        <!-- <button type="button" class="btn btn-success">Add</button> -->
                        <a class="btn btn-sucess" href="formulario.php">
                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="green" class="bi bi-plus-circle-fill" viewBox="0 0 16 16">
                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3v-3z"/>
                            </svg>
                        </a>

                    </td>
                </tr>

            </tbody>
        </table>
    </div>
    </div>
</body>
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>
        <script src="js/personalizado.js"></script>





