<?php
    session_start();
    $conexion = mysqli_connect("localhost","root","");
    $bd = mysqli_select_db($conexion, "estimazon");

    if (isset($_POST['registrar'])) {
        // asignar datos a variables locales
        $tabla = ($_POST['tipo'] == "Controlador") ? "controlador" : "repartidor";
        $nombre = $_POST['nombre'];
        $nUsuario = $_POST['nUsuario'];
        $contr1 = $_POST['contrasena'];
        $contr2 = $_POST['confirmar_contrasena'];
        if ($tabla == "repartidor") {
            $idDist = $_POST['dist'];
        }

        // comprobar que las contraseñas son iguales
        if ($contr1 !== $contr2) {
            header('Location: alta_ctrl_rep.php?error=2');
            exit();
        }

        // insertar usuario en su clase correspondiente
        if ($tabla === "controlador") {
            $insert = "INSERT INTO $tabla(nombre, nUsuario, contraseña) VALUES
                        ('$nombre', '$nUsuario', '$contr1')";
        } else {
            $insert = "INSERT INTO $tabla(nombre, nUsuario, contraseña, idDistribuidora) VALUES
                        ('$nombre', '$nUsuario', '$contr1', '$idDist')";
        }

        try {
            if (mysqli_query($conexion, $insert)) {
                // la consulta ha funcionado, redirigir a la salida
                header("Location: controlador.php");
                exit();
            } else {
                // algún dato no era válido
                header('Location: alta_ctrl_rep.php?error=3');
                exit();
            }
            
        } catch (Exception $e) {
            // la consulta no se ha ejecutado. Probablemente, el usuario ya existe.
            header('Location: alta_ctrl_rep.php?error=1');
            exit();
        }   
    } else {
        // se ha llegado hasta aquí por motivos extraños
        header('Location: alta_ctrl_rep.php?error=777');
        exit();
    }
    
?>