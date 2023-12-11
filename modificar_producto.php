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
    <div class=titulo-sup>
        <h1>Estimazon</h1>
    </div>
</div>

<div class="productos-container">
    <?php
    $conexion = mysqli_connect("localhost", "root", "");
    $bd = mysqli_select_db($conexion, "estimazon");

    $nombre_usuario = $_SESSION['nombreUsuario'];

        $productos_vendedor = mysqli_query($conexion, "
            SELECT producto.idProducto, producto.nombre, producto.imagen
            FROM producto
            JOIN (SELECT idProducto
                FROM r_vendedor_producto 
                WHERE r_vendedor_producto.nUsuarioVend = '$nombre_usuario') as producto_vendedor
            ON producto.idProducto = producto_vendedor.idProducto
        ");

        while ($producto = mysqli_fetch_array($productos_vendedor)) {
            echo '<div class="producto">';
            echo '<a href="editar_producto.php?idProducto=' . $producto['idProducto'] . '">';
            echo '<img src="' . $producto['imagen'] . '" alt="' . $producto['nombre'] . '">';
            echo '<p>' . $producto['nombre'] . '</p>';
            echo '</a>';
            echo '</div>';
        }
    
    ?>
</div>

</body>
</html>