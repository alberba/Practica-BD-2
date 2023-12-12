<?php
    session_start();
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
</head>
<body>
    <?php
        include "cabecera_comprador.php";
    ?>

    <?php
        $conexion = mysqli_connect("localhost","root","");
        $bd = mysqli_select_db($conexion, "estimazon");

        // Procesar el formulario cuando se envía
        if ($_SERVER["REQUEST_METHOD"] == "POST"){
            if (isset($_POST['idIVP']) && isset($_POST['cantidad'])) {
                $IVPId = $_POST['idIVP'];
                $cantidad = $_POST['cantidad'];

                if ($cantidad > 0){
                    $_SESSION['carrito'][$IVPId]['cantidad'] = $cantidad;
                } else {
                    unset($_SESSION['carrito'][$IVPId]);
                }

                // Después de actualizar la cantidad, redirige a la página del carrito o realiza cualquier otra acción necesaria.
                header("Location: carrito.php");
                exit();
            } else{
                echo "No se ha recibido ningún producto.";
            }
        }
    ?>
    
    <div class="subpage">
        <h2 class="subtitulo">Carrito de compra</h2>
    </div>
    <div class="content" id=content-carrito>
        <div id="div-carrito">
            <?php
                if (isset($_SESSION['carrito']) && !empty($_SESSION['carrito'])) {
                    // acumulador del precio
                    $precio_total = 0;

                    // contador para determinar cuándo iniciar una nueva fila
                    $contador = 0;
                    echo "<div class='lista-carrito'>";

                    // mostrar todos los productos del carrito
                    foreach ($_SESSION['carrito'] as $idIVP => $detallesProducto) {
                        // obtenemos valores de cantidad e idVendedor
                        $idProducto = $detallesProducto['producto'];
                        $cantidad = $detallesProducto['cantidad'];
                        $nUsuarioVend = $detallesProducto['nUsuarioVend'];
                        // consulta del nombre del producto, precio, stock y nombre del vendedor
                        $consulta = mysqli_query($conexion, "
                            SELECT producto.nombre AS prod, producto.imagen, i_prod_vend.precio, nom_vend.nombre AS vend, i_prod_vend.stock
                            FROM producto
                            JOIN
                                (SELECT precio, nUsuarioVend, stock
                                FROM info_vendedor_producto
                                WHERE idIVP = $idIVP) AS i_prod_vend
                            JOIN
                                (SELECT vendedor.nombre
                                FROM vendedor
                                WHERE nUsuario = '$nUsuarioVend') AS nom_vend
                            WHERE producto.idProducto = $idProducto
                        ");
                        
                        // realizamos las consultas
                        $fila = mysqli_fetch_array($consulta);

                        // calcular precio total
                        $precio = $fila['precio'] * $cantidad;
                        $precio_total += $precio;

                        // Mostrar la imagen y la información del producto
                        echo "<div class='producto'>";
                            echo "<div class='imagen-descripcion-prod'>";
                                echo "<div class='imagen-prod-container'>";
                                    echo "<img src='" . $fila['imagen'] . "' alt='" . $fila['prod'] . "' class='imagen-prod'>";
                                echo "</div>";
                                echo "<div class='descripcion-prod'>";
                                    
                                    echo "<div class=info-prod-carrito>";
                                        echo "<a class=link-prod href='prodshow.php?prod=".$idProducto."'>";
                                            echo "<h4>" . $fila['prod'] . "</h4>";
                                        echo "</a>";
                                    echo "</div>";
                                    echo "<div class=info-prod-carrito>";
                                        echo "<p class=precio-prod-carrito>$" . $fila['precio'] . "</p>";
                                    echo "</div>";

                                    echo "<p class=info-prod-carrito>Vendedor: " . $fila['vend'] . "</p>";
                                echo "</div>";

                            echo "</div>";    
                                    
                            echo "<div class=cant-tot-precio-producto>";
                                echo "<form method='post'>";
                                    echo "<input type='hidden' name='idIVP' value='" . $idIVP . "'>";
                                    echo "<select name='cantidad' onchange='this.form.submit()'>";
                                    for ($i=0; $i <= $fila['stock']; $i++) {
                                        if ($i == $cantidad) {
                                            echo "<option value='" . $i . "' selected>" . $i . "</option>";
                                        } else {
                                            echo "<option value='" . $i . "'>" . $i . "</option>";
                                        }
                                    }
                                    echo "</select>";
                                echo "</form>";

                                echo "<p>Total: $" . $precio . "</p>";
                            echo "</div>";
                                
                        echo "</div>";
                    }
                    echo "</div>";
                    echo "<div class='div-carrito-right'>";
                        echo "<div id=div-carrito-right-inside>";
                            // mostrar precio total arriba y a la derecha en color verde
                            echo "<div id='precio-total-container'>";
                            echo "<h3 id='precio-total' style='color: green; text-align: center;'>Precio total: $". $precio_total . "</h3>";
                            echo "</div>";
                        
                            // mostrar información general y formulario de pago
                            echo "<div class='info-gen-carrito'>";
                                echo '<form method="post" action="prepago.php">';
                                    echo '<input type="hidden" name="precio_total" value="' . $precio_total . '">';
                                    echo '<input class="boton-pago" type="submit" value="Ir al pago">';
                                echo '</form>';
                            echo "</div>";
                        echo "</div>";
                    echo "</div>";
                } else {
                    echo "<h3>No hay productos en el carrito.</h3>";
                }
            ?>
        </div>
    </div>
</body>
</html>

