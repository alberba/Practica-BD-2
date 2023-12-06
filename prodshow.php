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
        SELECT precio, stock, nombre
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
<head>
    <?php
        echo "<title>".$producto['nombre']." - Estimazon</title>";
    ?>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="css/estils.css">
    <link rel="stylesheet" type="text/css" href="css/cabecera.css">
    <link rel="stylesheet" type="text/css" href="css/general.css">
    <link rel="stylesheet" type="text/css" href="css/producto.css">
</head>
<body>
    <div class="sup">
        <div id="Titulo">
            <a id="Titol" href=principal.php>
                <h1>Estimazon</h1>
            </a>
        </div>
    </div>
    <div class="subpage">
            <h2 class="subtitulo">Catálogo</h2>
    </div>
    <div class=content>
        <nav class="menu-categorias">
            <?php
                $consulta_cat = mysqli_query($conexion, "SELECT * FROM categoria ORDER BY nombre ASC");
                while ($fila = mysqli_fetch_array($consulta_cat)) {
                    $link = "catshow.php?param=".$fila["idCategoria"];
                    echo "<a class='catlist' href= '$link'>".$fila['nombre']. "</a>";
                }
            ?>
        </nav>
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
                        echo "<p id=stock> Faltan ".$p_fila_vendedores['stock']." unidades</p>";
                    ?>
                </div>
            </div>
            <div id=otros-vend>
                <p id=tit-otros-vend>
                    Otros vendedores
                </p>
                <ul>
                    <?php
                        while ($p_fila_vendedores = mysqli_fetch_array($consulta_precios)) {
                            echo "<li>".$p_fila_vendedores['nombre']." ".$p_fila_vendedores['precio']." €</li>";
                        }
                    ?>
                </ul>
            </div>
        </div>

    </div>
    
</body>
</html>