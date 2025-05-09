<?php
        include ('../../includes/authentication.php');
        include('header.php');

        checkAuthentication('administrador');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultas</title>
    <link rel="stylesheet" href="../../css/enterpriseColors.css"> 
    <link rel="stylesheet" href="css/queries.css">
    <link rel="icon" href="../../images/favicon/favicon.ico" type="image/x-icon">

</head>
<body>
<body>
    <div id="containers-title">
        <label for="">Consultas</label>
    </div>
    <div class="container">
        <div class="box user teacher">
            <div class="icon">
                <i class="fas fa-book"></i>
            </div>
            <h3>Examenes</h3>
            <p>Gestionar y consultar examenes.</p>
            <a href="querie_exam.php" class="btn">Seleccionar</a>
        </div>

        <div class="box user student">
            <div class="icon">
                <i class="fas fa-history"></i>
            </div>
            <h3>Historial de grupos</h3>
            <p>Administra y consulta el historial de grupos.</p>
            <a href="queries_groups_history.php" class="btn">Seleccionar</a>
        </div>
    </div>
</body>

    </div>
</body>
</html>
