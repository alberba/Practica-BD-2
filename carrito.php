<?php
    $conexion = mysqli_connect("localhost","root","");
    $bd = mysqli_select_db($conexion, "estimazon");
    if(isset($_GET["prod"])) {
        $idprod = $_GET["prod"];
        $consulta = mysqli_query($conexion, "
        SELECT nombre, imagen, descripcion
        FROM producto
        WHERE idProducto = $idprod
        ");
        $producto = mysqli_fetch_array($consulta);
        $consulta_precios = mysqli_query($conexion, "
        SELECT precio, stock, nombre, r_vendedor_producto.idVendedor
        FROM r_vendedor_producto
        JOIN 
            (SELECT idVendedor, nombre
            FROM vendedor) AS vendedor
        ON r_vendedor_producto.idVendedor = vendedor.idVendedor
        WHERE idProducto = $idprod
        ORDER BY precio ASC
        ");
        $p_fila_vendedores = mysqli_fetch_array($consulta_precios);
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="css/estils.css">
    <link rel="stylesheet" type="text/css" href="css/cabecera.css">
    <link rel="stylesheet" type="text/css" href="css/general.css">
    <link rel="stylesheet" type="text/css" href="css/producto.css">
       
</head>
<body>
    <?php
        include "cabecera.php";
    ?>
    
    <div class="subpage">
            <h2 class="subtitulo">Carrito</h2>
    </div>
    <div class=content>
        <div id=div-carrito>
            <?php
                if (isset($_SESSION['carrito']) && !empty($_SESSION['carrito'])) {
                    // acumulador del precio
                    $precio_total = 0;
                    // mostrar todos los productos del carrito
                    foreach ($_SESSION['carrito'] as $idProducto => $detallesProducto) {
                        // obtenemos valores de cantidad e idVendedor
                        $cantidad = $detallesProducto['cantidad'];
                        $idVendedor = $detallesProducto['idVendedor'];
                        // consultas de nombre del producto, precio, stock y nombre del vendedor
                        $consulta_nomprod = mysqli_query($conexion, "
                        SELECT nombre
                        FROM producto
                        WHERE idProducto = $idProducto
                        ");
                        $consulta_precio = mysqli_query($conexion, "
                        SELECT precio
                        FROM r_vendedor_producto
                        WHERE idProducto = $idProducto
                        AND idVendedor = $idVendedor
                        ");
                        $consulta_nombre = mysqli_query($conexion, "
                        SELECT nombre
                        FROM vendedor
                        WHERE idVendedor = $idVendedor
                        ");
                        
                        // realizamos las consultas
                        $nombre_prod = mysqli_fetch_array($consulta_nomprod);
                        $fila_precio = mysqli_fetch_array($consulta_precio);
                        $nombre_ven = mysqli_fetch_array($consulta_nombre);

                        // calcular precio total
                        $precio = $fila_precio['precio'] * $cantidad;
                        $precio_total += $precio;

                        echo "<h4>" . $nombre_prod['nombre'] . ": \t " . $cantidad . " \t-------\t " . $precio . " Vendedor: " . $nombre_ven['nombre'] . "</h4>";
                    }
                    // mostrar precio total
                    echo "<h3>Precio total: {$precio_total}</h3>";
                } else {
                    echo "<h3>No hay productos en el carrito</h3>";
                }
            ?>
        </div>
    </div>
</body>
</html>