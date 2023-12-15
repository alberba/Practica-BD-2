<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../css/estils.css">
    <link rel="stylesheet" type="text/css" href="../css/cabecera.css">
    <link rel="stylesheet" type="text/css" href="../css/general.css">
    <link rel="stylesheet" type="text/css" href="../css/producto.css">
    <link rel="stylesheet" type="text/css" href="../css/vendedor.css">
</head>
<body>

<?php
    session_start();
    include "../cabecera.php";
?>

<div class="subpage">
    <h2 class="subtitulo">Menú</h2>
</div>

<div class="mensaje-bienvenida">
    <p>Bienvenido a la sección de los vendedores. <br>
    Aquí puedes agregar o modificar un producto existente.</p>
</div>

<div id=menu-container>
    <div class="boton-container">
        <a class="boton" href="añadir_producto.php">
            <img src="../imagenes/producto+.png" alt="Añadir producto">
            <p>Añadir un producto<br> nuevo</p>
        </a>
    </div>

    <div class="boton-container">
        <a class="boton" href="añadir_producto_existente.php">
            <img id=img-vend src="../imagenes/carrito.png" alt="Añadir producto existente">
            <p>Vender producto<br> existente</p>
        </a>
    </div>

    <div class="boton-container">
        <a class="boton" href="modificar_producto.php">
            <img id=img-mod src="../imagenes/editar.png" alt="Modificar producto">
            <p>Modificar un <br>producto</p>
        </a>
    </div>
</div>

</body>
</html>