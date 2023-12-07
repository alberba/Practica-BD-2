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
    <?php
        echo "<title>".$producto['nombre']." - Estimazon</title>";
    ?>    
</head>
<body>
    <?php
        include "cabecera.php";
    ?>
    
    <div class="subpage">
            <h2 class="subtitulo">Catálogo</h2>
    </div>
    <div class=content>
        <?php
            include "l_categorias.php";
        ?>
        <div id=vista-producto>
            <div id=vend-princ>
                <div class=imagen-prod>
                    <?php
                        echo "<img src=".$producto['imagen']." class='imagen-prod'>";
                    ?>
                </div>
                <div id=descripcion-prod>
                    <?php
                        echo "<h2>".$producto['nombre']."</h2>";
                        echo "<p>".$producto['descripcion']."</p>";
                        echo "<h3>".$p_fila_vendedores['precio']." €</h3>";
                        if ($p_fila_vendedores['stock'] <= 10)
                            echo "<p id=stock> Sólo quedan ".$p_fila_vendedores['stock']." unidades a este precio!</p>";
                    
                        echo "<form method='post' action='añadir_carrito.php'>";
                            echo "<input type='hidden' name='producto' value='".$idprod."'>";
                            echo "<input type='hidden' name='idVendedor' value='".$p_fila_vendedores['idVendedor']."'>";
                            echo '<label for="cantidad">Cantidad: </label>';
                            echo '<input type="number" id="cantidad" name="cantidad">';
                            echo '<input class=boton-compra type="submit" name="agregar" value="Agregar al carrito">';
                        echo "</form>"
                    ?>    
                </div>
            </div>
            <?php
                if ($p_fila_vendedores = mysqli_fetch_array($consulta_precios)) {
                    echo '<div id=otros-vend>';
                    echo '<p id=tit-otros-vend> Otros vendedores </p>';
                    echo '<ul>';
                    
                    do {
                        echo "<li>".$p_fila_vendedores['nombre'].": ".$p_fila_vendedores['precio']." €</li>";
                    } while ($p_fila_vendedores = mysqli_fetch_array($consulta_precios));
                    
                    echo '</ul>';
                    echo '</div>';
                }
            ?>
        </div>

    </div>
    
</body>
</html>