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
    <link rel="stylesheet" type="text/css" href="css/producto.css">
    <link rel="stylesheet" type="text/css" href="css/carrito.css">  
</head>
<body>
    <?php
        include "cabecera.php";
    ?>
    
    <div class="subpage">
            <h2 class="subtitulo">Proceso de pago</h2>
    </div>
    <div class=content>
        <div id=div-carrito>
            <?php
                if (isset($_POST['precio_total']) && isset($_SESSION['carrito']) && !empty($_SESSION['carrito'])) {
                    $precio_total = $_POST['precio_total'];
                    // mostrar formulario para introducir método de pago
                    echo "<div class='info-gen-carrito'>";
                        echo "<h3 class='precio-total'>Total: {$precio_total} €</h3>";
                        echo '<form method="post" action="pago.php">';
                            echo '<input type="hidden" name="precio_total" value="' . $precio_total . '">';
                            echo '<input type="text" name="tipo_tarjeta" value="VISA" readonly>';
                            echo '<input type="text" name="numero_tarjeta" placeholder="Número de tarjeta" pattern="\d{16}" title="Ingrese 16 números" required>';
                            echo '<input type="text" name="cvc" placeholder="CVC" pattern="\d{3}" title="Ingrese 3 números" required>';
                            echo '<input class="boton-pago" type="submit" value="Pagar" name="pagar">';
                        echo '</form>';
                    echo "</div>";
                } else {
                    echo "<h3>No hay productos en el carrito.</h3>";
                }
            ?>
        </div>
    </div>
</body>
</html>