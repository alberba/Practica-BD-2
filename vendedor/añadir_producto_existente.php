<?php 
    session_start(); 

    $conexion = mysqli_connect("localhost", "root", "");
    $bd = mysqli_select_db($conexion, "estimazon");

    $nombre_usuario = $_SESSION['nombreUsuario'];

    $string_consulta = "
        SELECT producto.idProducto, producto.nombre, producto.imagen
        FROM producto
        LEFT JOIN (SELECT idProducto, nUsuarioVend
            FROM info_vendedor_producto 
            WHERE info_vendedor_producto.nUsuarioVend = '$nombre_usuario') as producto_vendedor
        ON producto.idProducto = producto_vendedor.idProducto
        WHERE nUsuarioVend IS NULL";
    
    if(isset($_GET['busqueda']) && !empty($_GET['busqueda'])) {
        $nombre_prod = $_GET['busqueda'];
        $string_consulta = $string_consulta. " AND MATCH(producto.nombre) AGAINST ('$nombre_prod' IN BOOLEAN MODE)";
    }
    
    $string_consulta = $string_consulta. " ORDER BY RAND() LIMIT 10";
        
    $productos_vendedor = mysqli_query($conexion, $string_consulta);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Añadir Producto Existente</title>
    
    <link rel="stylesheet" type="text/css" href="../css/estils.css">
    <link rel="stylesheet" type="text/css" href="../css/cabecera.css">
    <link rel="stylesheet" type="text/css" href="../css/general.css">
    <link rel="stylesheet" type="text/css" href="../css/producto.css">
    <link rel="stylesheet" type="text/css" href="../css/vendedor.css">

</head>
<body>

    <?php
        include "../cabecera.php";
    ?>

    <div class="busqueda-container">
        <form action="añadir_producto_existente.php" class="busqueda-form" method="GET">
            <input type="text" name="busqueda" class="busqueda-in" placeholder="Buscar productos...">
            <button type="submit" class="busqueda-button">Buscar</button>
        </form>
    </div>

    <div class="productos-container">

        <?php
            // Mostrar los productos que el vendedor no vende y podría querer vender
            if ($producto = mysqli_fetch_array($productos_vendedor)) {

                do {
                    echo '<div class="producto">';
                    echo '<a href="añadir_producto_vendedor.php?idProducto=' . $producto['idProducto'] . '">';
                    echo '<img src="' . $producto['imagen'] . '" alt="' . $producto['nombre'] . '">';
                    echo '<p>' . $producto['nombre'] . '</p>';
                    echo '</a>';
                    echo '</div>';
                } while ($producto = mysqli_fetch_array($productos_vendedor));

            } else {
                // no hay productos con ese nombre o el usuario ya los vende
                echo '<h3>No hay ningún producto con este nombre a la venta que usted no venda actualmente.</h3>';
            }
        ?>

    </div>

</body>
</html>