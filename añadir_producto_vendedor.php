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
$nVend = $_SESSION['nombreUsuario'];


// Verificar si el formulario se ha enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Procesar la edición del producto
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];

    // Crear la información del producto para este vendedor en la base de datos
    try {
        mysqli_query($conexion, "
            INSERT INTO r_vendedor_producto(idProducto, nUsuarioVend, precio, stock) VALUES
            ($idProducto, '$nVend', $precio, $stock);
        ");

        echo '<p class="success-message">Producto añadido correctamente.</p>';
    } catch (Exception $e) {
        echo '<p>Producto no añadido.</p>';
    }

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
    // Formulario para añadir ocurrencia vendedor-producto
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
        <div id="Titulo">
            <h1>Estimazon</h1>
        </div>
    </div>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?idProducto=" . $idProducto); ?>" method="post">
        <label for="precio">Precio:</label>
        <input type="text" id="precio" name="precio" value="<?php echo $producto['precio']; ?>" required>

        <label for="stock">Stock:</label>
        <input type="number" id="stock" name="stock" value="<?php echo $producto['stock']; ?>" required>

        <button type="submit">Añadir producto</button>
    </form>

    </body>
    </html>

<?php
} else {
    echo "Producto no encontrado.";
}
?>