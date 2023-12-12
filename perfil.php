<?php
    session_start();

    $conexion = mysqli_connect("localhost", "root", "");
    $bd = mysqli_select_db($conexion, "estimazon");

    $username = $_SESSION['nombreUsuario'];

    $consulta = mysqli_query($conexion, "
        SELECT nombre, nUsuario, contraseña, teléfono
        FROM comprador
        WHERE nUsuario = '$username'
    ");

    $fila = mysqli_fetch_array($consulta);

    $mensaje_error = ""; // Inicializamos la variable de mensaje de error

    if ($_SERVER["REQUEST_METHOD"] == "POST" and $_POST["nombre"] != "") {
        $nombre = $_POST["nombre"];
        $telefono = $_POST["telefono"];
        $contraseña = $_POST["contraseña"];
        $verificar_contraseña = $_POST["verificar_contrasena"];

        // Verificar si se ingresó una contraseña
        if (!empty($contraseña)) {
            // Verificar si la contraseña y la confirmación son iguales
            if ($contraseña != $verificar_contrasena) {
                header("Location: perfil.php?isOK=false");
            } else {
                mysqli_query($conexion, "
                    UPDATE comprador
                    SET nombre = '$nombre', teléfono = '$telefono', contraseña = '$contraseña'
                    WHERE nUsuario = '$username'
                ");

                header("Location: perfil.php?isOK=true");
            }
        } else {
            mysqli_query($conexion, "
                UPDATE comprador
                SET nombre = '$nombre', teléfono = '$telefono'
                WHERE nUsuario = '$username'
            ");
            header("Location: perfil.php?isOK=true");
        }
    }

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="css/estils.css">
    <link rel="stylesheet" type="text/css" href="css/cabecera.css">
    <link rel="stylesheet" type="text/css" href="css/general.css">
    <link rel="stylesheet" type="text/css" href="css/perfil.css">
    <title>Perfil - Estimazon</title>   
</head>
<body>
    <?php
        include "cabecera.php";
    ?>

    <div class="subpage">
        <h2 class="subtitulo">Perfil</h2>
    </div>

    <div id=form-perfil>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="nombre">Nombre:</label>
            <input class=input-perfil type="text" name="nombre" value="<?php echo $fila['nombre']; ?>" required>

            <label for="telefono">Telefono:</label>
            <input class=input-perfil type="number" name="telefono" value="<?php echo $fila['teléfono']; ?>" required>

            <label for="contraseña">Contraseña:</label>
            <input class=input-perfil type="password" name="contraseña" value="">

            <label for="verificar_contrasena">Verificar Contraseña:</label>
            <input class=input-perfil type="password"  name="verificar_contrasena">

            <?php
            if (isset($_GET["isOK"])){
                if ($isOK == "false") {
                    echo '<p style="color: red">Las contraseñas no coinciden. Vuelve a intentarlo.</p>';
                } elseif ($isOK == "true") {
                    echo '<p style="color: green">Perfil cambiado con exito</p>';
                }
            }
            ?>

            <button type="submit">Guardar cambios</button>
        </form>
    </div>

</body>