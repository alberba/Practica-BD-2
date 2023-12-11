<div class="sup">
    <div id=Titulo>
        <a id="Titol" href=catshow.php>
            <h1>Estimazon</h1>
        </a>
    </div>
    <div id=log-and-cart>
        <div id="bot-log-reg">
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

                if(!isset($_SESSION['nombreUsuario'])){
                    echo '<a class="boton-sesion" id=boton-inicio href="portal_inicio_usuario.html">Iniciar Sesión</a>';
                    echo '<a class="boton-sesion" id=boton-registro href="registrarse.php">Registrarse</a>';

                } else {
                    echo "<p id=nombre-Usuario>".$_SESSION['nombreUsuario'].'</p>';
                    echo '<form action="" method="post">';
                    echo "<input type='hidden' name='sesionCerrada' value=''>";
                    echo '<input class=boton-sesion type="submit" value="Cerrar Sesión" name="cerrarSesion">';
                    echo '</form>';
                }
            ?>
        </div>
        <a href="carrito.php" id=carrito-btn-container>
            <img src="imagenes/carrito.png" alt="Ir al carrito" class="imagen-carrito">
        </a>
        
    </div>
    
</div>