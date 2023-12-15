<?php

    session_start();

    // Comprobar que se ha pasado el id de la comanda por parámetro
    if (isset($_GET['com'])) {

        $idComanda = $_GET['com'];

        $conexion = mysqli_connect("localhost","root","");
        $bd = mysqli_select_db($conexion, "estimazon");

        // Comprobar si se ha enviado el formulario
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['asignarDistr'])) {

            $idDistribuidora = $_POST['distribuidora'];
            
            // Elegir repartidor al azar
            $consulta_rep = mysqli_query($conexion, "CALL setRepartidor($idDistribuidora, $idComanda)");

            // Redirigir a la página actual para evitar reenvío de formulario
            header("Location: ". htmlspecialchars($_SERVER["PHP_SELF"]). "?com=" . $idComanda);

        }

        // Obtener información de la comanda
        $consulta = mysqli_query($conexion, "
            SELECT fecha, estado, comprador.nombre AS compN, nUsuarioRep, idDomicilio
            FROM comanda
                JOIN comprador
                ON comprador.nUsuario = nUsuarioComp
            WHERE idComanda = $idComanda
        ");
        $comanda = mysqli_fetch_array($consulta);
    }

    if ($comanda) {

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="css/estils.css">
    <link rel="stylesheet" type="text/css" href="css/cabecera.css">
    <link rel="stylesheet" type="text/css" href="css/general.css">
    <link rel="stylesheet" type="text/css" href="css/control_comanda.css">

    <?php
        echo "<title>Comanda ". $idComanda. " - Estimazon</title>"
    ?>

</head>
<body>

    <?php 
        include "cabecera.php"; 
    ?>

    <div class="subpage">

        <?php
            $nUsuarioControlador = $_SESSION['nombreUsuario'];
            echo "<h2 class='subtitulo'> Comanda ". $idComanda. "</h2>"
        ?>

    </div>

    <div class="content">

        <div id=info-comanda>

            <h3> Información de la comanda</h3>

            <?php

                // Información de estado
                echo "<p> Fecha: " . $comanda['fecha']. "</p>";
                echo "<p> Estado: " . $comanda['estado']. "</p>";

                // Personas implicadas
                echo "<p> Comprador: " . $comanda['compN']. "</p>";

                // Trato especial al repartidor (puede ser NULL)
                $nUsuarioRep = $comanda['nUsuarioRep'];
                $idDom = $comanda['idDomicilio'];
                if ($nUsuarioRep == NULL) {
                    // No hay repartidor
                    // Añadir opción para elegir empresa distribuidora

                    // Consulta de empresas distribuidoras
                    $consulta_distr = mysqli_query($conexion, "
                        SELECT distribuidora.nombre, distribuidora.idDistribuidora
                        FROM distribuidora
                        JOIN r_zona_distribuidora
                            JOIN zona_geografica
                                JOIN poblacion
                                ON zona_geografica.idZona = poblacion.idZona
                                AND poblacion.idPoblacion =     (SELECT idPoblacion
                                                                FROM domicilio
                                                                WHERE idDomicilio = $idDom)
                            ON r_zona_distribuidora.idZona = zona_geografica.idZona
                        ON distribuidora.idDistribuidora = r_zona_distribuidora.idDistribuidora
                    ");

                    echo "<form method='post' action=". htmlspecialchars($_SERVER["PHP_SELF"]). "?com=" . $idComanda. ">";

                        // Campo de seleccion
                        echo "<select name='distribuidora'>";

                            // Añadir cada distribuidora al select
                            while($fila_distr = mysqli_fetch_array($consulta_distr)) {

                                echo "<option value='" . $fila_distr['idDistribuidora'] . "'>" . $fila_distr['nombre'] . "</option>";

                            }

                        echo "</select>";

                        echo "<input type='hidden' name='idCom' value='". $idComanda. "'>";
                        echo "<input type='submit' value='Asignar distribuidora' name='asignarDistr'>";

                    echo "</form>";

                } else {

                    // Hay repartidor asignado
                    $consulta_rep = mysqli_query($conexion, "
                        SELECT nombre
                        FROM repartidor
                        WHERE nUsuario = '$nUsuarioRep'
                    ");
                    $fila = mysqli_fetch_array($consulta_rep);
                    echo "<p> Repartidor: " . $fila['nombre']. "</p>";

                }

            ?>

        </div>

        <div id=lista-prod>
        
            <?php

                $consulta = mysqli_query($conexion, "
                    SELECT producto.nombre AS prod, imagen, i_prod.cantidad, i_prod.nUsuarioVend AS vend
                    FROM producto
                    JOIN
                        (SELECT nUsuarioVend, idProducto, cantidad
                        FROM info_vendedor_producto
                        JOIN
                            (SELECT idIVP, cantidad
                            FROM r_ipv_comanda
                            WHERE idComanda = $idComanda) AS ipv_comanda
                        ON info_vendedor_producto.idIVP = ipv_comanda.idIVP) AS i_prod
                    ON producto.idProducto = i_prod.idProducto
                ");

                while ($fila = mysqli_fetch_array($consulta)) {

                    echo "<div class='producto-lista'>";

                        echo "<div class='imagen-descripcion-prod'>";

                            echo "<div class='imagen-prod-container'>";

                                echo "<img src='" . $fila['imagen'] . "' alt='" . $fila['prod'] . "' class='imagen-prod'>";

                            echo "</div>";

                            echo "<div class='descripcion-prod'>";

                                echo "<p> " . $fila['prod'] . "</p>";

                                echo "<p class=info-prod-carrito>Vendedor: " . $fila['vend'] . "</p>";

                                echo "<p class=info-prod-carrito>Cantidad: " . $fila['cantidad'] . "</p>";

                            echo "</div>";

                        echo "</div>";
                    
                    echo "</div>";

                }

            ?>

        </div>
        
    </div>

</body>
</html>

<?php

    } else {
        echo "Producto no encontrado.";
    }

?>