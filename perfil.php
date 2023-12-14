<?php
    session_start();

    $conexion = mysqli_connect("localhost", "root", "");
    $bd = mysqli_select_db($conexion, "estimazon");

    $username = $_SESSION['nombreUsuario'];
    $tipo_usuario = $_SESSION['tipoUsuario'];

    // Conseguiremos los datos del usuario logeado
    if (isset($tipo_usuario)) {

        // Comprobamos que tipo de usuario es, ya que no todos comparten las mismas columnas
        if ($tipo_usuario == "comprador" or $tipo_usuario == "vendedor") {

            $consulta = mysqli_query($conexion, "
                SELECT nombre, nUsuario, contraseña, teléfono
                FROM $tipo_usuario
                WHERE nUsuario = '$username'
            ");

        } else {

            $consulta = mysqli_query($conexion, "
                SELECT nombre, nUsuario, contraseña
                FROM $tipo_usuario
                WHERE nUsuario = '$username'
            ");

        }

        $fila = mysqli_fetch_array($consulta);
    }

    eliminarDireccion();

    editarPerfil();

    function eliminarDireccion() {

        global $conexion, $username;

        // Comprobamos si se ha pulsado el botón de eliminar dirección
        if (isset($_GET["idDireccion"])) {

            // Obtenemos el id de la dirección a eliminar
            $idDireccion = $_GET["idDireccion"];

            mysqli_query($conexion, "
                DELETE FROM r_comprador_domicilio
                WHERE idDomicilio = '$idDireccion'
            ");

            // Refrescamos la pagina
            header("Location: perfil.php");

        }
    }

    function editarPerfil() {

        global $conexion, $username, $fila, $tipo_usuario;

        if ($_SERVER["REQUEST_METHOD"] == "POST" and $_POST["nombre"] != "") {

            $nombre = $_POST["nombre"];
            $contraseña = $_POST["contraseña"];
            $verificar_contraseña = $_POST["verificar_contrasena"];
            
            // Comprobamos que tipo de usuario esta intentando acceder al perfil
            if ($_SESSION['tipoUsuario'] != "controlador" and $_SESSION['tipoUsuario'] != "repartidor"){

                $telefono = $_POST["telefono"];

                // Evitamos hacer uso innecesario de la base de datos
                if ($nombre == $fila["nombre"] and $telefono == $fila["teléfono"] and $contraseña == "" and $verificar_contraseña == "") {
                    
                    header("Location: perfil.php");

                }

            } else {

                // Evitamos hacer uso innecesario de la base de datos
                if ($nombre == $fila["nombre"] and $contraseña == "" and $verificar_contraseña == "") {

                    header("Location: perfil.php");

                }
            }

            // Verificar si se ingresó una contraseña
            if (!empty($contraseña)) {

                // Verificar si la contraseña y la confirmación son iguales
                if ($contraseña != $verificar_contraseña) {

                    $_SESSION['nombreReal'] = $nombre;
                    // Las contraseñas no coinciden
                    // Redirige a la página de perfil, pasando por parámetro un cambio de perfil erróneo
                    header("Location: perfil.php?isOK=false");

                } else {

                    // Las contraseñas coinciden
                    // Actualizar datos dependiendo del tipo de usuario
                    if ($_SESSION['tipoUsuario'] == "comprador" or $_SESSION['tipoUsuario'] == "vendedor"){

                        mysqli_query($conexion, "
                            UPDATE $tipo_usuario
                            SET nombre = '$nombre', teléfono = '$telefono', contraseña = '$contraseña'
                            WHERE nUsuario = '$username'
                        ");

                    } else {

                        mysqli_query($conexion, "
                            UPDATE $tipo_usuario
                            SET nombre = '$nombre', contraseña = '$contraseña'
                            WHERE nUsuario = '$username'
                        ");

                    }

                    $_SESSION['nombreReal'] = $nombre;
                    // Redirigir a la página de perfil, pasando por parámtero un cambio de perfil exitoso
                    header("Location: perfil.php?isOK=true");

                }

            } else {

                // Se hace el cambio sin la contraseña dependiendo del tipo de usuario
                if ($_SESSION['tipoUsuario'] == "comprador" or $_SESSION['tipoUsuario'] == "vendedor"){

                    mysqli_query($conexion, "
                        UPDATE $tipo_usuario
                        SET nombre = '$nombre', teléfono = '$telefono'
                        WHERE nUsuario = '$username'
                    ");

                } else {

                    mysqli_query($conexion, "
                        UPDATE $tipo_usuario
                        SET nombre = '$nombre'
                        WHERE nUsuario = '$username'
                    ");

                }
                
                $_SESSION['nombreReal'] = $nombre;
                // Redirigir a la página de perfil, pasando por parámtero un cambio de perfil exitoso
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
        include "cabecera.php";
    ?>

    <div class="subpage">
        <h2 class="subtitulo">Perfil</h2>
    </div>

    <div id=main-container>

        <div id=form-perfil>

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

                <label for="nombre">Nombre:</label>
                <input class=input-perfil type="text" name="nombre" value="<?php echo $fila["nombre"]; ?>" required>

                <?php

                    if (isset($_SESSION['tipoUsuario']) && ($_SESSION['tipoUsuario'] != "controlador" and $_SESSION['tipoUsuario'] != "repartidor")) {

                        echo '<label for="telefono">Teléfono:</label>';
                        echo '<input class=input-perfil type="text" name="telefono" pattern="^(\+\d{1,3}\s)?\d{3}((\s?\d{2}){3}|(\s?\d{3}){2})$" value="' . $fila["teléfono"] . '" required>';
                    
                    }

                ?>

                <label for="contraseña">Cambiar contraseña:</label>
                <input class=input-perfil type="password" name="contraseña" value="">

                <label for="verificar_contrasena">Verificar contraseña:</label>
                <input class="input-perfil" type="password"  name="verificar_contrasena">

                <?php

                    // Comprobamos si se ha pasado por parámetro un cambio de perfil erróneo o exitoso
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

        <?php
            // Si el usuario es comprador, mostraremos sus direcciones de envío que tenga registradas
            if($_SESSION['tipoUsuario'] == "comprador") {

                echo '<div id=direccion-container>';

                    echo '<h3 class="subtitulo">Direcciones</h3>';
                    echo '<div id=direcciones>';
                    
                        echo '<a class="link" href="añadir_domicilios.php?llamador=perfil"> Añadir nuevo domicilio </a>';
        
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
                        
                    echo '</div>';

                echo '</div>';

            }

        ?>
    </div>

</body>