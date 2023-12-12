<?php
    session_start();

    if (isset($_POST['cantidad']) && $_POST['cantidad'] > 0) {
        // asignar datos a variables locales
        $idIVP = $_POST['idIVP'];
        $cantidad = $_POST['cantidad'];
        $producto = $_POST['producto'];
        $nUsuarioVend = $_POST['nUsuarioVend'];
        
        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = array();
        }

        // Verificar si el producto ya está en el carrito
        if (isset($_SESSION['carrito'][$idIVP])) {
            // Si el producto ya existe, añadir la cantidad
            $_SESSION['carrito'][$idIVP]['cantidad'] += $cantidad;
        } else {
            // Si no existe, añadirlo al carrito
            $_SESSION['carrito'][$idIVP] = array(
                'producto' => $producto,
                'cantidad' => $cantidad,
                'nUsuarioVend' => $nUsuarioVend
            );
        }
    }
    header('Location: catshow.php');
    exit();
?>
