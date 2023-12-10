
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" type="text/css" href="css/registro.css">
</head>
<body>
    <div class="container">
        <form action="procesar_registro.php" method="post" class="registro-form">
            <h1>REGISTRO</h1>

            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required>

            <label for="apellido">Apellido:</label>
            <input type="text" id="apellido" name="apellido" required>

            <label for="email">Correo Electrónico:</label>
            <input type="email" id="email" name="email" required>

            <label for="contrasena">Contraseña:</label>
            <input type="password" id="contrasena" name="contrasena" required>

            <label for="confirmar_contrasena">Confirmar Contraseña:</label>
            <input type="password" id="confirmar_contrasena" name="confirmar_contrasena" required>

            <input type="submit" name="action" value="Registrarse" class="registro-button">
        </form>
        
        <p>¿Ya tienes una cuenta? <a href="pagina_principal.php">Iniciar sesión</a></p>
    </div>
</body>
</html>
