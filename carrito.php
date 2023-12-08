<?php
    $conexion = mysqli_connect("localhost","root","");
    $bd = mysqli_select_db($conexion, "estimazon");
    ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="css/estils.css">
    <link rel="stylesheet" type="text/css" href="css/cabecera.css">
    <link rel="stylesheet" type="text/css" href="css/general.css">
    <link rel="stylesheet" type="text/css" href="css/producto.css">
    <link rel="stylesheet" type="text/css" href="css/carrito.css">
    <!-- Otros enlaces a archivos CSS -->
</head>
<body>
    <?php
        include "cabecera.php";
    ?>
    
    <div class="subpage">
        <h2 class="subtitulo">Carrito de compra</h2>
    </div>
    <div class="content">
        <div id="div-carrito">
            <?php
                if (isset($_SESSION['carrito']) && !empty($_SESSION['carrito'])) {
                    // acumulador del precio
                    $precio_total = 0;

                    // contador para determinar cuándo iniciar una nueva fila
                    $contador = 0;

                    // mostrar todos los productos del carrito
                    foreach ($_SESSION['carrito'] as $idProducto => $detallesProducto) {
                        // obtenemos valores de cantidad e idVendedor
                        $cantidad = $detallesProducto['cantidad'];
                        $idVendedor = $detallesProducto['idVendedor'];
                        // consulta del nombre del producto, precio, stock y nombre del vendedor
                        $consulta = mysqli_query($conexion, "
                            SELECT producto.nombre AS prod, producto.imagen, r_prod_vend.precio, nom_vend.nombre AS vend
                            FROM producto
                            JOIN
                                (SELECT precio, idVendedor
                                FROM r_vendedor_producto
                                WHERE idProducto = $idProducto
                                AND idVendedor = $idVendedor) AS r_prod_vend
                            JOIN
                                (SELECT vendedor.nombre
                                FROM vendedor
                                WHERE idVendedor = $idVendedor) AS nom_vend
                            WHERE producto.idProducto = $idProducto
                        ");
                        
                        // realizamos las consultas
                        $fila = mysqli_fetch_array($consulta);

                        // calcular precio total
                        $precio = $fila['precio'] * $cantidad;
                        $precio_total += $precio;

                        // Mostrar la imagen y la información del producto
                        echo "<div class='producto'>";
                                echo "<div class='imagen-prod-container'>";
                                    echo "<img src='" . $fila['imagen'] . "' alt='" . $fila['prod'] . "' class='imagen-prod'>";
                                echo "</div>";
                                echo "<div class='descripcion-prod'>";
                                    echo "<a class=link-prod href='prodshow.php?prod=".$idProducto."'>";
                                        echo "<h4>" . $fila['prod'] . "</h4>";
                                    echo "</a>";
                                    echo "<p>Precio por unidad: $" . $fila['precio'] . "</p>";
                                    echo "<p>Cantidad en el carrito: " . $cantidad . "</p>";
                                    echo "<p>Vendedor: " . $fila['vend'] . "</p>";
                                    echo "<p>Precio total: $" . $precio . "</p>";
                                echo "</div>";
                        echo "</div>";

                        // Incrementar el contador
                        $contador++;

                        // Si se han mostrado 4 productos, cerrar la fila y reiniciar el contador
                        if ($contador == 4) {
                            echo "<div class='clear'></div>";
                            $contador = 0;
                        }
                    }
                    // mostrar precio total arriba y a la derecha en color verde
                    echo "<div id='precio-total-container'>";
                    echo "<h3 id='precio-total' style='color: green; text-align: right;'>Precio total: {$precio_total}</h3>";
                    echo "</div>";
                
                    // mostrar información general y formulario de pago
                    echo "<div class='info-gen-carrito'>";
                        echo '<form method="post" action="prepago.php">';
                            echo '<input type="hidden" name="precio_total" value="' . $precio_total . '">';
                            echo '<input class="boton-pago" type="submit" value="Ir al pago">';
                        echo '</form>';
                    echo "</div>";
                } else {
                    echo "<h3>No hay productos en el carrito.</h3>";
                }
            ?>
        </div>
    </div>
</body>
</html>

