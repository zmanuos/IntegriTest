<?php
    include_once 'header.php';
    include_once '../../includes/authentication.php';
    include_once '../../models2/students.php';

    checkAuthentication('administrador');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="../../css/dashboard.css"/>
    <link rel="stylesheet" href="css/user_details.css"/>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../images/favicon/favicon.ico" type="image/x-icon">
    <title>Detalles Estudiante</title>
</head>
<body>
    <div class="container">

        <?php
            if (isset($_POST['success_message'])) {
                echo "<p class='success-message'>" . htmlspecialchars($_POST['success_message']) . "</p>";
            }
            if (isset($_POST['error_message'])) {
                echo "<p class='error-message'>" . htmlspecialchars($_POST['error_message']) . "</p>";
            }

            $matricula = isset($_POST['matricula']) ? intval($_POST['matricula']) : 0;

            if ($matricula > 0) {
                $s = Student::get($matricula);
            } else {
                $s = null;
            }
        ?>
        <div class="user_container">
            <div class="user_photo">
                <?php
                    $foto = '../../images/users/' . $s->getFoto();

                    if (file_exists($foto) && !empty($s->getFoto())) {
                        echo "<img src='$foto' alt='Foto de usuario'/>";
                    } else {
                        echo "<img src='../../images/default/default.png'/>";
                    }
                ?>
            </div>

        </div>

        <div class="user_info">
            <form action="update_student.php" method="POST">
                <input type="hidden" name="matricula" value="<?php echo $s->getMatricula(); ?>">

                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" value="<?php echo $s->getNombre(); ?>" name="nombre">

                <label for="apellidoP">Apellido paterno:</label>
                <input type="text" id="apellidoP" value="<?php echo $s->getApePat(); ?>" name="apellidoP">

                <label for="apellidoM">Apellido materno:</label>
                <input type="text" id="apellidoM" value="<?php echo $s->getApeMat(); ?>" name="apellidoM">
                
                <label for="promedio">Promedio:</label>
                <input type="text" id="promedio" value="<?php echo $s->getPromedio(); ?>" name="promedio">
                
                <label for="telefono">Teléfono:</label>
                <input type="text" id="telefono" value="<?php echo $s->getTelefono(); ?>" name="telefono">

                <label for="correo">Correo:</label>
                <input type="text" id="correo" value="<?php echo $s->getCorreo(); ?>" name="correo" readonly>

                <label for="genero">Género:</label>
                <input type="text" id="genero" value="<?php echo $s->getGenero(); ?>" name="genero" readonly>

                <label for="fechaNacimiento">Fecha de nacimiento:</label>
                <input type="text" id="fechaNacimiento" value="<?php echo $s->getFechaNacimiento(); ?>" name="fechaNacimiento">

                <label for="curp">Curp:</label>
                <input type="text" id="curp" value="<?php echo $s->getCurp(); ?>" name="curp">

                <button type="submit">Actualizar</button>
            </form>
        </div>
    </div>

<script src="js/get.js"></script>

</body>
</html>
