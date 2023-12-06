<?php
if (isset($_POST['añadir-carrito'])) {
    // asignar datos a variables locales
    $cantidad = $_POST['cantidad'];
    $producto = $_GET['producto'];
    $idVendedor = $_GET['idVendedor'];
    
    if (!isset($_SESSION['carrito'])) {
        $_SESSION['carrito'] = array();
    }

    // Verificar si el producto ya está en el carrito
    if (isset($_SESSION['carrito'][$producto])) {
        // Si el producto ya existe, añadir la cantidad
        $_SESSION['carrito'][$producto]['cantidad'] += $cantidad;
    } else {
        // Si no existe, añadirlo al carrito
        $_SESSION['carrito'][$producto] = array(
            'cantidad' => $cantidad,
            'idVendedor' => $idVendedor
        );
    }
}

header('Location: catshow.php');
exit;
?>
