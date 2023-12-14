<!DOCTYPE html>
<html lang="es">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión</title>
    
</head>
<body>

    <h2>Iniciar sesión</h2>

    <?php
    // Verificar si se ha enviado el formulario
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Recuperar los valores del formulario
        $usuario = $_POST["usuario"];
        $contrasena = $_POST["contrasena"];

        $nom_usuario = verificarCredenciales($usuario, $contrasena);
        if ($nom_usuario !== ""){
            session_start();

            $_SESSION['nombreUsuario'] = $nom_usuario;
            header("Location: productos.php");
            exit();
        }else{
            echo "Usuario o contraseña incorrectos";
        }
    }


    function verificarCredenciales($username, $password){
        $conexion = mysqli_connect("localhost","root","");
        $bd = mysqli_select_db($conexion, "estimazon");
        $consulta = mysqli_query($conexion, "
        SELECT nombre, nUsuario, contraseña 
        FROM vendedor
        WHERE nUsuario = '$username' AND contraseña = '$password'
        ");
        if ($fila = mysqli_fetch_array($consulta)) {
            return $fila['nombre']." ";
        } else {
            return "";
        }
    }
    ?>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="usuario">Usuario:</label>
        <input type="text" id="usuario" name="usuario" required>

        <br>

        <label for="contrasena">Contraseña:</label>
        <input type="password" id="contrasena" name="contrasena" required>

        <br>

        <input type="submit" value="Iniciar sesión">
    </form>

</body>
</html>
