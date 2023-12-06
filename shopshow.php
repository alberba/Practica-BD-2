<!DOCTYPE html>
<head>
    <title>Catalogo - Estimazon</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="css/estils.css">
    <link rel="stylesheet" type="text/css" href="css/cabecera.css">
    <link rel="stylesheet" type="text/css" href="css/general.css">
</head>
<body>
    <?php
        include "cabecera.php";
    ?>
    <div class="subpage">
            <h2 class="subtitulo">Carrito de compra</h2>
    </div>
    <div class=content>
        <div class=catalogo>
            <ul class=lista-prod>
                <?php
                    session_start();

                    if(isset($_SESSION['carrito'])) {
                        foreach($_SESSION['carrito'] as $producto => $detalles) {
                            $vendedor = $detalles['idVendedor'];
                            $cantidad = $detalles['cantidad'];

                            $conexion = mysqli_connect("localhost","root","");
                            $bd = mysqli_select_db($conexion, "estimazon");
                            $consulta = mysqli_query($conexion, "
                                SELECT producto.nombre AS prod, r_prod_vend.precio, nom_vend.nombre AS vend
                                FROM producto
                                JOIN
                                    (SELECT precio, idVendedor
                                    FROM r_vendedor_producto
                                    WHERE idProducto = $producto
                                    AND idVendedor = $vendedor) AS r_prod_vend
                                JOIN
                                    (SELECT vendedor.nombre
                                    FROM vendedor
                                    WHERE idVendedor = $vendedor) AS nom_vend
                                WHERE producto.idProducto = $producto
                                ");
                            
                            if($consulta)
                                while ($fila = mysqli_fetch_array($consulta)) {
                                    echo "<li class='product-prev'>";
                                    echo "<div>";
                                        // nombre de producto
                                        echo "<a class=enl-prod href='prodshow.php?prod=".$fila['idProducto']."'>";
                                            echo $fila['prod'];
                                        echo "</a>";
                                        // resto de datos
                                        echo $cantidad;
                                        echo $fila['precio'];
                                        echo "Vendedor: ".$fila['vend'];
                                    echo "</div>";
                                    echo "</li>";
                                }
                        }
                    } else {
                      echo "<p>No hay productos en el carrito</p>";
                    }
                ?>
            </ul>
        </div>

    </div>
    
</body>
</html>