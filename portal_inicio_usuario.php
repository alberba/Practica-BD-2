<!DOCTYPE html>
<html lang="es">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="css/general.css">
    <link rel="stylesheet" type="text/css" href="css/login_registro.css">

</head>
<body>

    <div class="container">

        <form action="login.php" method="post" class="login-form">

            <h1>LOGIN</h1>

            <label for="usuario">Usuario:</label>
            <input class="login-input" type="text" id="usuario" name="usuario" required>

            <label for="contrasena">Contraseña:</label>
            <input class="login-input" type="password" id="contrasena" name="contrasena" required>

            <input type="submit" name="action" value="Iniciar sesión" class="login-button">
            
        </form>

        <?php
            session_start();
            if (isset($_SESSION['error'])) {
                echo '<p style="color: red;">' . $_SESSION['error'] . '</p>';
                unset($_SESSION['error']);
            }
        ?>

        <p>¿No tienes cuenta? <a href="registrarse.php">Registrarse</a></p>

        <a href=comprador/catshow.php>Volver a Estimazon</a>
     
    </div>
    
</body>
</html>


