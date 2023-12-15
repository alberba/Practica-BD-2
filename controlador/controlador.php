<?php
    session_start();
    $conexion = mysqli_connect("localhost","root","");
    $bd = mysqli_select_db($conexion, "estimazon");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../css/estils.css">
    <link rel="stylesheet" type="text/css" href="../css/cabecera.css">
    <link rel="stylesheet" type="text/css" href="../css/general.css">
    <link rel="stylesheet" type="text/css" href="../css/controlador.css">
    <title>Comandas - Estimazon</title>
</head>
<body>
    <?php include "../cabecera.php"; ?>

    <div class="subpage-cont">
        <h2 class="subtitulo">Comandas Activas</h2>

        <div class="div-cont">
            <a href="alta_ctrl_rep.php" class="enl-cont">Dar de alta usuarios</a>
        </div>
    </div>

    <div class="content">
        <?php
            $nUsuarioControlador = $_SESSION['nombreUsuario'];

            $consulta = mysqli_query($conexion, "
                SELECT comanda.idComanda, comanda.fecha, comanda.estado
                FROM comanda
                WHERE comanda.estado NOT IN ('Entregado', 'Devuelto', 'Rechazado')
                AND comanda.nUsuarioCont = '$nUsuarioControlador'
            ");

            if ($consulta) {
                echo "<div class='div-com'>";
                    echo "<ul class='lista-com'>";
                    while ($comanda = mysqli_fetch_array($consulta)) {
                        echo "<li class='com-prev'>";
                        // enlace a la página de la comanda
                            echo "<a class='enl-com' href='control_comanda.php?com=" . $comanda['idComanda'] . "'>";
                                echo "<p class='text-com comanda-text'> ID Comanda: " . $comanda['idComanda']. "</p>";
                                echo "<p class='text-com'> Fecha: " . $comanda['fecha']. "</p>";
                                echo "<p class='text-com'> Estado: " . $comanda['estado']. "</p>";
                            echo "</a>";

                        echo "</li>";
                    }
                    echo "</ul>";
                echo "</div>";
            } else {
                echo "<p>No hay comandas activas.</p>";
            }

            // Cerrar conexión
            mysqli_close($conexion);
        ?>
    </div>

</body>
</html>