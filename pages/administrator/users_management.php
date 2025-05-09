<?php 
        include('header.php');
        include ('../../includes/authentication.php');
        

        checkAuthentication('administrador');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear usuarios</title>
    <link rel="stylesheet" href="../../css/enterpriseColors.css"> 
    <link rel="stylesheet" href="css/users_management.css">
    <link rel="icon" href="../../images/favicon/favicon.ico" type="image/x-icon">

</head>
<body>
<body>
    <div id="containers-title">
        <label for="">Crear usuarios</label>
    </div>
    <div class="container">
        <div class="box user teacher">
            <div class="icon">
                <i class="fas fa-chalkboard-teacher"></i>
            </div>
            <h3>Docente</h3>
            <p>Gestionar clases y estudiantes.</p>
            <a href="form_user_teacher.php" class="btn">Crear Docente</a>
        </div>

        <div class="box user student">
            <div class="icon">
                <i class="fas fa-user-graduate"></i>
            </div>
            <h3>Estudiante</h3>
            <p>Accede a tus clases y calificaciones.</p>
            <a href="form_user_student.php" class="btn">Crear Estudiante</a>
        </div>
    </div>
</body>

    </div>
</body>
</html>
