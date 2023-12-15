<?php 

    session_start(); 

    $conexion = mysqli_connect("localhost", "root", "");
    $bd = mysqli_select_db($conexion, "estimazon");
    
?>

<!DOCTYPE html>
<html lang="es">
<head>

    <meta charset="UTF-8">
    <title>Modificar Producto</title>
    
    <link rel="stylesheet" type="text/css" href="../css/cabecera.css">
    <link rel="stylesheet" type="text/css" href="../css/general.css">
    <link rel="stylesheet" type="text/css" href="../css/vendedor.css">

</head>
<body>

    <?php
        include "../cabecera.php";
    ?>

    <div class="productos-container">
        
        <?php

            $nombre_usuario = $_SESSION['nombreUsuario'];

            // Consulta de todos los productos del vendedor
            $productos_vendedor = mysqli_query($conexion, "
                SELECT producto.idProducto, producto.nombre, producto.imagen
                FROM producto
                JOIN (SELECT idProducto
                    FROM info_vendedor_producto 
                    WHERE info_vendedor_producto.nUsuarioVend = '$nombre_usuario') as producto_vendedor
                ON producto.idProducto = producto_vendedor.idProducto
            ");

            // Bucle para mostrar todos los productos del vendedor
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