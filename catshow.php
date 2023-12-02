<?php
    $conexion = mysqli_connect("localhost","root","");
    $bd = mysqli_select_db($conexion, "estimazon");
?>
<!DOCTYPE html>
<head>
    <title>Catalogo - Estimazon</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="estils.css?v=1.0.0">
</head>
<body>
    <div class="sup">
        <div id="Titulo">
            <a id="Titol" href=principal.php>Estimazon</a>
        </div>
    </div>
    <div class="subpage">
            <h1 class="subtitulo">Catálogo</h1>
    </div>
    <div class=content>
        <nav class="menu-categorias">
            <?php
                $conexion = mysqli_connect("localhost","root","");
                $bd = mysqli_select_db($conexion, "estimazon");
                $consulta = mysqli_query($conexion, "SELECT * FROM categoria ORDER BY nombre ASC");
                while ($fila = mysqli_fetch_array($consulta)) {
                    $link = "catshow.php?param=".$fila["idCategoria"];
                    echo "<a class='catlist' href= '$link'>".$fila['nombre']. "</a>";
                }
            ?>
        </nav>
        <div class=catalogo>
            <ul class=lista-prod>
                <?php
                    $conexion = mysqli_connect("localhost","root","");
                    $bd = mysqli_select_db($conexion, "estimazon");

                    if(isset($_GET["param"])) {
                        $idcat = $_GET["param"];

                        $consulta = mysqli_query($conexion, "
                        SELECT nombre, imagen, producto.idProducto
                        FROM producto
                        JOIN
                            (SELECT idProducto
                            FROM r_producto_categoria
                            WHERE idCategoria = $idcat) AS r_id_cat
                        ON producto.idProducto = r_id_cat.idProducto
                        ");
                        if($consulta)
                            while ($fila = mysqli_fetch_array($consulta)) {
                                echo "<li class='product-prev'>";
                                echo "<div>";
                                echo "<a class=enl-prod href='prodshow.php?param=".$fila['idProducto']."'>";
                                echo "<img src=".$fila['imagen']." class='imagen-prod'>";
                                echo $fila['nombre'];
                                echo "</a>";
                                #echo "<li class='product-prev'>".$fila['nombre']." "."</li>";
                                echo "</div>";
                                echo "</li>";
                            }
                        else{
                            echo "<p>No hay productos en esta categoria</p>";
                        }

                    }
                ?>
            </ul>
        </div>

    </div>
    
</body>
</html>