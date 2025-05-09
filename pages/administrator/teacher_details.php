<?php
    session_start();
    include_once 'header.php';
    include_once '../../includes/authentication.php';
    include_once '../../models2/teachers.php';

    checkAuthentication('administrador');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="../../css/dashboard.css"/>
    <link rel="stylesheet" href="css/styles.css"/>
    <link rel="stylesheet" href="css/tables.css?v=<?php echo time(); ?>"/>
    <link rel="stylesheet" href="css/user_details.css"/>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" href="../../images/favicon/favicon.ico" type="image/x-icon">
    <title>Detalles de docente</title>
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

            
            require_once('../../models2/teachers.php');
    
            $numEmpleado = isset($_POST['numEmpleado']) ? intval($_POST['numEmpleado']) : 0;
            
            if ($numEmpleado > 0) {
                $t = Teacher::get($numEmpleado);
            } else {
                $t = null;
            }
            ?>
        <div class="user_photo">
            <?php
                    $foto = '../../images/users/' . $t->getFoto();

                    if (file_exists($foto) && !empty($t->getFoto())) {
                        echo "<img src='$foto' alt='Foto de usuario'/>";
                    } else {
                        echo "<img src='../../images/default/user.png'/>";
                    }
            ?>
        </div> 
        
        <div class="user_info">
            

            <form action="update_teacher.php" method="POST">
                <label for="numEmpleado">No. Empleado:</label>
                <input type="text" id="numEmpleado" value="<?php echo $t->getNumEmpleado(); ?>" name="numEmpleado" readonly>

                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" value="<?php echo $t->getNombre(); ?>" name="nombre">

                <label for="apellidoP">Apellido paterno:</label>
                <input type="text" id="apellidoP" value="<?php echo $t->getApePat(); ?>" name="apellidoP">

                <label for="apellidoM">Apellido materno:</label>
                <input type="text" id="apellidoM" value="<?php echo $t->getApeMat(); ?>" name="apellidoM">
                
                <label for="telefono">Teléfono:</label>
                <input type="text" id="telefono" value="<?php echo $t->getTelefono(); ?>" name="telefono">

                <label for="correo">Correo:</label>
                <input type="text" id="correo" value="<?php echo $t->getCorreo(); ?>" name="correo" readonly>

                <label for="genero">Género:</label>
                <input type="text" id="genero" value="<?php echo $t->getGenero(); ?>" name="genero" readonly>

                <label for="curp">Curp:</label>
                <input type="text" id="curp" value="<?php echo $t->getCurp(); ?>" name="curp">


                <button type="submit">Actualizar</button>
            </form>
        </div>
    </div>

    <script src="js/get.js"></script>

</body>
</html>