<?php
$id = filter_input(INPUT_GET,'id',FILTER_SANITIZE_NUMBER_INT);

echo 'Quer mesmo apagar esse registro?';
echo "<a href='delete.php?id=$id&excluir=1'>Yes</a> |";
echo "<a href='listar.php'> No</a>";