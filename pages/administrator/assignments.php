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
    <title>Asignaciones</title>
    <link rel="stylesheet" href="../../css/enterpriseColors.css"> 
    <link rel="stylesheet" href="css/assignments.css">
    <link rel="icon" href="../../images/favicon/favicon.ico" type="image/x-icon">

</head>
<body>
<body>
    <div id="containers-title">
        <label for="">Asignaciones</label>
    </div>
    <div class="container">
    <div class="box user teacher">
    <div class="icon">
        <i class="fas fa-puzzle-piece"></i>
        <i class="fas fa-users"></i>
    </div>
    <h3>Cursos <span class="arrow">→</span> Grupos</h3>
    <p>Administra y asigna cursos a grupos.</p>
    <a href="courses_to_group.php" class="btn">Asignar</a>
</div>

<div class="box user student">
    <div class="icon">
        <i class="fas fa-user-graduate"></i>
        <i class="fas fa-users"></i>
    </div>
    <h3>Alumnos      <span class="arrow">→</span> Grupos</h3>
    <p>Administra y asigna a alumnos a grupos.</p>
    <a href="students_to_group.php" class="btn">Asignar</a>
</div>

    </div>
</body>

    </div>
</body>
</html>
