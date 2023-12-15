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
            SELECT idComanda, fecha, estado, comprador.nombre AS compN, nUsuarioRep, idDomicilio
            FROM comanda
                JOIN comprador
                ON comprador.nUsuario = nUsuarioComp
            WHERE idComanda = $idComanda
        ");
        $comanda = mysqli_fetch_array($consulta);

        // comprobar si alguno de los productos de la comanda no ha sido entregado
        $consulta_entregados = mysqli_query($conexion, "
            SELECT info_vendedor_producto.idIVP, enAlmacen
            FROM info_vendedor_producto
            JOIN
                (SELECT idIVP, cantidad, enAlmacen
                FROM r_ipv_comanda
                WHERE idComanda = $idComanda) AS ipv_comanda
            ON info_vendedor_producto.idIVP = ipv_comanda.idIVP
            WHERE enAlmacen = FALSE
        ");
        $productos_entregados = mysqli_num_rows($consulta_entregados) == 0 ? TRUE : FALSE;
    }



?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../css/estils.css">
    <link rel="stylesheet" type="text/css" href="../css/cabecera.css">
    <link rel="stylesheet" type="text/css" href="../css/general.css">
    <link rel="stylesheet" type="text/css" href="../css/control_comanda.css">

    <?php
        echo "<title>Comanda ". $idComanda. " - Estimazon</title>"
    ?>

</head>
<body>

    <?php 
        include "../cabecera.php"; 
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
                    // Añadir opción para elegir empresa distribuidora si todos los productos están entregados
                    if ($productos_entregados) {
                        
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
                    }

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

                // Convertir la fecha de la comanda a un objeto DateTime
                $fechaComanda = new DateTime($comanda['fecha']);

                // Obtener la fecha actual
                $fechaActual = new DateTime();

                // Calcular la diferencia en días
                $diferencia = $fechaActual->diff($fechaComanda)->days;

                $consulta = mysqli_query($conexion, "
                    SELECT producto.nombre AS prod, imagen, cantidad, nUsuarioVend AS vend, idIVP, enAlmacen, aviso
                    FROM producto
                    JOIN
                        (SELECT nUsuarioVend, idProducto, cantidad, enAlmacen, aviso, info_vendedor_producto.idIVP
                        FROM info_vendedor_producto
                        JOIN
                            (SELECT idIVP, cantidad, enAlmacen, aviso
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

                                echo "<div class=vendedor-nombreProd-container>";

                                    echo "<p class=nomProd> " . $fila['prod'] . "</p>";

                                    echo "<div class=vendedor-container>";

                                        echo "<p class=vendedor>Vendedor: " . $fila['vend'] . "</p>";

                                        if ($diferencia >= 5 && $fila['aviso'] == FALSE && $fila['enAlmacen'] == FALSE) {

                                            echo "<form action='control_comanda.php?com=" . $idComanda . "' method='post'>";
                                                echo "<input type='hidden' name=vend value=" . $fila["vend"] . ">";
                                                echo "<input type='hidden' name=idIVP value=" . $fila["idIVP"] . ">";
                                                echo "<input type='submit' class='btn' name='btn-aviso' value='Poner aviso'>";
                                            echo "</form>";

                                        }
                                    
                                    echo "</div>";
                                
                                echo "</div>";

                                echo "<div class=almacen-cant-container>";

                                    echo "<div class='almacen-container'>";

                                        if ($fila['enAlmacen'] == TRUE) {
                                            echo "<p class=almacen>En almacén</p>";
                                        } else {
                                            echo "<p class=almacen>Pendiente</p>";
                                            echo "<form method='post' action='control_comanda.php?com=" . $idComanda . "'>";
                                                echo "<input type='hidden' name=idIVP value=" . $fila["idIVP"] . ">";
                                                echo "<input type='submit' class='btn' name=boton-almacen value='Llegó al almacen'>";
                                            echo "</form>";
                                        }

                                    echo "</div>";

                                    echo "<p class=cant-producto>Cantidad: " . $fila['cantidad'] . "</p>";
                                
                                echo "</div>";

                            echo "</div>";

                        echo "</div>";
                    
                    echo "</div>";

                    // Convertir la fecha de la comanda a un objeto DateTime
                    $fechaComanda = new DateTime($comanda['fecha']);

                    // Obtener la fecha actual
                    $fechaActual = new DateTime();

                    // Calcular la diferencia en días
                    $diferencia = $fechaActual->diff($fechaComanda)->days;

                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    
                        if (isset($_POST["boton-almacen"])) {
                            // Se ha pulsado el botón de poner aviso
                            
                            // Obtener el idIVP del producto
                            $idIVP = $_POST["idIVP"];

                            $consulta = mysqli_query($conexion, "
                                UPDATE r_ipv_comanda
                                SET enAlmacen = TRUE
                                WHERE idIVP = '$idIVP'
                            ");

                            header("Location: control_comanda.php?com=" . $idComanda);

                        } elseif (isset($_POST["btn-aviso"])) {
                            // obtener el id de la comanda, del vendedor y el idIVP
                            $idComanda = $_GET['com'];
                            $usuarioVend = $_POST['vend'];
                            $idIVP = $_POST['idIVP'];

                            // Código para actualizar la tabla
                            $actualizar_vend = mysqli_query($conexion, "
                                UPDATE vendedor
                                SET numAvisos = numAvisos + 1
                                WHERE nUsuario = '$usuarioVend'
                            ");

                            $actualizar_idIVP = mysqli_query($conexion, "
                                UPDATE r_ipv_comanda
                                SET aviso = TRUE
                                WHERE idIVP = $idIVP
                                AND idComanda = $idComanda
                            ");

                            header("Location: control_comanda.php?com=" . $idComanda);

                        }

                    }       

                }

            ?>

        </div>
        
    </div>

</body>
</html>

