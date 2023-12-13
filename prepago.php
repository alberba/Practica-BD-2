<?php
    session_start();
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
    <link rel="stylesheet" href="css/domicilios.css">  
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
                if (isset($_POST['precio_total'])){
                    $_SESSION['precio_total'] = $_POST['precio_total'];
                    $precio_total = $_POST['precio_total'];
                }else{
                    $precio_total = $_SESSION['precio_total'];
                }
                if (isset($_SESSION['carrito']) && !empty($_SESSION['carrito'])) {
                    
                    $nUsuarioComp = $_SESSION['nombreUsuario'];

                    // consulta de los domicilios del usuario
                    $consulta = mysqli_query($conexion, "
                        SELECT domicilio.idDomicilio, direccion, cp
                        FROM domicilio
                        JOIN	(SELECT idDomicilio
                                FROM r_comprador_domicilio
                                WHERE nUsuarioComp = '$nUsuarioComp') AS r
                        ON r.idDomicilio = domicilio.idDomicilio
                        ");

                    // mostrar formulario para introducir método de pago
                    echo "<div class='info-gen-carrito'>";
                        
                        if ($domicilios = mysqli_fetch_array($consulta)) {

                            echo '<form method="post" action="pago.php">';
                                echo '<input type="hidden" name="precio_total" value="' . $precio_total . '">';

                                echo '<input type="text" name="tipo_tarjeta" value="VISA" readonly>';

                                echo '<input type="text" name="numero_tarjeta" placeholder="Número de tarjeta" pattern="\d{16}" title="Ingrese 16 números" required>';
                                
                                echo '<input type="text" name="cvc" placeholder="CVC" pattern="\d{3}" title="Ingrese 3 números" required>';

                                echo "<div class= 'div-but-mod'>";
                                    echo '<select name="domicilio" required>';
                                    do {
                                        echo "<option value=\"" . $domicilios['idDomicilio'] . "\">" . $domicilios['direccion'] . " - " . $domicilios['cp'] . "</option>";
                                    } while ($domicilios = mysqli_fetch_array($consulta));
                                    echo '</select>';
                                    echo '<a class="link" href="añadir_domicilios.php?llamador=prepago"> Añadir nuevo domicilio </a>';
                                echo "</div>";

                                echo "<h3 class='precio-total'>Total: {$precio_total} €</h3>";

                                echo '<input class="button" type="submit" value="Pagar" name="pagar">';
                            echo '</form>';        
                        
                        } else {
                            echo '<a class="link" href="añadir_domicilios.php?llamador=prepago"> Añadir domicilios </a>';
                            echo '<h4> No se puede realizar el proceso de compra hasta no haber registrado al menos un domicilio </h4>';
                        }

                    echo "</div>";
                } else {
                    echo "<h3>No hay productos en el carrito.</h3>";
                }
            ?>
        </div>
    </div>
</body>
</html>