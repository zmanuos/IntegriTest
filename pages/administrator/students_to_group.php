<?php
include("header.php");
include('../../includes/authentication.php');
include("../../models/registro.php");
include ('../../models3/student.php');

checkAuthentication("administrador");


$alumnosSinGrupo = student::get_alumnos_sin_grupo();

$notificationMessage = '';
$notificationType = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $grupo = trim($_POST['grupo']);
    $matricula = trim($_POST['matricula']);

    if (!empty($grupo) && !empty($matricula)) {
        try {
            $registroExistente = registro::checkAlumnoGrupo($matricula);

            if ($registroExistente) {
                $notificationMessage = "El alumno ya pertenece al grupo '{$registroExistente['nombre_grupo']}'.";
                $notificationType = "error";
            } else {
                if (registro::insertRegistro($matricula, $grupo)) {
                    $notificationMessage = "Alumno asignado correctamente";
                    $notificationType = "success";
                } else {
                    $notificationMessage = "Error: No se pudo asignar el curso.";
                    $notificationType = "error";
                }
            }
        } catch (Exception $e) {
            $notificationMessage = "Error: " . $e->getMessage();
            $notificationType = "error";
        }
    } else {
        $notificationMessage = "Error: Debes completar todos los campos.";
        $notificationType = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/students_to_group.css"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../images/favicon/favicon.ico" type="image/x-icon">
    <title>Asignaciones</title>
    <style>
        .notification.error { background-color: #e74c3c; }
        .notification.success { background-color: #4CAF50; }
        .notification.show { display: block; opacity: 1; }
    </style>
</head>
<body>
<div class="title">
    <div class="container-title">
        <h1>Curso<span class="arrow"> → </span>Grupo</h1>
    </div>
</div>
<div class="mega-container">
    <form method="POST" class="form-container">
        <div class="form-item student-container">
            <i class="fas fa-user-graduate"></i>
            <h3>Seleccionar Alumno</h3>

            <select name="matricula" id="alumno_sin_grupo">
    <?php

    foreach ($alumnosSinGrupo as $alumno) {
        $nombreCompleto = $alumno->getNombre() . ' ' . $alumno->getApellidoP() . ' ' . $alumno->getApellidoM();
        $matricula = $alumno->getMatricula();
        echo "<option value=\"$matricula\">$nombreCompleto</option>";
    }
    ?>
</select>
        </div>

        <div class="arrow-container">
            <span class="arrows">→</span>
        </div>

        <div class="form-item group-container">
            <i class="fas fa-users"></i>
            <h3>Seleccionar Grupo</h3>
            <select name="grupo" id="grupo">
                <?php
                $result = $conn->query("SELECT grupo, nombre FROM grupo");
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='{$row['grupo']}'>{$row['nombre']}</option>";
                }
                ?>
            </select>
        </div>
        <input type="submit" value="Asignar alumno a grupo">
    </form>
</div>
<div class="notification <?php echo $notificationType; ?>" style="<?php echo empty($notificationMessage) ? 'display:none;' : ''; ?>">
    <?php echo $notificationMessage; ?>
</div>
<script>
    const notification = document.querySelector('.notification');
    if (notification) {
        setTimeout(() => notification.classList.remove('show'), 3000);
    }
</script>
</body>
</html>
