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
        <form action="proceso_registro.php" method="post" class="registro-form">
            <h1>REGISTRO</h1>

            <label for="tipo">¿Eres ...?:</label>
            <select name="tipo" class="select-tipo" required>
                <option value="Cliente" class="option-tipo">Cliente</option>
                <option value="Vendedor" class="option-tipo">Vendedor</option>
            </select>

            <label for="nombre">Nombre y Apellidos:</label>
            <input class=registro-input type="text" id="nombre" name="nombre" required>
            
            <label for="nUsuario">Nombre de Usuario:</label>
            <input class=registro-input type="text" id="nUsuario" name="nUsuario" required>

            <label for="contrasena">Contraseña:</label>
            <input class=registro-input type="password" id="contrasena" name="contrasena" required>

            <label for="confirmar_contrasena">Confirmar Contraseña:</label>
            <input class=registro-input type="password" id="confirmar_contrasena" name="confirmar_contrasena" required>
            
            <label for="telf">Teléfono:</label>
            <input class=registro-input type="text" id="telf" name="telf" pattern="^(\+\d{1,3}\s)?\d{3}((\s?\d{2}){3}|(\s?\d{3}){2})$" title="Ingrese un número de teléfono válido" required>

            <label for="email">Correo Electrónico:</label>
            <input class=registro-input type="email" id="email" name="email" required>

            <?php
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
            <input type="submit" name="registrarse" value="Registrarse" class="registro-button">
        </form>
        
        <p>¿Ya tienes una cuenta? <a href="portal_inicio_usuario.html">Iniciar sesión</a></p>
    </div>
</body>
</html>