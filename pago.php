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
            <h2 class="subtitulo">Fin de la compra</h2>
    </div>
    <div class=content>
        <div id=div-carrito>
            <?php
                // comprobamos que se ha enviado el formulario correctamente
                if (isset($_POST['pagar']) && isset($_SESSION['carrito']) && !empty($_SESSION['carrito'])) {
                    $numT = $_POST['numero_tarjeta'];
                    $fecha = date("Y-m-d");
                    $nUsuarioComp = $_SESSION['nombreUsuario'];
                    $nUsuarioCont = NULL;
                    $idDomicilio = $_POST['domicilio'];

                    // comenzar transacción (solo alteraremos la base de datos en caso
                    // de que todas las modificaciones sean exitosas)
                    mysqli_begin_transaction($conexion);
                    
                    try {
                        // obtener usuario de controlador aleatoriamente
                        $consulta_cont = mysqli_query($conexion, "
                                SELECT nUsuario
                                FROM controlador
                                ");
                        $consulta_num_cont = mysqli_query($conexion, "
                        SELECT count(nUsuario) AS n
                        FROM controlador
                        ");

                        $n = mysqli_fetch_array($consulta_num_cont);
                        $elegido = rand(1, $n['n']);

                        $fila = mysqli_fetch_array($consulta_cont);
                        for ($i=1; $i <= $elegido; $i++) { 
                            $fila = mysqli_fetch_array($consulta_cont);
                        }
                        $nUsuarioCont = $fila['nUsuario'];         
                        
                        // esto habrá que quitarlo
                        //$idDomicilio = 1;

                        // generamos la comanda
                        $ret = mysqli_query($conexion, "
                            INSERT INTO comanda(fecha, nTarjeta, idDomicilio, nUsuarioComp, nUsuarioCont) VALUES
                            ('$fecha', '$numT', $idDomicilio, '$nUsuarioComp', '$nUsuarioCont');
                            ");

                        // obtener idComanda
                        $idComanda = mysqli_insert_id($conexion);

                        if ($ret === false) {
                            throw new Exception("Error: No se puedo crear la comanda.");
                        }

                        // restar stock de los productos y añadirlos a producto_comanda
                        foreach ($_SESSION['carrito'] as $idProducto => $detallesProducto) {
                            // obtenemos valores de cantidad e idVendedor
                            $cantidad = $detallesProducto['cantidad'];
                            $nUsuarioVend = $detallesProducto['nUsuarioVend'];

                            // consulta del stock
                            $consulta_stock = mysqli_query($conexion, "
                                SELECT stock
                                FROM r_vendedor_producto
                                WHERE idProducto = $idProducto AND nUsuarioVend = '$nUsuarioVend'
                                ");
                            
                            // obtener stock tras compra
                            $fila = mysqli_fetch_array($consulta_stock);
                            $stock_f = $fila['stock'] - $cantidad;

                            if ($stock_f < 0) {
                                // stock negativo, no es posible
                                throw new Exception("Error: La cantidad a restar excede el stock disponible.");
                            } else {
                                // stock positivo, actualizamos (no se lleva a cabo hasta el commit)
                                mysqli_query($conexion, "
                                    UPDATE r_vendedor_producto
                                    SET stock = $stock_f
                                    WHERE idProducto = $idProducto AND nUsuarioVend = '$nUsuarioVend'
                                    ");
                            }

                            // añadir producto a producto_comanda
                            $ret = mysqli_query($conexion, "
                                INSERT INTO r_producto_comanda(idProducto,idComanda,cantidad) VALUES
                                ($idProducto, $idComanda, $cantidad);
                                ");

                            if ($ret === false) {
                                throw new Exception("Error: No se puedo meter un producto en la comanda.");
                            }
                        }

                        // acabar transacción (todas las operaciones tuvieron éxito)
                        mysqli_commit($conexion);
                        // Limpiar el carrito tras completar la transacción
                        unset($_SESSION['carrito']);
                        echo "<h3>La transacción se completó exitosamente</h3>";
                    
                    } catch (Exception $e) {
                        // Ha habido un error, revertir los cambios
                        mysqli_rollback($conexion);
                        echo "<h3>La transacción falló.</h3>";
                    }

                } else {
                    echo "<h3>No hay productos en el carrito.</h3>";
                }
            ?>
        </div>
    </div>
</body>
</html>