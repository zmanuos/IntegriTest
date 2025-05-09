<?php
include ("header.php");
include ('../../includes/authentication.php');
include ("../../models/asignacion.php");

checkAuthentication("administrador");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $numEmpleado = $_POST['numEmpleado'];
    $cursonum = $_POST['cursonum'];
    $grupo = $_POST['grupo'];

    $stmt = $conn->prepare("SELECT COUNT(*) FROM asignacion WHERE cursonum = ? AND grupo = ?");
    $stmt->bind_param('ii', $cursonum, $grupo);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        echo '<div class="notification error">Esta asignación ya existe.</div>';
    } else {
        $stmt = $conn->prepare("SELECT promedio FROM grupo WHERE grupo = ?");
        $stmt->bind_param('i', $grupo);
        $stmt->execute();
        $stmt->bind_result($promedio);
        $stmt->fetch();
        $stmt->close();

        if ($promedio !== null) {
            asignacion::insert($cursonum, $grupo, $promedio, $numEmpleado);
            echo '<div class="notification success">Curso asignado correctamente al grupo.</div>';
        } else {
            echo '<div class="notification error">No se encontró el promedio para el grupo seleccionado.</div>';
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/courses_to_group.css"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../images/favicon/favicon.ico" type="image/x-icon">
    <title>Asignaciones</title>
    <style>

        .notification {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            background-color: #4CAF50;
            color: white;
            padding: 15px;
            border-radius: 8px;
            font-size: 16px;
            display: none;
            z-index: 1000;
            opacity: 0;
            transition: opacity 0.5s ease-in-out;
        }
        .notification.error {
            background-color: #e74c3c;
        }
    </style>
</head>
<body>

<div class="title">
    <div class="container-title">
        <h1>Curso<span class="arrow"> →</span>
        Grupo</h1>
    </div>
</div>

<div class="mega-container">
    <form method="POST" class="form-container">
        <div class="form-item course-container">
            <i class="fas fa-puzzle-piece"></i>
            <h3>Seleccionar Curso</h3>
            <select name="cursonum" id="cursonum">
                <?php
                include 'conexion.php';
                $result = $conn->query("SELECT cursonum, nombre FROM curso");
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='{$row['cursonum']}'>{$row['nombre']}</option>";
                }
                ?>
            </select>
        </div>

        <div class="arrow-container">
            <span class="arrows">→</span>
        </div>

        <div class="form-item teacher-container">
            <i class="fas fa-chalkboard-teacher"></i>
            <h3>Seleccionar Docente</h3>
            <select name="numEmpleado" id="numEmpleado">
                <?php
                $result = $conn->query("SELECT numEmpleado, nombre, apellidoP, apellidoM FROM docente");
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='{$row['numEmpleado']}'>{$row['numEmpleado']} - {$row['nombre']} {$row['apellidoP']}</option>";
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

        <input type="submit" value="Asignar curso a grupo">
    </form>
</div>

<div class="notification <?php echo $notificationType; ?>">
    <?php echo $notificationMessage; ?>
</div>

<script>
    const errorNotification = document.querySelector('.notification.error');
    const successNotification = document.querySelector('.notification.success');

    if (errorNotification || successNotification) {
        if (errorNotification) {
            errorNotification.classList.add('show'); 
        }
        if (successNotification) {
            successNotification.classList.add('show'); 
        }

        setTimeout(() => {
            if (errorNotification) {
                errorNotification.classList.remove('show');
            }
            if (successNotification) {
                successNotification.classList.remove('show');
            }
        }, 5000);
    }
</script>

</body>
</html>
