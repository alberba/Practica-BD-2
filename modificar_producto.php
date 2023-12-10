<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Modificar Producto</title>
    
    <link rel="stylesheet" type="text/css" href="css/estils.css">
    <link rel="stylesheet" type="text/css" href="css/cabecera.css">
    <link rel="stylesheet" type="text/css" href="css/general.css">
    <link rel="stylesheet" type="text/css" href="css/producto.css">
    <link rel="stylesheet" type="text/css" href="css/vendedor.css">


</head>
<body>
<div class="sup">
    <div id="Titulo">
        <h1>Estimazon</h1>
    </div>
</div>

<div class="productos-container">
    <?php
    $conexion = mysqli_connect("localhost", "root", "");
    $bd = mysqli_select_db($conexion, "estimazon");

    $nombre_usuario = $_SESSION['nombreUsuario'];

    $consulta = mysqli_query($conexion, "SELECT idVendedor FROM vendedor WHERE nombre = '$nombre_usuario'");

    if ($fila = mysqli_fetch_assoc($consulta)) {
        $idVendedor = $fila['idVendedor'];

        $productos_vendedor = mysqli_query($conexion, "
            SELECT producto.idProducto, producto.nombre, producto.imagen
            FROM producto
            JOIN r_vendedor_producto ON producto.idProducto = r_vendedor_producto.idProducto
            WHERE r_vendedor_producto.idVendedor = '$idVendedor'
        ");

        while ($producto = mysqli_fetch_assoc($productos_vendedor)) {
            echo '<div class="producto">';
            echo '<a href="editar_producto.php?idProducto=' . $producto['idProducto'] . '">';
            echo '<img src="' . $producto['imagen'] . '" alt="' . $producto['nombre'] . '">';
            echo '<p>' . $producto['nombre'] . '</p>';
            echo '</a>';
            echo '</div>';
        }
    }
    ?>
</div>

</body>
</html>