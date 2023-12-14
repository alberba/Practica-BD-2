<?php 
    session_start(); 
?>
<!DOCTYPE html>
<html lang="es">
<head>

    <meta charset="UTF-8">
    <title>Añadir Producto Existente</title>
    
    <link rel="stylesheet" type="text/css" href="css/estils.css">
    <link rel="stylesheet" type="text/css" href="css/cabecera.css">
    <link rel="stylesheet" type="text/css" href="css/general.css">
    <link rel="stylesheet" type="text/css" href="css/producto.css">
    <link rel="stylesheet" type="text/css" href="css/vendedor.css">

</head>
<body>

    <?php
        include "cabecera.php";
    ?>

    <div class="productos-container">

        <?php

            $conexion = mysqli_connect("localhost", "root", "");
            $bd = mysqli_select_db($conexion, "estimazon");

            $nombre_usuario = $_SESSION['nombreUsuario'];

            $productos_vendedor = mysqli_query($conexion, "
                SELECT producto.idProducto, producto.nombre, producto.imagen
                FROM producto
                LEFT JOIN (SELECT idProducto, nUsuarioVend
                    FROM info_vendedor_producto 
                    WHERE info_vendedor_producto.nUsuarioVend = '$nombre_usuario') as producto_vendedor
                ON producto.idProducto = producto_vendedor.idProducto
                WHERE nUsuarioVend IS NULL
            ");

            // Mostrar los productos que tiene registrado el vendedor
            while ($producto = mysqli_fetch_array($productos_vendedor)) {

                echo '<div class="producto">';
                echo '<a href="añadir_producto_vendedor.php?idProducto=' . $producto['idProducto'] . '">';
                echo '<img src="' . $producto['imagen'] . '" alt="' . $producto['nombre'] . '">';
                echo '<p>' . $producto['nombre'] . '</p>';
                echo '</a>';
                echo '</div>';
            }
        
        ?>

    </div>

</body>
</html>