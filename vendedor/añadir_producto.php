<?php 
    session_start(); 
?>

<!DOCTYPE html>
<html lang="es">
<head>

    <meta charset="UTF-8">
    <title>Añadir Producto</title>
    <link rel="stylesheet" type="text/css" href="../css/añadir_producto.css">
    <link rel="stylesheet" type="text/css" href="../css/cabecera.css">
    <link rel="stylesheet" type="text/css" href="../css/general.css">

</head>
<body>

    <?php
        include "../cabecera.php";
    ?>

    <div id="form-añadir-prod-container">

        <form id=form-añadir-prod action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

            <label for="producto">Producto:</label>
            <input class="input-form" type="text" id="producto" name="producto" required>

            <label for="descripcion">Descripción:</label>
            <input class="input-form" type="text" id="descripcion" name="descripcion" required>

            <label for="imagen">Imagen:</label>
            <input class="input-form" type="text" id="imagen" name="imagen" required>

            <label for="precio">Precio:</label>
            <input class="input-form" type="text" id="precio" name="precio" required>

            <label for="stock">Stock:</label>
            <input class="input-form" type="number" id="stock" name="stock" required>

            
            <div class="contenedor-checkboxes">
                <?php 
                    $conexion = mysqli_connect("localhost", "root", "");
                    $bd = mysqli_select_db($conexion, "estimazon");

                    // Consulta de todas las categorías
                    $consulta = mysqli_query($conexion, "SELECT nombre FROM categoria");

                    while($fila = mysqli_fetch_array($consulta)){
                        echo '<div class="contenedor-checkbox">';
                        echo '<label class="labelCategoria" for="' . $fila['nombre'] . '">' . $fila['nombre'] . '</label>';
                        echo '<input class="input-form-cat" type="checkbox" id="' . $fila['nombre'] . '" name="categorias[]" value="' . $fila['nombre'] . '">';
                        echo '</div>';
                    }
                ?>
            </div>


            <div class="botonAñadir">
            <button type="submit">Añadir</button>
            </div>
        </form>

    </div>
    
    <?php
        
        $conexion = mysqli_connect("localhost","root","");
        $bd = mysqli_select_db($conexion, "estimazon");

        // Verificar si se ha enviado el formulario
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            
            // Recuperar los valores del formulario
            $nombre = $_POST["producto"];
            $descripcion = $_POST['descripcion'];
            $imagen = $_POST['imagen'];
            $stock = $_POST['stock'];
            $precio = $_POST['precio'];
            $categorias = $_POST['categorias'];
            $nombre_usuario = $_SESSION['nombreUsuario'];

            // Comprobar si ya existe el producto
            $consulta = mysqli_query($conexion, "
                SELECT idProducto 
                FROM producto 
                WHERE nombre = '$nombre'
            ");

            if ($fila = mysqli_fetch_array($consulta)){ 
                // El producto ya existe

                $consulta = mysqli_query($conexion, "
                    SELECT nombre, imagen, descripcion
                    FROM producto
                    JOIN (SELECT idProducto 
                        FROM INFO_VENDEDOR_PRODUCTO 
                        WHERE nUsuarioVend = '$nombre_usuario') as r_vend_prod
                    ON producto.idProducto = r_vend_prod.idProducto
                    WHERE nombre = '$nombre'
                ");

                // Se comprueba si el vendedor ya vende el producto
                if ($fila = mysqli_fetch_array($consulta)) {                
                    
                    echo '<p class="error-message">Ya vendes este producto.</p>';
                    echo '<meta http-equiv="refresh" content="0.4;url=vendedor.php" />';
                    
                } else {
                    // Aún no vende el producto

                    // Obtener idProducto
                    $consulta = mysqli_query($conexion, "
                        SELECT idProducto 
                        FROM producto 
                        WHERE nombre = '$nombre'
                    ");
                    $fila = mysqli_fetch_array($consulta);
                    $idProducto = $fila['idProducto'];

                    // Añadir a info_vendedor_producto
                    mysqli_query($conexion, "
                        INSERT INTO info_vendedor_producto 
                        ('$nombre_usuario', '$idProducto', '$precio', '$stock');
                    ");
                    
                }
                
            } else { 
                // El producto no existe

                // Añadir el producto
                $consulta = mysqli_query($conexion, "
                    INSERT INTO producto (nombre, descripcion, imagen) VALUES 
                    ('$nombre', '$descripcion', '$imagen');
                ");

                // Obtener idProducto
                $consulta = mysqli_query($conexion, "
                    SELECT idProducto 
                    FROM producto 
                    WHERE nombre = '$nombre'
                ");
                $fila = mysqli_fetch_array($consulta);
                $idProducto = $fila['idProducto'];

                // Añadir las categorías a las que pertenece
                foreach ($categorias as $categoria) {

                    // Obtener idCategoría
                    $consulta = mysqli_query($conexion, "
                        SELECT idCategoria 
                        FROM categoria 
                        WHERE nombre = '$categoria'
                    ");
                    $fila = mysqli_fetch_array($consulta);
                    $idCategoria = $fila['idCategoria'];

                    // Se añade a r_producto_categoria
                    mysqli_query($conexion, "
                        INSERT INTO 
                        r_producto_categoria(idProducto, idCategoria) VALUES 
                        ('$idProducto','$idCategoria');
                    ");

                }

                // Añadir a info_vendedor_producto
                mysqli_query($conexion, "
                    INSERT INTO info_vendedor_producto (nUsuarioVend, idProducto, precio, stock) VALUES 
                    ('$nombre_usuario', '$idProducto', '$precio', '$stock');
                ");

                echo '<p class="success-message">Producto añadido.</p>';
                echo '<meta http-equiv="refresh" content="0.4;url=vendedor.php" />';
            }

        }

    ?>

</body>
</html>