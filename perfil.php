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

    eliminarDireccion();

    editarPerfil();

    function eliminarDireccion() {
        global $conexion, $username;

        if (isset($_GET["idDireccion"])) {
            $idDireccion = $_GET["idDireccion"];

            mysqli_query($conexion, "
                DELETE FROM r_comprador_domicilio
                WHERE idDomicilio = '$idDireccion'
            ");

            header("Location: perfil.php");
        }
    }

    function editarPerfil() {
        global $conexion, $username, $fila;

        if ($_SERVER["REQUEST_METHOD"] == "POST" and $_POST["nombre"] != "") {
            $nombre = $_POST["nombre"];
            $telefono = $_POST["telefono"];
            $contraseña = $_POST["contraseña"];
            $verificar_contraseña = $_POST["verificar_contrasena"];

            // Verificar si se ingresó una contraseña
            if (!empty($contraseña)) {
                // Verificar si la contraseña y la confirmación son iguales
                if ($contraseña != $verificar_contraseña) {
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
        include "cabecera_comprador.php";
    ?>

    <div class="subpage">
        <h2 class="subtitulo">Perfil</h2>
    </div>
    <div id=main-container>
        <div id=form-perfil>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <label for="nombre">Nombre:</label>
                <input class=input-perfil type="text" name="nombre" value="<?php echo $fila['nombre']; ?>" required>

                <label for="telefono">Telefono:</label>
                <input class=input-perfil type="number" name="telefono" value="<?php echo $fila['teléfono']; ?>" required>

                <label for="contraseña">Cambiar contraseña:</label>
                <input class=input-perfil type="password" name="contraseña" value="">

                <label for="verificar_contrasena">Verificar contraseña:</label>
                <input class="input-perfil" type="password"  name="verificar_contrasena">

                <?php
                if (isset($_GET["isOK"])){
                    $isOK = $_GET["isOK"];
                    if ($isOK == "false") {
                        echo '<p style="color: red">Las contraseñas no coinciden. Vuelve a intentarlo.</p>';
                    } elseif ($isOK == "true") {
                        echo '<p style="color: green">Perfil cambiado con exito</p>';
                    }
                }
                ?>

                <button class="input-perfil boton-input" type="submit">Guardar cambios</button>
            </form>
        </div>

        <div id=direccion-container>
            <h3 class="subtitulo">Direcciones</h3>
            <div id=direcciones>
                <?php
                    $consulta = mysqli_query($conexion, "
                    SELECT r_comprador_domicilio.idDomicilio, domicilio_poblacion.direccion, domicilio_poblacion.CP, domicilio_poblacion.nombre
                    FROM r_comprador_domicilio
                    JOIN 
                        (SELECT idDomicilio, direccion, CP, poblacion.nombre
                        FROM domicilio
                        JOIN poblacion
                        ON domicilio.idPoblacion = poblacion.idPoblacion) AS domicilio_poblacion
                    ON r_comprador_domicilio.idDomicilio = domicilio_poblacion.idDomicilio
                    WHERE nUsuarioComp = '$username'
                    ");

                    while ($fila = mysqli_fetch_array($consulta)) {
                        echo '<div class="direccion">';
                            echo '<p class="direccion-titulo">' . $fila['direccion'] . ', ' . $fila['CP'] . ', ' . $fila['nombre'] . '</p>';
                            echo '<a class="direccion-boton" href="perfil.php?idDireccion=' . $fila["idDomicilio"] . '">Eliminar</a>';
                        echo '</div>';
                    }
                ?>
            </div>
        </div>
    </div>

</body>