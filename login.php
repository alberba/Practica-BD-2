<?php
session_start();
if (isset($_POST['usuario']) && isset($_POST['contrasena'])) {

    
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];
    $credenciales = verificarCredenciales($usuario, $contrasena);

    $nombreReal = $credenciales[0];
    $nom_usuario = $credenciales[1];
    $tipo_usuario = $credenciales[2];

    if ($nom_usuario == ""){
        echo "Usuario o contraseña incorrectos";
    } else{
        $_SESSION['nombreReal'] = $nombreReal;
        $_SESSION['nombreUsuario'] = $nom_usuario;

        if($tipo_usuario=="comprador"){
            $_SESSION['carrito'] = array();
            header("Location: catshow.php");
        }else if($tipo_usuario=="vendedor"){
            header("Location: vendedor.php");
        }else if($tipo_usuario=="controlador"){
            header("Location: controlador.php");
        }else{
            echo "Error";
        }

        exit();
        
    }
} 



function verificarCredenciales($username, $password){
    $conexion = mysqli_connect("localhost","root","");
    $bd = mysqli_select_db($conexion, "estimazon");

    //se comprueba si es un comprador
    $consulta = mysqli_query($conexion, "
    SELECT nombre, nUsuario, contraseña 
    FROM comprador
    WHERE nUsuario = '$username' AND contraseña = '$password'
    ");
    if ($fila = mysqli_fetch_array($consulta)) {
        return array($fila['nombre']." ", $fila['nUsuario']." ", "comprador");
    }

    //se comprueba si es un vendedor
    $consulta = mysqli_query($conexion, "
    SELECT nombre, nUsuario, contraseña 
    FROM vendedor
    WHERE nUsuario = '$username' AND contraseña = '$password'
    ");

    if ($fila = mysqli_fetch_array($consulta)) {
        return array($fila['nombre']." ", $fila['nUsuario']." ", "vendedor");
    }

    //se comprueba si es un controlador
    $consulta = mysqli_query($conexion, "
    SELECT nombre, nUsuario, contraseña 
    FROM controlador
    WHERE nUsuario = '$username' AND contraseña = '$password'
    ");

    if ($fila = mysqli_fetch_array($consulta)) {
        return array($fila['nombre']." ", $fila['nUsuario']." ", "controlador");
    }else{
        return array("","","");
    }


}


?>