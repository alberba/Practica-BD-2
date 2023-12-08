<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <label for="producto">Producto: </label>
        <input type="text" id="producto" name="producto"><br>

        <label for="descripcion">Descripción: </label>
        <input type="text" id="descripcion" name="descripcion"><br>

        <label for="imagen">Imagen: </label>
        <input type="text" id="imagen" name="imagen"><br>

        <label for="precio">Precio: </label>
        <input type="text" id="precio" name="precio"><br>

        <label for="stock">Stock: </label>
        <input type="number" id="stock" name="stock"><br><br>

        <?php 
            $conexion = mysqli_connect("localhost","root","");
            $bd = mysqli_select_db($conexion, "estimazon");
            $consulta = mysqli_query($conexion, "
            SELECT nombre
            FROM categoria
            ");
            
            echo '<label for="categorias">Categoría/s:</label><br>';
            while($fila = mysqli_fetch_assoc($consulta)){
                echo '<input type="checkbox" id="' . $fila['nombre'] . '" name="categorias[]" value="' . $fila['nombre'] . '">';
                echo '<label for="' . $fila['nombre'] . '">' . $fila['nombre'] . '</label><br>';
            }
            echo '<br>';



        ?>

        <input type="submit" value="Añadir">
</form>

<?php
    session_start();
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

        $consulta = mysqli_query($conexion, "
        SELECT nUsuario, nombre, idVendedor
        FROM vendedor
        WHERE nombre = '$nombre_usuario'
        ");

        if($fila = mysqli_fetch_assoc($consulta)){
            $idVendedor = $fila['idVendedor'];
        }


        //comprobar si ya existe el producto
        $consulta = mysqli_query($conexion, "

        SELECT idProducto
        FROM producto
        WHERE nombre = '$nombre'
        ");


        if ($fila = mysqli_fetch_assoc($consulta)){ //el producto ya existe

            //se comprueba si el vendedor ya vende el producto
            $consulta = mysqli_query($conexion, "
            SELECT nombre, imagen, descripcion
            FROM producto
            JOIN
                (SELECT idProducto
                FROM R_VENDEDOR_PRODUCTO
                WHERE idVendedor = '$idVendedor') as r_vend_prod
            ON producto.idProducto = r_vend_prod.idProducto
            WHERE nombre = '$nombre'
            ");

            if ($fila = mysqli_fetch_assoc($consulta)) {                
                echo "Ya vendes este producto.";
                echo '<p style="color: red;">Ya vendes este producto.</p>';
                
            }else{

                //obtener idProducto
                $consulta = mysqli_query($conexion, "
                SELECT idProducto
                FROM producto
                WHERE nombre = '$nombre'
                ");
                $fila = mysqli_fetch_assoc($consulta);
                $idProducto = $fila['idProducto'];

                //añadir a r_vendedor_producto
                mysqli_query($conexion, "
                INSERT INTO r_vendedor_producto ('$idVendedor', '$idProducto', '$precio', '$stock');
                ");
            }



            
        }else{//el producto no existe

            //añadir el producto
            $consulta = mysqli_query($conexion, "
            INSERT INTO producto (nombre, descripcion, imagen) VALUES
                ('$nombre', '$descripcion', '$imagen');
            ");




            //obtener idProducto
            $consulta = mysqli_query($conexion, "
            SELECT idProducto
            FROM producto
            WHERE nombre = '$nombre'
            ");
            $fila = mysqli_fetch_assoc($consulta);
            $idProducto = $fila['idProducto'];

            //añadir las categorías a las que pertenece
            foreach ($categorias as $categoria) {

                //obtener idCategoría
                $consulta = mysqli_query($conexion, "
                SELECT idCategoria
                FROM categoria
                WHERE nombre = '$categoria'
                ");

                $fila = mysqli_fetch_assoc($consulta);
                $idCategoria = $fila['idCategoria'];

                //se añade a r_producto_categoria
                mysqli_query($conexion, "
                INSERT INTO r_producto_categoria(idProducto, idCategoria) VALUES
                    ('$idProducto','$idCategoria');
                ");

            }

            //añadir a r_vendedor_producto
            mysqli_query($conexion, "
            INSERT INTO r_vendedor_producto (idVendedor, idProducto, precio, stock) VALUES
                ('$idVendedor', '$idProducto', '$precio', '$stock');
            ");


            echo '<p style="color: green;">Producto añadido.</p>';
        }




   
    }


    
?>


