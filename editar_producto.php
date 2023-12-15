<?php
    session_start();

    $conexion = mysqli_connect("localhost", "root", "");
    $bd = mysqli_select_db($conexion, "estimazon");

    // Verificar si se ha proporcionado un ID de producto en la URL
    if (!isset($_GET['idProducto'])) {

        echo "ID de producto no proporcionado.";
        exit();

    }

    $idProducto = $_GET['idProducto'];
    $nUsuario = $_SESSION['nombreUsuario'];

    // Verificar si el formulario se ha enviado
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // Procesar la edición del producto
        $nombre = $_POST["nombre"];
        $descripcion = $_POST['descripcion'];
        $imagen = $_POST['imagen'];
        $precio = $_POST['precio'];
        $stock = $_POST['stock'];

        // Actualizar la información del producto en la base de datos
        mysqli_query($conexion, "
            UPDATE producto
            SET nombre = '$nombre', descripcion = '$descripcion', imagen = '$imagen'
            WHERE idProducto = '$idProducto'
        ");

        mysqli_query($conexion, "
            UPDATE info_vendedor_producto
            SET precio = '$precio', stock = '$stock'
            WHERE idProducto = '$idProducto'
            AND  nUsuarioVend = '$nUsuario'
        ");

        echo '<p class="success-message">Producto actualizado correctamente.</p>';
        echo '<meta http-equiv="refresh" content="0.4;url=vendedor.php" />';

    }

    // Obtener información del producto basado en el ID y el vendedor
    $consulta = mysqli_query($conexion, "
        SELECT producto.idProducto, producto.nombre, producto.descripcion, producto.imagen, info_vendedor_producto.precio, info_vendedor_producto.stock
        FROM producto
        JOIN info_vendedor_producto ON producto.idProducto = info_vendedor_producto.idProducto
        WHERE producto.idProducto = '$idProducto'
        AND  nUsuarioVend = '$nUsuario'
    ");

    if ($producto = mysqli_fetch_array($consulta)) {
        // Formulario para editar el producto
?>

<!DOCTYPE html>
<html lang="es">
<head>

    <meta charset="UTF-8">
    <title>Editar Producto</title>
    <link rel="stylesheet" type="text/css" href="css/general.css">
    <link rel="stylesheet" type="text/css" href="css/cabecera.css">
    <link rel="stylesheet" type="text/css" href="css/editar_producto.css">


</head>
<body>

    <?php
        include "cabecera.php";
    ?>
    <div class="formulario">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?idProducto=" . $idProducto); ?>" method="post">

        <label for="nombre">Nombre del producto:</label>
        <input type="text" id="nombre" name="nombre" value="<?php echo $producto['nombre']; ?>" required>

        <label for="descripcion">Descripción:</label>
        <textarea name="descripcion" id="descrip-prod-tarea" required><?php echo $producto['descripcion']; ?></textarea>

        <label for="imagen">Imagen:</label>
        <input type="text" id="imagen" name="imagen" value="<?php echo $producto['imagen']; ?>" required>

        <label for="precio">Precio:</label>
        <input type="number" id="precio" name="precio" min=0.01 step=0.01 value="<?php echo $producto['precio']; ?>" required>

        <label for="stock">Stock:</label>
        <input type="number" id="stock" name="stock" min=1 step=1 value=1 value="<?php echo $producto['stock']; ?>" required>

        <button type="submit">Guardar cambios</button>
        </div>
    </form>

</body>
</html>

<?php

    } else {
        echo "Producto no encontrado.";
    }

?>