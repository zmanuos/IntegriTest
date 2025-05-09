<?php 
        include ('../../includes/authentication.php');
        include('header.php');

        checkAuthentication('maestro');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../images/favicon/favicon.ico" type="image/x-icon">
    <title>Crear usuarios</title>
    <link rel="stylesheet" href="../../css/enterpriseColors.css"> 
    <link rel="stylesheet" href="../administrator/css/users_management.css">
</head>
<body>
<body>
    <div id="containers-title">
        <label for="">Examenes</label>
    </div>
    <div class="container">
        <div class="box user teacher">
            <div class="icon">
            <i class="fas fa-clipboard-list"></i>
            </div>
            <h3>Crear</h3>
            <p>Gestionar creacion de examenes.</p>
            <a href="creacion-examenes.php" class="btn">Crear Examen</a>
        </div>

        <div class="box user student">
            <div class="icon">
            <i class="fas fa-paste"></i>
            </div>
            <h3>Editar</h3>
            <p>Accede y edita tus examenes.</p>
            <a href="exam-views.php" class="btn">Editar Examen</a>
        </div>
    </div>
</body>

    </div>
</body>
</html>
