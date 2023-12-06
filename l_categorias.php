    
<nav class="menu-categorias">
    <?php
        $conexion = mysqli_connect("localhost","root","");
        $bd = mysqli_select_db($conexion, "estimazon");
        $consulta = mysqli_query($conexion, "SELECT * FROM categoria ORDER BY nombre ASC");
        while ($fila = mysqli_fetch_array($consulta)) {
            $link = "catshow.php?param=".$fila["idCategoria"];
            echo "<a class='catlist' href= '$link'>".$fila['nombre']. "</a>";
        }
    ?>
</nav>