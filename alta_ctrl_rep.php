<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" type="text/css" href="css/general.css">
    <link rel="stylesheet" type="text/css" href="css/login_registro.css">
</head>
<body>
    <div class="container">
        <form action="proceso_registro_in.php" method="post" class="registro-form">
            <h1>REGISTRO</h1>
             
            <label for="tipo">Tipo de Usuario:</label>
            <!-- <select name="tipo" class="select-tipo" onchange="window.location.href='alta_ctrl_rep.php?tipo=' +this.value;" required> --> 
            <select name="tipo" class="select-tipo" required>
                <option value="Controlador" class="option-tipo">Controlador</option>
                <option value="Repartidor" class="option-tipo">Repartidor</option>
            </select>

            <label for="nombre">Nombre y Apellidos:</label>
            <input class=registro-input type="text" id="nombre" name="nombre" required>
            
            <label for="nUsuario">Nombre de Usuario:</label>
            <input class=registro-input type="text" id="nUsuario" name="nUsuario" required>

            <label for="contrasena">Contraseña:</label>
            <input class=registro-input type="password" id="contrasena" name="contrasena" required>

            <label for="confirmar_contrasena">Confirmar Contraseña:</label>
            <input class=registro-input type="password" id="confirmar_contrasena" name="confirmar_contrasena" required>

            <label for='nombreDist'>Empresa Distribuidora</label>
            <select name='dist' class='select-tipo' required>
            <?php
                /*
                if (isset($_GET['tipo']) && $_GET['tipo'] === 'Repartidor') {
                    echo "<label for='nombreDist'>Empresa Distribuidora</label>";
                    echo "<select name='dist' class='select-tipo' required>";
                */
                $conexion = mysqli_connect("localhost","root","");
                $bd = mysqli_select_db($conexion, "estimazon");

                $consulta = mysqli_query($conexion, "SELECT * FROM distribuidora");
                while ($dist = mysqli_fetch_array($consulta)) {
                    echo "<option value='" . $dist['idDistribuidora'] . "'>" . $dist['nombre'] . "</option>";
                }
                
                if (isset($_GET["error"])) {
                    $error = $_GET["error"];
                    $err_message = "";
                    switch ($error) {
                        case 1: $err_message = "Este nombre de usuario ya está en uso."; break;
                        case 2: $err_message = "Las 2 contraseñas no coinciden."; break;
                        case 3: $err_message = "Alguno de los datos está en formato incorrecto (o es demasiado largo)."; break;
                        default : $err_message = "Error desconocido."; break;
                    }
                    echo "<p class=error-mess> ". $err_message. "</p>";
                }
            ?>
            <input type="submit" name="registrar" value="Registrar Usuario" class="registro-button">
        </form>
    </div>
</body>
</html>