<?php
    session_start();

    $conexion = mysqli_connect("localhost","root","");
    $bd = mysqli_select_db($conexion, "estimazon");

    // Comprobar si se ha enviado el formulario
    if (isset($_POST['registrarse'])) {

        // Asignar datos a variables locales
        $tabla = ($_POST['tipo'] === "Cliente") ? "comprador" : "vendedor";
        $nombre = $_POST['nombre'];
        $nUsuario = $_POST['nUsuario'];
        $contr1 = $_POST['contrasena'];
        $contr2 = $_POST['confirmar_contrasena'];
        $telf = $_POST['telf'];
        $email = $_POST['email'];

        // Comprobar que las contraseñas son iguales
        if ($contr1 !== $contr2) {

            // Si no lo son, se redirige a la página de registro con un error
            header('Location: registrarse.php?error=2');
            exit();

        }

        // comprobar que el usuario no está en uso
        $existe = mysqli_query($conexion, "SELECT existe_usuario('$nUsuario') as Res");
        $res = mysqli_fetch_array($existe);

        if ($res['Res']) {

            // el usuario existe, no se debe hacer el insert
            header('Location: registrarse.php?error=1');
            exit();

        }

        // Insertar usuario en su clase correspondiente
        if ($tabla === "comprador") {

            $insert = "
                INSERT INTO comprador(nombre, nUsuario, contraseña, teléfono, email) VALUES
                ('$nombre', '$nUsuario', '$contr1', '$telf', '$email')
            ";

        } else {

            $insert = "
                INSERT INTO vendedor(nombre, nUsuario, contraseña, teléfono, email, estado, numAvisos) VALUES
                ('$nombre', '$nUsuario', '$contr1', '$telf', '$email', 'BUENO', 0)
            ";

        }

        try {

            if (mysqli_query($conexion, $insert)) {
                // La consulta ha funcionado

                // Redirigir al menú correspondiente
                $dir = ($tabla === "comprador") ? "Location: catshow.php" : "Location: vendedor.php";

                // Hay que inicializar las variables de sesión
                $_SESSION['nombreUsuario'] = $nUsuario;
                if ($tabla === "comprador")
                    $_SESSION['carrito'] = array();

                // Redirigir a la salida
                header($dir);
                exit();

            } else {

                // Algún dato no era válido
                header('Location: registrarse.php?error=3');
                exit();

            }
            
        } catch (Exception $e) {

            // La consulta no se ha ejecutado. Probablemente, algún dato no era válido
            header('Location: registrarse.php?error=3');
            exit();

        }   
    } else {

        // Se ha llegado hasta aquí por motivos extraños
        header('Location: registrarse.php?error=777');
        exit();

    }
    
?>