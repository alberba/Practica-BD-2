<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Añadir Producto</title>
    <link rel="stylesheet" type="text/css" href="css/añadir_producto.css">

</head>
<body>
<div class="sup">
    <div class=titulo-sup>
       
            <h1>Estimazon</h1>
        </a>
    </div>
    </div>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="producto">Producto:</label>
        <input type="text" id="producto" name="producto" required>

        <label for="descripcion">Descripción:</label>
        <input type="text" id="descripcion" name="descripcion" required>

        <label for="imagen">Imagen:</label>
        <input type="text" id="imagen" name="imagen" required>

        <label for="precio">Precio:</label>
        <input type="text" id="precio" name="precio" required>

        <label for="stock">Stock:</label>
        <input type="number" id="stock" name="stock" required>

        
        <div class="contenedor-checkboxes">
            <?php 
                $conexion = mysqli_connect("localhost","root","");
                $bd = mysqli_select_db($conexion, "estimazon");
                $consulta = mysqli_query($conexion, "SELECT nombre FROM categoria");
                
                while($fila = mysqli_fetch_array($consulta)){
                    echo '<input type="checkbox" id="' . $fila['nombre'] . '" name="categorias[]" value="' . $fila['nombre'] . '">';
                    echo '<label for="' . $fila['nombre'] . '">' . $fila['nombre'] . '</label>';
                }
            ?>
        </div>

        <button type="submit">Añadir</button>
    </form>

    <?php
        
        $conexion = mysqli_connect("localhost","root","");
        $bd = mysqli_select_db($conexion, "estimazon");

        if(isset($_POST['producto']) && isset($_POST['descripcion']) && isset($_POST['imagen']) && isset($_POST['stock']) && isset($_POST['precio']) && isset($_POST['categorias'])) {
            
            $nombre = $_POST["producto"];
            $descripcion = $_POST['descripcion'];
            $imagen = $_POST['imagen'];
            $stock = $_POST['stock'];
            $precio = $_POST['precio'];
            $categorias = $_POST['categorias'];
            $nombre_usuario = $_SESSION['nombreUsuario'];

            //comprobar si ya existe el producto
            $consulta = mysqli_query($conexion, "SELECT idProducto FROM producto WHERE nombre = '$nombre'");

            if ($fila = mysqli_fetch_array($consulta)){ //el producto ya existe

                //se comprueba si el vendedor ya vende el producto
                $consulta = mysqli_query($conexion, "
                SELECT nombre, imagen, descripcion
                FROM producto
                JOIN (SELECT idProducto 
                    FROM INFO_VENDEDOR_PRODUCTO 
                    WHERE nUsuarioVend = '$nombre_usuario') as r_vend_prod
                ON producto.idProducto = r_vend_prod.idProducto
                WHERE nombre = '$nombre'
                ");

                if ($fila = mysqli_fetch_array($consulta)) {                
                    
                    echo '<p class="error-message">Ya vendes este producto.</p>';
                    echo '<meta http-equiv="refresh" content="0.4;url=vendedor.php" />';
                    
                } else {

                    //obtener idProducto
                    $consulta = mysqli_query($conexion, "
                    SELECT idProducto 
                    FROM producto 
                    WHERE nombre = '$nombre'");
                    $fila = mysqli_fetch_array($consulta);
                    $idProducto = $fila['idProducto'];

                    //añadir a info_vendedor_producto
                    mysqli_query($conexion, "
                    INSERT INTO info_vendedor_producto 
                    ('$nombre_usuario', '$idProducto', '$precio', '$stock');");
                }
                
            } else { //el producto no existe

                //añadir el producto
                $consulta = mysqli_query($conexion, "
                INSERT INTO producto (nombre, descripcion, imagen) VALUES 
                ('$nombre', '$descripcion', '$imagen');");

                //obtener idProducto
                $consulta = mysqli_query($conexion, "
                SELECT idProducto 
                FROM producto 
                WHERE nombre = '$nombre'");
                $fila = mysqli_fetch_array($consulta);
                $idProducto = $fila['idProducto'];

                //añadir las categorías a las que pertenece
                foreach ($categorias as $categoria) {

                    //obtener idCategoría
                    $consulta = mysqli_query($conexion, "
                    SELECT idCategoria 
                    FROM categoria 
                    WHERE nombre = '$categoria'");
                    $fila = mysqli_fetch_array($consulta);
                    $idCategoria = $fila['idCategoria'];

                    //se añade a r_producto_categoria
                    mysqli_query($conexion, "
                    INSERT INTO 
                    r_producto_categoria(idProducto, idCategoria) VALUES 
                    ('$idProducto','$idCategoria');");
                }

                //añadir a info_vendedor_producto
                mysqli_query($conexion, "
                INSERT INTO info_vendedor_producto (nUsuarioVend, idProducto, precio, stock) VALUES 
                ('$nombre_usuario', '$idProducto', '$precio', '$stock');");

                echo '<p class="success-message">Producto añadido.</p>';
                echo '<meta http-equiv="refresh" content="0.4;url=vendedor.php" />';
            }
        }
    ?>
</body>
</html>