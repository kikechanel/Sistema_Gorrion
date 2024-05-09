<?php
include_once "encabezado.php";
include_once "navbar.php";
session_start();

if(empty($_SESSION['usuario'])) header("location: login.php");

?>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<div class="container">
    <br>
    <h3 style="margin-bottom: 20px;">Agregar cliente</h3>
    <form method="post">
        <div class="row">
            <div class="col-md-4">
                <div class="dropdown">
                    <a class="btn btn-secondary btn-lg dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                          Elige una opción
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                        <li><a class="dropdown-item" href="agregar_cliente.php">DNI</a></li>
                        <li><a class="dropdown-item" href="agregar_cliente_RUC.php">RUC</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-md-4">
                <!-- Campo de entrada para el DNI -->
                <div class="form-group">
                    <input type="text" name="dni" class="form-control form-control-lg" id="dni" placeholder="Escribe el DNI del cliente" maxlength="8"  onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                </div>
            </div>
            <div class="col-md-4 align-self-end">
                <!-- Botón de consulta -->
                <div class="form-group text-center">
                    <button id="consulta_dni" class="btn btn-primary btn-lg" style="margin-left: 190px;">
                        Consultar DNI
                    </button>
                </div>
            </div>
        </div>
        <div class="row" style="margin-top: 20px;">
            <div class="col-4">
                <div class="form-group mt-3">
                    <label for="nombres">Nombres:</label>
                    <input type="tel" name="nombres_respuesta" id="nombres_respuesta" class="form-control" readonly>
                </div>
            </div>
            <div class="col-4">
                <div class="form-group mt-3">
                    <label for="ape_p">Apellido Paterno:</label>
                    <input type="tel" id="ape_p_respuesta" name="ape_p_respuesta" class="form-control" readonly>
                </div>
            </div>
            <div class="col-4">
                <div class="form-group mt-3">
                    <label for="ape_m">Apellido Materno:</label>
                    <input type="tel" name="ape_m_respuesta" id="ape_m_respuesta" class="form-control" readonly>
                </div>
            </div>   
        </div>

        <!-- me llegas basura -->
        <input type="hidden" name="nombre_completo" id="nombre_completo">


        <div class="row" style="margin-bottom: 40px;">
            <div class="col-md-6">
                <div class="form-group mt-3">
                    <label for="telefono" class="form-label">Teléfono</label>
                    <input type="tel" name="telefono" class="form-control" id="telefono" placeholder="Ej. 2111568974" minlength="7" maxlength="10"  onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                </div>

            </div>
            <div class="col-md-6">
                <div class="form-group mt-3">
                    <label for="direccion" class="form-label">Dirección</label>
                    <input type="text" name="direccion" class="form-control" id="direccion" placeholder="17 A Sin Camino">
                </div>
            </div>
        </div>

        <div class="text-center mt-3">
            <input type="submit" name="registrar" value="Registrar" class="btn btn-primary btn-lg">
            <a href="clientes.php" class="btn btn-danger btn-lg">
                <i class="fa fa-times"></i> 
                Cancelar
            </a>
        </div>
    </form>
</div>

<script>
$(document).ready(function() {
    $("#consulta_dni").click(function(event) {
        event.preventDefault();

        var dni = $("#dni").val();

        $.ajax({
            type: "POST",
            url: "consultas_dni.php",
            data: { dni: dni },
            dataType: "json",
            success: function(data) {
                if (data.error) {
                    alert(data.error);
                } else {
                    $("#nombres_respuesta").val(data.nombres); //nombre en campo
                    
                    // nombre completo q registramos
                    var nombreCompleto = data.nombres + " " + data.apellidoPaterno + " " + data.apellidoMaterno;
            
                    $("#nombre_completo").val(nombreCompleto);
                    // valores en los campos correspondientes
                    $("#ape_p_respuesta").val(data.apellidoPaterno);
                    $("#ape_m_respuesta").val(data.apellidoMaterno);
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });
});
</script>

<?php
if(isset($_POST['registrar'])){
    $nombresCompleto = $_POST['nombre_completo'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];
    if(empty($nombresCompleto) || empty($telefono) || empty($direccion)){
        echo '
        <div class="alert alert-danger mt-3" role="alert">
            Debes completar todos los datos.
        </div>';
        return;
    } 
        include_once "funciones.php";
        $resultado = registrarCliente($nombresCompleto, $telefono, $direccion);
        if($resultado){
            echo '
            <div class="alert alert-success mt-3" role="alert">
            Cliente registrado con éxito.
            </div>';
        }
    
}

?>
