<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Domicilio</title>
    <link rel="stylesheet" href="css/domicilios.css">
</head>
<body>

    <h2>Formulario de Domicilio</h2>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">


        <label for="direccion">Dirección:</label>
        <input type="text" id="direccion" name="direccion" required>z

        <label for="codigoPostal">Código Postal:</label>
        <input type="text" id="codigoPostal" name="codigoPostal" required>

        <button type="submit">Enviar Domicilio</button>
    </form>

</body>
</html>





<?php
session_start();
$conexion = mysqli_connect("localhost", "root", "");
$bd = mysqli_select_db($conexion, "estimazon");
$nombre_usuario = $_SESSION['nombreUsuario'];



mysqli_query($conexion, "
    INSERT INTO r_(idProducto, idCategoria) VALUES 
    ('$idProducto','$idCategoria');
");

?>

