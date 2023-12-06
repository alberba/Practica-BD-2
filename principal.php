<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="css/estils.css">
    <link rel="stylesheet" type="text/css" href="css/cabecera.css">
    <link rel="stylesheet" type="text/css" href="css/general.css">
    <title>Estimazon</title>
</head>
<body>
<body>
    <div class="sup">
        <div id=Titulo>
            <a id="Titol" href=principal.php>
                <h1>Estimazon</h1>
            </a>
        </div>
        <div id="login">
            <?php
                session_start();

                if(!isset($_SESSION['nomVeterinario'])){
                    echo '<form action="login.php" method="post">';
                    echo '<input name="user" placeholder="Usuario:">';
                    echo '<input name="pass" type="password" placeholder="Contraseña:">';
                    echo '<input type="submit" value="Iniciar Sesión" name="Iniciar Sesión">';
                    echo '</form>';
                } else {
                    echo "<p>Bienvenido, ".$_SESSION['nomVeterinario'].'</p>';
                }
            ?>
        </div>
    </div>
   
    


    <ul>
        <li><a href="catalogo.php">Catalogo online</a></li>
        <li><a href="vetllista.php">Llista de Veterinaris</a></li>
        <li><a href="mascllista.php">Llista de Mascotes</a></li>
        <li><a href="visitllista.php">Llista de Visites</a></li>
    </ul>
</body>
</body>
</html>