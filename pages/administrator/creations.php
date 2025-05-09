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
    <link rel="stylesheet" href="../../css/enterpriseColors.css"> 
    <link rel="stylesheet" href="css/users_management.css">
    <link rel="stylesheet" href="css/creation.css">

    <link rel="icon" href="../../images/favicon/favicon.ico" type="image/x-icon">
    <title>Crear usuarios</title>

</head>
<body>
<body>
    <div id="containers-title">
        <label for="">Creaciones</label>
    </div>
    <div class="container">
        <div class="box user teacher">
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
            <h3>Grupos</h3>
            <p>Forma y gestiona tus grupos de clases.</p>
            <a href="create_groups.php" class="btn">Crear Grupos</a>
        </div>

        <div class="box user student">
            <div class="icon">
                <i class="fas fa-book-open"></i>
            </div>
            <h3>Temas</h3>
            <p>Crea y organiza tus temas de estudio.</p>
            <a href="create_themes.php" class="btn">Crear Temas</a>
        </div>

        <div class="box user administrator">
            <div class="icon">
                <i class="fas fa-puzzle-piece"></i>
            </div>
            <h3>Cursos</h3>
            <p>Crea y gestiona cursos de manera rápida.</p>
            <a href="create_courses.php" class="btn">Crear Cursos</a>
        </div>
    </div>
</body>

    </div>
</body>
</html>
