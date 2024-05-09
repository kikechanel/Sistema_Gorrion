<?php
include_once "encabezado.php";
include_once "navbar.php";
session_start();

if(empty($_SESSION['usuario'])) header("location: login.php");

?>

<script src="jquery-3.7.1.min.js"></script>

<div class="container">
    <br>
    <h3 style="margin-bottom: 20px;">Agregar cliente</h3>
    <form method="post">
        <div class="row">
            <div class="col-md-4">
                <!-- Dropdown -->
                <div class="dropdown">
                <a class="btn btn-secondary btn-lg dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                      Elige una opcion
                 </a>
                 <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                    <li><a class="dropdown-item" href="agregar_cliente.php">DNI</a></li>
                    <li><a class="dropdown-item" href="agregar_cliente_RUC.php">RUC</a></li>
                  
                  </ul>
                </div>
            </div>
            <div class="col-md-4" >
                <!-- Campo de entrada para el RUC -->
                <div class="form-group">
                    <input type="text" name="ruc" class="form-control form-control-lg" id="ruc" placeholder="Escribe el RUC de la persona jurídica" maxlength="11"  onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                </div>
            </div>
            <div class="col-md-4 align-self-end">
                <!-- Botón de consulta -->
                <div class="form-group text-center">
                    <button id="consulta_ruc" class="btn btn-primary btn-lg" style="margin-left: 190px;">
                        Consultar RUC
                    </button>
                </div>
            </div>
        </div>
        <div class="row" style="margin-top: 20px;">
             <div class="col-md-6">
        <!-- Campo de Nombre -->
                <div class="form-group mt-3 mb-3">
                    <label for="nombres">Razon Social:</label>
                    <input type="text" class="form-control" name="nombres_respuesta" id="nombres_respuesta_input" readonly>
                </div>
            </div>
        <div class="col-md-6">
        <!-- Campo de Dirección -->
            <div class="form-group mt-3 mb-3">
                <label for="direccion">Domicilio fiscal:</label>
                <input type="text" class="form-control" name="direccion_respuesta" id="direccion_respuesta_input" readonly>
        
            </div>
        </div>
    </div>

<!-- Campo de Teléfono -->
<div class="form-group mt-3" style="margin-bottom: 40px;">
    <label for="telefono" class="form-label">Teléfono:</label>
    <input type="tel" name="telefono" class="form-control" id="telefono" placeholder="Ej. 2111568974"  minlength="7" maxlength="10"  onkeypress="return event.charCode >= 48 && event.charCode <= 57">
</div>

        <div class="text-center mt-3" >
            <input type="submit" name="registrar" value="Registrar" class="btn btn-primary btn-lg">
            
            </input>
            <a href="clientes.php" class="btn btn-danger btn-lg">
                <i class="fa fa-times"></i> 
                Cancelar
            </a>
        </div>
    </div>

        
    </form>



<br>



<script>
$(document).ready(function() {
    $("#consulta_ruc").click(function(event) {
        event.preventDefault();

        var ruc = $("#ruc").val();

        $.ajax({
            type: "POST",
            url: "consultas_ruc.php",
            data: { ruc: ruc },
            dataType: "json",
            success: function(data) {
                if (data === 1) {
                    alert('El RUC debe tener 11 dígitos.');
                } else {
                    console.log(data);
                    $("#nombres_respuesta").text(data.nombre);
                    $("#direccion_respuesta").text(data.direccion);

                    // Asignar valores a los campos ocultos
                    $("#nombres_respuesta_input").val(data.nombre);
                    $("#direccion_respuesta_input").val(data.direccion);
                }
            }
        });
    });
});

</script>

<?php
if(isset($_POST['registrar'])){
    $nombres = $_POST['nombres_respuesta'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion_respuesta'];
    if(empty($nombres)
    || empty($telefono) 
    || empty($direccion)){
        echo'
        <div class="alert alert-danger mt-3" role="alert">
            Debes completar todos los datos.
        </div>';
        return;
    } 
    
    include_once "funciones.php";
    $resultado = registrarCliente($nombres, $telefono, $direccion);
    if($resultado){
        echo'
        <div class="alert alert-success mt-3" role="alert">
            Cliente registrado con éxito.
        </div>';
    }
    
}

?>