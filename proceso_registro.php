<?php
    session_start();
    $conexion = mysqli_connect("localhost","root","");
    $bd = mysqli_select_db($conexion, "estimazon");

    if (isset($_POST['registrarse'])) {
        // asignar datos a variables locales
        $clase = $_POST['tipo'];
        $tabla = ($clase === "Cliente") ? "comprador" : "vendedor";
        $nombre = $_POST['nombre'];
        $nUsuario = $_POST['nUsuario'];
        $contr1 = $_POST['contrasena'];
        $contr2 = $_POST['confirmar_contrasena'];
        $telf = $_POST['telf'];
        $email = $_POST['email'];

        // comprobar que las contraseñas son iguales
        if ($contr1 !== $contr2) {
            header('Location: registrarse.php?error=2');
            exit();
        }

        // insertar usuario en su clase correspondiente
        if ($tabla === "comprador") {
            $insert = "INSERT INTO comprador(nombre, nUsuario, contraseña, teléfono, email) VALUES
                                ('$nombre', '$nUsuario', '$contr1', '$telf', '$email')";
        } else {
            $insert = "INSERT INTO vendedor(nombre, nUsuario, contraseña, teléfono, email, estado, numAvisos) VALUES
                                ('$nombre', '$nUsuario', '$contr1', '$telf', '$email', 'BUENO', 0)";
        }

        try {
            if (mysqli_query($conexion, $insert)) {
                // la consulta ha funcionado
                $dir = ($tabla === "comprador") ? "Location: catshow.php" : "Location: vendedor.php";
                // hay que inicializar las variables de sesión
                $_SESSION['nombreUsuario'] = $nUsuario;
                if ($tabla === "comprador")
                    $_SESSION['carrito'] = array();

                // redirigir a la salida
                header($dir);
                exit();
            } else {
                // algún dato no era válido
                header('Location: registrarse.php?error=3');
                exit();
            }
            
        } catch (Exception $e) {
            // la consulta no se ha ejecutado. Probablemente, el usuario ya existe.
            header('Location: registrarse.php?error=1');
            exit();
        }   
    } else {
        // se ha llegado hasta aquí por motivos extraños
        header('Location: registrarse.php?error=777');
        exit();
    }
    
?>