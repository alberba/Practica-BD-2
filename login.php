<?php

    session_start();

    if (isset($_POST['usuario']) && isset($_POST['contrasena'])) {

        // Recuperar los valores del formulario
        $usuario = $_POST['usuario'];
        $contrasena = $_POST['contrasena'];
        $credenciales = verificarCredenciales($usuario, $contrasena);

        // La función devolverá datos del usuario si las credenciales son correctas
        $nombreReal = $credenciales[0];
        $nom_usuario = $credenciales[1];
        $tipo_usuario = $credenciales[2];

        if ($nom_usuario == ""){
            $_SESSION['error'] = "Usuario o contraseña incorrectos";
            header("Location: portal_inicio_usuario.php");
            exit();
        } else{

            // Inicializamos las variables de sesión relacionadas con el usuario
            $_SESSION['nombreReal'] = $nombreReal;
            $_SESSION['nombreUsuario'] = $nom_usuario;
            $_SESSION['tipoUsuario'] = $tipo_usuario;

            // Redirigir al menú correspondiente
            switch ($tipo_usuario) {

                case "comprador":
                    $_SESSION['carrito'] = array();
                    header("Location: catshow.php");
                    break;

                case "vendedor":
                    header("Location: vendedor.php");
                    break;

                case "controlador":
                    header("Location: controlador.php");
                    break;
                
                case "repartidor":
                    header("Location: repartidor.php");
                    break;

                default:
                    echo "Error";
            }

            exit();
            
        }
    } 


    // Función para verificar las credenciales del usuario
    function verificarCredenciales($username, $password){

        $conexion = mysqli_connect("localhost","root","");
        $bd = mysqli_select_db($conexion, "estimazon");

        $roles = array("comprador", "vendedor", "controlador", "repartidor");

        // Bucle para comprobar las credenciales en cada usuario de la base de datos
        foreach ($roles as $rol) {

            $consulta = mysqli_query($conexion, "
                SELECT nombre, nUsuario, contraseña 
                FROM $rol
                WHERE nUsuario = '$username' AND contraseña = '$password'
            ");

            if ($fila = mysqli_fetch_array($consulta)) {
                return array($fila['nombre']." ", $fila['nUsuario']." ", $rol);
            }

        }

        return array("","","");

    }

?>