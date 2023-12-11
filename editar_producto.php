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

// Verificar si el formulario se ha enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Procesar la edición del producto
    $nombre = $_POST["nombre"];
    $descripcion = $_POST['descripcion'];
    $imagen = $_POST['imagen'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];

    // Validar y procesar los datos (aquí debes agregar las validaciones necesarias)

    // Actualizar la información del producto en la base de datos
    mysqli_query($conexion, "
        UPDATE producto
        SET nombre = '$nombre', descripcion = '$descripcion', imagen = '$imagen'
        WHERE idProducto = '$idProducto'
    ");

    mysqli_query($conexion, "
        UPDATE r_vendedor_producto
        SET precio = '$precio', stock = '$stock'
        WHERE idProducto = '$idProducto'
    ");

    echo '<p class="success-message">Producto actualizado correctamente.</p>';
    echo '<meta http-equiv="refresh" content="0.4;url=vendedor.php" />';
}

// Obtener información del producto basado en el ID
$consulta = mysqli_query($conexion, "
    SELECT producto.idProducto, producto.nombre, producto.descripcion, producto.imagen, r_vendedor_producto.precio, r_vendedor_producto.stock
    FROM producto
    JOIN r_vendedor_producto ON producto.idProducto = r_vendedor_producto.idProducto
    WHERE producto.idProducto = '$idProducto'
");

if ($producto = mysqli_fetch_array($consulta)) {
    // Formulario para editar el producto
    ?>

    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Editar Producto</title>
        <link rel="stylesheet" type="text/css" href="css/añadir_producto.css">

    </head>
    <body>

    <div class="sup">
        <div class=titulo-sup>
            <h1>Estimazon</h1>
        </div>
    </div>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?idProducto=" . $idProducto); ?>" method="post">
        <label for="nombre">Nombre del producto:</label>
        <input type="text" id="nombre" name="nombre" value="<?php echo $producto['nombre']; ?>" required>

        <label for="descripcion">Descripción:</label>
        <textarea id="descripcion" name="descripcion" required><?php echo $producto['descripcion']; ?></textarea>

        <label for="imagen">Imagen:</label>
        <input type="text" id="imagen" name="imagen" value="<?php echo $producto['imagen']; ?>" required>

        <label for="precio">Precio:</label>
        <input type="text" id="precio" name="precio" value="<?php echo $producto['precio']; ?>" required>

        <label for="stock">Stock:</label>
        <input type="number" id="stock" name="stock" value="<?php echo $producto['stock']; ?>" required>

        

        <button type="submit">Guardar cambios</button>
    </form>

    </body>
    </html>

<?php
} else {
    echo "Producto no encontrado.";
}
?>