<?php

session_start();
    
$conexion = mysqli_connect("localhost", "root", "");
$bd = mysqli_select_db($conexion, "estimazon");
$nombre_usuario = $_SESSION['nombreUsuario'];


if(isset($_GET['llamador'])){
    $_SESSION['llamador'] = $_GET['llamador'];
}



if(isset($_POST['direccion']) && isset($_POST['codigoPostal']) && isset($_POST['poblacion'])){
    

    $direccion = $_POST['direccion'];
    $codigoPostal = $_POST['codigoPostal'];
    $poblacion = $_POST['poblacion'];


    //obtener idPoblacion
    $consulta = mysqli_query($conexion, "
    SELECT idPoblacion 
    FROM poblacion
    WHERE nombre = '$poblacion'
    ");

    $fila = mysqli_fetch_array($consulta);
    $idPoblacion = $fila['idPoblacion'];


    //añadir el domicilio
    mysqli_query($conexion, "
    INSERT INTO domicilio (direccion, CP, idPoblacion) VALUES
    ('$direccion', '$codigoPostal', '$idPoblacion');
    ");

    //obtener idDomicilio
    $consulta = mysqli_query($conexion, "
    SELECT idDomicilio 
    FROM domicilio
    WHERE direccion = '$direccion'
    ");

    $fila = mysqli_fetch_array($consulta);
    $idDomicilio = $fila['idDomicilio'];

    //r_comprador_domicilio
    mysqli_query($conexion, "
    INSERT INTO r_comprador_domicilio (nUsuarioComp, idDomicilio) VALUES 
            ('$nombre_usuario','$idDomicilio');
    ");



    if($_SESSION['llamador'] == 'perfil'){
        header("Location: perfil.php");
    }else if($_SESSION['llamador']  == 'prepago'){
        header("Location: prepago.php");
    }


    
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/cabecera.css">
    <link rel="stylesheet" href="css/estils.css">
    <link rel="stylesheet" href="css/domicilios.css">
    <title>Formulario de Domicilio</title>
</head>
<body>
    <?php
        include "cabecera.php";
    ?>

    <div class="content">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <h2 class="subtitulo">Formulario de Domicilio</h2>
            
            <label for="direccion">Dirección:</label>
            <input type="text" id="direccion" name="direccion" required>

            <label for="codigoPostal">Código Postal:</label>
            <input type="text" id="codigoPostal" name="codigoPostal" required>


            <label for="poblacion">Población:</label>
            <select id="poblacion" name="poblacion" required>
                <option value="" disabled selected>Selecciona una población</option>
                <?php 
                    $conexion = mysqli_connect("localhost","root","");
                    $bd = mysqli_select_db($conexion, "estimazon");
                    $consulta = mysqli_query($conexion, "SELECT nombre FROM POBLACION");
                    while($poblacion = mysqli_fetch_array($consulta)){
                        echo "<option value='" . htmlspecialchars($poblacion['nombre']) . "'>" . htmlspecialchars($poblacion['nombre']) . "</option>";
                    }
                ?>
            </select><br><br>

            <button class= "button" type="submit">Añadir domicilio</button>
        </form>
    </div>
</body>
</html>


