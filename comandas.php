<?php
    $conexion = mysqli_connect("localhost","root","");
    $bd = mysqli_select_db($conexion, "estimazon");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="css/estils.css">
    <link rel="stylesheet" type="text/css" href="css/cabecera.css">
    <link rel="stylesheet" type="text/css" href="css/general.css">
    <title>Comandas - Estimazon</title>
</head>
<body>
    <?php include "cabecera.php"; ?>

    <div class="subpage">
        <h2 class="subtitulo">Comandas Activas</h2>
    </div>

    <div class="content">
        <?php
            $nUsuarioControlador = $_SESSION['nombreUsuario'];

            $consulta = mysqli_query($conexion, "
                SELECT C.*
                FROM COMANDA C
                WHERE C.nUsuarioCont = '$nUsuarioControlador'
                    AND NOT EXISTS (
                        SELECT 1
                        FROM INCIDENCIA I
                        WHERE I.idComanda = C.idComanda
                            AND I.idTIncidencia = (SELECT idTIncidencia FROM TIPO_INCIDENCIA WHERE nombre = 'Entregado al comprador')
                    )
                    AND DATEDIFF(NOW(), C.fecha) <= 15
            ");

            if ($consulta) {
                echo "<ul class='lista-com'>";
                while ($comanda = mysqli_fetch_array($consulta)) {
                    echo "<li class='com-prev'>";
                    // enlace a la página de la comanda
                        echo "<div>";
                            echo "<a class='enl-com' href='control_comanda.php?com=" . $comanda['idComanda'] . "'>";
                                echo "<p class='text-com'> ID Comanda: " . $comanda['idComanda']. "</p>";
                                echo "<p class='text-com'> Fecha: " . $comanda['fecha']. "</p>";
                                echo "<p class='text-com'> Número de Tarjeta: " . $comanda['nTarjeta']. "</p>";
                            echo "</a>";
                        echo "</div>";
                    echo "</li>";
                }
                echo "</ul>";
            } else {
                echo "<p>No hay comandas activas.</p>";
            }

            // Cerrar conexión
            mysqli_close($conexion);
        ?>
    </div>

</body>
</html>