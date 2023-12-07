<?php
    if (isset($_POST['user']) && isset($_POST['pass'])) {
        $usuario = $_POST['user'];
        $contrasena = $_POST['pass'];
        $nom_usuario = verificarCredenciales($usuario, $contrasena);

        if ($nom_usuario !== ""){
            session_start();

            $_SESSION['nombreUsuario'] = $nom_usuario;
            $_SESSION['carrito'] = array();
            header("Location: catshow.php");
            exit();
        } else{
            echo "Usuario o contraseña incorrectos";
        }
    } 

    function verificarCredenciales($username, $password){
        $conexion = mysqli_connect("localhost","root","");
        $bd = mysqli_select_db($conexion, "estimazon");
        $consulta = mysqli_query($conexion, "
        SELECT nombre, nUsuario, contraseña 
        FROM comprador
        WHERE nUsuario = '$username' AND contraseña = '$password'
        ");
        if ($fila = mysqli_fetch_array($consulta)) {
            return $fila['nombre']." ";
        } else {
            return "";
        }
    }
?>