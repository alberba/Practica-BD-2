<div class="sup">
    <div id=Titulo>
        <a id="Titol" href=catshow.php>
            <h1>Estimazon</h1>
        </a>
    </div>
    <div id="carrito-btn-container">
        <a href="carrito.php">
            <img src="imagenes/carrito.png" alt="Ir al carrito" class="imagen-carrito">
        </a>
    </div>
    <div id="login">
        <?php
            session_start();

            if(isset($_POST['cerrarSesion'])){
                // Destruir la sesión
                session_unset();
                session_destroy();
                // Redirigir a la página de inicio de sesión
                header("Location: pagina_principal.php");
                exit();
            }

            if(!isset($_SESSION['nombreUsuario'])){
                echo '<form action="login.php" method="post">';
                echo '<input name="user" placeholder="Usuario:">';
                echo '<input name="pass" type="password" placeholder="Contraseña:">';
                echo '<input class=boton-sesion type="submit" value="Iniciar Sesión" name="Iniciar Sesión">';
                echo '</form>';
            } else {
                echo "<p id=nombre-Usuario>".$_SESSION['nombreUsuario'].'</p>';
                echo '<form action="" method="post">';
                echo "<input type='hidden' name='sesionCerrada' value=''>";
                echo '<input class=boton-sesion type="submit" value="Cerrar Sesión" name="cerrarSesion">';
                echo '</form>';
            }
        ?>
    </div>
</div>