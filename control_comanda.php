<?php
    session_start();
    if (isset($_GET['com'])) {
        
        $idComanda = $_GET['com'];
        
        $conexion = mysqli_connect("localhost","root","");
        $bd = mysqli_select_db($conexion, "estimazon");

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
        <div>
            <h3> Información de la comanda</h3>

            <?php
                // información de estado
                echo "<p> Fecha: " . $comanda['fecha']. "</p>";
                echo "<p> Estado: " . $comanda['estado']. "</p>";

                // personas implicadas
                echo "<p> Comprador: " . $comanda['compN']. "</p>";

                // trato especial al repartidor (puede ser NULL)
                $nUsuarioRep = $comanda['nUsuarioRep'];
                if ($nUsuarioRep == NULL) {
                    // no hay repartidor
                    // añadir opción para elegir empresa distribuidora
                    // consulta de empresas distribuidoras
                    $consulta_distr = mysqli_query($conexion, "
                        SELECT distribuidora.nombre, distribuidora.idDistribuidora
                        FROM distribuidora
                            JOIN r_zona_distribuidora
                                JOIN zona_geografica
                                    JOIN poblacio
                                    ON zona_geografica.idZona = poblacio.idZona
                                    AND poblacio.idPobl =   (SELECT idPoblacio
                                                            FROM domicili
                                                            WHERE idDomicili = $comanda['domicili'])
                                ON r_zona_distribuidora.idZona = zona_geografica.idZona
                            ON distribuidora.idDistribuidora = r_zona_distribuidora.idDistribuidora
                    ");
                    // campo de seleccion
                    echo "<select name='distribuidora'>";
                    // añadir cada distribuidora al select
                    while($fila_distr = mysqli_fetch_array($consulta_distr)) {
                        echo "<option value='" . $fila_distr['idDistribuidora'] . "'>" . $$fila_distr['nombre'] . "</option>";
                    }
                } else {
                    echo "<p> Repartidor: " . $nUsuarioRep. "</p>";
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