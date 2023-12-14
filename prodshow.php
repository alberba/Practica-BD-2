<?php

    session_start();

    $conexion = mysqli_connect("localhost","root","");
    $bd = mysqli_select_db($conexion, "estimazon");

    // Se pasara como parámetro el producto a mostrar
    if(isset($_GET["prod"])) {

        $prod = $_GET["prod"];

    }else if(isset($_POST["prod"])){

        $prod = $_POST["prod"];

    }

        $idprod = $prod;

        // Información del producto
        $consulta = mysqli_query($conexion, "
            SELECT idProducto, nombre, imagen, descripcion
            FROM producto
            WHERE idProducto = $idprod
        ");

        $producto = mysqli_fetch_array($consulta);

        // Información del producto/vendedor
        $consulta_precios = mysqli_query($conexion, "
            SELECT idIVP, precio, stock, vendedor.nombre, info_vendedor_producto.nUsuarioVend AS vend
            FROM info_vendedor_producto
                JOIN 
                    (SELECT nUsuario, nombre
                    FROM vendedor) AS vendedor
                ON info_vendedor_producto.nUsuarioVend = vendedor.nUsuario
                AND info_vendedor_producto.idProducto = $idprod
                AND info_vendedor_producto.stock > 0
            WHERE idProducto = $idprod
            ORDER BY precio ASC
        ");

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
                        echo "<label for='vendedor'>Vendedor:</label>";

                        echo "<form method='post' action=".$_SERVER['PHP_SELF']."?prod=".$prod.">";

                            echo "<select id='vendedor' name='vendedor' required>";

                                while($vendedor = mysqli_fetch_array($consulta_precios)){
                                    
                                    // Cambia 'nombre_predeterminado' por el nombre del vendedor que quieres como predeterminado
                                    if ($_SERVER["REQUEST_METHOD"] == "POST" && $vendedor['nombre'] == $_POST['vendedor']) { 

                                        echo "<option value='" . htmlspecialchars($vendedor['nombre']) . "' selected>" . htmlspecialchars($vendedor['nombre']) . "</option>";

                                    } else {

                                        echo "<option value='" . htmlspecialchars($vendedor['nombre']) . "'>" . htmlspecialchars($vendedor['nombre']) . "</option>";

                                    }   

                                }

                            echo "</select><br>";

                            echo "<input type='submit' name='submit' value='Escoger vendedor'>";

                        echo "</form>";
                   
                        if ($_SERVER["REQUEST_METHOD"] == "POST") {

                            $selected_vendedor = $_POST['vendedor'];
                            echo $selected_vendedor;

                            // Obtener nUsuario del vendedor
                            $consulta = mysqli_query($conexion, "
                                SELECT nUsuario
                                FROM vendedor
                                WHERE nombre = '$selected_vendedor'
                            ");

                            $fila = mysqli_fetch_array($consulta);
                            $nUsuario = $fila['nUsuario'];
                            $idProd = $producto['idProducto'];

                            // Información del producto/vendedor
                            $consulta = mysqli_query($conexion, "
                                SELECT idIVP, precio, stock, idProducto, nUsuarioVend
                                FROM info_vendedor_producto
                                WHERE idProducto = '$idProd' AND nUsuarioVend = '$nUsuario'
                            ");

                            $fila = mysqli_fetch_array($consulta);
                          
                            echo "<h3>".$fila['precio']." €</h3>";

                            if ($fila['stock'] <= 10){
                                echo "<p id=stock> Sólo quedan ".$fila['stock']." unidades a este precio!</p>";
                            }
                        
                            echo "<form method='post' action='añadir_carrito.php' id=form-prod>";

                                echo "<input type='hidden' name='idIVP' value='".$fila['idIVP']."'>";
                                echo "<input type='hidden' name='producto' value='".$idprod."'>";
                                echo "<input type='hidden' name='nUsuarioVend' value='".$fila['nUsuarioVend']."'>";
                                echo "<input type='hidden' name='stock' value='".$fila['stock']."'>";
                                echo '<label for="cantidad">Cantidad: </label>';
                                echo "<input type='number' min='1' max=".$fila['stock']." value='1' id='cantidad' name='cantidad' required>";
                                
                                if(isset($_SESSION['nombreUsuario'])){
                                    echo '<input class=boton-compra type="submit" name="agregar" value="Agregar al carrito">';
                                }
                                
                            echo "</form>";
                        }           

                    ?>    

                </div>

            </div>

            <?php

                // Información de otros vendedores
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