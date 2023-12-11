<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="css/estils.css">
    <link rel="stylesheet" type="text/css" href="css/cabecera.css">
    <link rel="stylesheet" type="text/css" href="css/general.css">
    <link rel="stylesheet" type="text/css" href="css/producto.css">
    <link rel="stylesheet" type="text/css" href="css/vendedor.css">
</head>
<body>

<div class="sup">
    <div class=titulo-sup>
        <a id="Titol" href=catshow.php>
            <h1>Estimazon</h1>
        </a>
    </div>
    <div id=log-info>
        <?php
            session_start();

            if(isset($_POST['cerrarSesion'])){
                // Destruir la sesión
                session_unset();
                session_destroy();
                // Redirigir a la página de inicio de sesión
                header("Location: portal_inicio_usuario.html");
                exit();
            }

            echo "<p id=nombre-Usuario>".$_SESSION['nombreUsuario'].'</p>';
            echo '<form action="" method="post">';
            echo "<input type='hidden' name='sesionCerrada' value=''>";
            echo '<input class=boton-sesion id=cerrar-sesion type="submit" value="Cerrar Sesión" name="cerrarSesion">';
            echo '</form>';
        ?>
    </div>
</div>

<div class="subpage">
    <h2 class="subtitulo">Catálogo</h2>
</div>

<div class="mensaje-bienvenida">
    <p>Bienvenido a la sección de los vendedores.</p>
    <p>Aquí puedes agregar o modificar un producto existente.</p>
</div>

<div class="boton">
    <a href="añadir_producto.php">
        <img src="imagenes/producto+.png" alt="Añadir producto">
        <p>Añadir un producto nuevo</p>
    </a>
</div>

<div class="boton">
    <a href="añadir_producto_existente.php">
        <img src="imagenes/carrito.png" alt="Añadir producto existente">
        <p>Vender producto existente</p>
    </a>
</div>

<div class="boton">
<a href="modificar_producto.php">
    <img src="imagenes/editar.png" alt="Modificar producto">
    <p>Modificar un producto</p>
</div>

</body>
</html>