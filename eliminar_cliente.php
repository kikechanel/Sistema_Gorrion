<?php
$id = $_GET['id'];
if (!$id) {
    echo 'No se ha seleccionado el cliente';
    exit;
}
include_once "funciones.php";

$resultado = eliminarCliente($id);
if($resultado){
    echo'
    <div class="alert alert-success mt-3" role="alert">
        Cliente eliminado con éxito.
    </div>';

    return;
}

header("Location: clientes.php");
?>