<!DOCTYPE html>
<head>
    <title>Catalogo - Estimazon</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="estils.css?v=1">
</head>
<body>
    <div class="sup">
        <div id="Titulo">
            <a id="Titol" href=principal.php>Estimazon</a>
        </div>
    </div>
    <div class="subpage">
            <h1 class="subtitulo">Catálogo</h1>
    </div>
    <div class=content>
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

    </div>
    
</body>
</html>