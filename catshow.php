<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="css/estils.css">
    <link rel="stylesheet" type="text/css" href="css/cabecera.css">
    <link rel="stylesheet" type="text/css" href="css/general.css">
    <title>Catálogo - Estimazon</title>
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
        <div class=catalogo>
            <ul class=lista-prod>
                <?php
                    $conexion = mysqli_connect("localhost","root","");
                    $bd = mysqli_select_db($conexion, "estimazon");

                    if(isset($_GET["param"])) {
                        $idcat = $_GET["param"];
                        // consulta para obtener la información del producto
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
                                // mostrar información del producto
                                echo "<li class='product-prev'>";
                                // enlace a la página del producto
                                echo "<div>";
                                echo "<a class=enl-prod href='prodshow.php?prod=".$fila['idProducto']."'>";
                                echo "<img src=".$fila['imagen']." class='imagen-prod-cat'>";
                                echo $fila['nombre'];
                                echo "</a>";
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