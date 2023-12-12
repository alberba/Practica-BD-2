<?php
    $conexion = mysqli_connect("localhost","root","");
    $bd = mysqli_select_db($conexion, "estimazon");
    if(isset($_GET["prod"])) {
        $idprod = $_GET["prod"];
        // información del producto
        $consulta = mysqli_query($conexion, "
        SELECT nombre, imagen, descripcion
        FROM producto
        WHERE idProducto = $idprod
        ");
        $producto = mysqli_fetch_array($consulta);

        // información del producto/vendedor
        $consulta_precios = mysqli_query($conexion, "
        SELECT idIVP, precio, stock, vendedor.nombre, info_vendedor_producto.nUsuarioVend AS vend
        FROM info_vendedor_producto
            JOIN 
                (SELECT nUsuario, nombre
                FROM vendedor) AS vendedor
            ON info_vendedor_producto.nUsuarioVend = vendedor.nUsuario
            AND info_vendedor_producto.idProducto = $idprod
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
    <link rel="stylesheet" type="text/css" href="css/productshow.css">
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
                    
                        echo "<form method='post' action='añadir_carrito.php' id=form-prod>";
                            echo "<input type='hidden' name='idIVP' value='".$p_fila_vendedores['idIVP']."'>";
                            echo "<input type='hidden' name='producto' value='".$idprod."'>";
                            echo "<input type='hidden' name='nUsuarioVend' value='".$p_fila_vendedores['vend']."'>";
                            echo '<label for="cantidad">Cantidad: </label>';
                            echo "<input type='number' min='1' max=".$p_fila_vendedores['stock']." value='1' id='cantidad' name='cantidad' required>";
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