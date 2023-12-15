<?php

    session_start();
        
    $conexion = mysqli_connect("localhost", "root", "");
    $bd = mysqli_select_db($conexion, "estimazon");
    $nombre_usuario = $_SESSION['nombreUsuario'];

    // Verificar si se viene desde el perfil o desde el pago
    if(isset($_GET['llamador'])){

        $_SESSION['llamador'] = $_GET['llamador'];

    }

    if(isset($_POST['direccion']) && isset($_POST['codigoPostal']) && isset($_POST['poblacion'])){
         
        // Obtener los datos del formulario
        $direccion = $_POST['direccion'];
        $codigoPostal = $_POST['codigoPostal'];
        $poblacion = $_POST['poblacion'];


        // Obtener idPoblacion
        $consulta = mysqli_query($conexion, "
            SELECT idPoblacion 
            FROM poblacion
            WHERE nombre = '$poblacion'
        ");

        $fila = mysqli_fetch_array($consulta);
        $idPoblacion = $fila['idPoblacion'];


        // Añadir el domicilio
        mysqli_query($conexion, "
            INSERT INTO domicilio (direccion, CP, idPoblacion) VALUES
            ('$direccion', '$codigoPostal', '$idPoblacion');
        ");

        // Obtener idDomicilio
        $consulta = mysqli_query($conexion, "
            SELECT idDomicilio 
            FROM domicilio
            WHERE direccion = '$direccion'
        ");

        $fila = mysqli_fetch_array($consulta);
        $idDomicilio = $fila['idDomicilio'];

        // r_comprador_domicilio
        mysqli_query($conexion, "
            INSERT INTO r_comprador_domicilio (nUsuarioComp, idDomicilio) VALUES 
                    ('$nombre_usuario','$idDomicilio');
        ");

        // Redirigir al perfil o al pago
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
    <link rel="stylesheet" href="../css/general.css">
    <link rel="stylesheet" href="../css/cabecera.css">
    <link rel="stylesheet" href="../css/estils.css">
    <link rel="stylesheet" href="../css/domicilios.css">
    <title>Formulario de Domicilio</title>

</head>
<body>

    <?php
        include "../cabecera.php";
    ?>

    <div class="subpage">

        <h2 id="subtitulo-añadir">Formulario de Domicilio</h2>

    </div>

    <div class="content-añ-domicilio">

        <div id=form-domicilio>

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

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

                        $consulta = mysqli_query($conexion, "
                            SELECT nombre 
                            FROM POBLACION
                        ");

                        while($poblacion = mysqli_fetch_array($consulta)){

                            echo "<option value='" . htmlspecialchars($poblacion['nombre']) . "'>" . htmlspecialchars($poblacion['nombre']) . "</option>";

                        }

                    ?>

                </select><br>

                <button class= "button" type="submit">Añadir domicilio</button>

            </form>


        </div>

        
    </div>
    
</body>
</html>


