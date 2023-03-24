<?php
require_once "save.php";
	
if(isset($_GET["id"]))
{
	if(isset($_GET['excluir']) && $_GET['excluir'] == 1)
	{
        $sql = 'DELETE FROM pessoa WHERE id='.intval($_GET['id']);
        $resultado_usuario = mysqli_query($conn,$sql);
        header("Location: listar.php");
	}
}

