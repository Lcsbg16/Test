<?php 
    include_once("Conexao.php");
    $id = filter_input(INPUT_POST,"id", FILTER_SANITIZE_NUMBER_INT);
    $nome = filter_input(INPUT_POST,"nome",FILTER_SANITIZE_STRING);
    $tipo_pessoa = filter_input(INPUT_POST,"tipo_pessoa",FILTER_SANITIZE_STRING);
    $cpf = filter_input(INPUT_POST, 'cpf', FILTER_SANITIZE_STRING);
    $data_nascimento = filter_input(INPUT_POST, 'data_nascimento', FILTER_SANITIZE_SPECIAL_CHARS);
    $endereco = filter_input(INPUT_POST, 'endereco', FILTER_SANITIZE_STRING);
    $bairro = filter_input(INPUT_POST, 'bairro', FILTER_SANITIZE_STRING);
    $cep = filter_input(INPUT_POST, 'cep', FILTER_SANITIZE_NUMBER_INT);
    $estado = filter_input(INPUT_POST, 'estado', FILTER_SANITIZE_SPECIAL_CHARS);
    $cidade = filter_input(INPUT_POST, 'cidade', FILTER_SANITIZE_STRING);
    $telefone = filter_input(INPUT_POST, 'telefone', FILTER_SANITIZE_STRING);
    $celular = filter_input(INPUT_POST, 'celular', FILTER_SANITIZE_STRING);
    $inscricao = filter_input(INPUT_POST, 'inscricao', FILTER_SANITIZE_STRING);
    $observacao = filter_input(INPUT_POST, 'observacao', FILTER_SANITIZE_STRING);
    $data = date ('Y-m-d H:i:s');
    
    if(isset($_POST['id'])){
        if($_POST['id']){
            $result_pessoa = "UPDATE pessoa SET nome = '$nome',tipo_pessoa='$tipo_pessoa', cpf='$cpf',data_nascimento='$data_nascimento',endereco='$endereco',
            bairro='$bairro',cep='$cep',estado='$estado', cidade ='$cidade', telefone='$telefone', celular = '$celular',
            inscricao = '$inscricao', observacao = '$observacao', data_atualizacao='$data' where id='$id'";

        }else{
            $result_pessoa="INSERT INTO pessoa (nome,tipo_pessoa,cpf,data_nascimento,endereco,bairro,cep,estado,cidade,telefone,celular,inscricao,observacao,data_atualizacao,data_criacao)
            values('$nome','$tipo_pessoa','$cpf','$data_nascimento','$endereco','$bairro','$cep','$estado','$cidade','$telefone','$celular','$inscricao','$observacao','$data','$data')";
        }

        $resultado_usuario = mysqli_query($conn,$result_pessoa);

        

        if($resultado_usuario){
            echo "Operacao realizada com sucesso!";
        }else{
            echo "Houve um erro na operacao";
        }

        header("Location: listar.php");
    }   