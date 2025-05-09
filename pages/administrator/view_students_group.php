<?php
include("header.php");
include('../../includes/authentication.php');
include('../../models/registro.php');

checkAuthentication("administrador");

$grupoId = $_GET['grupoId'] ?? null;

if (!$grupoId) {
    die("No se especificó un número de grupo.");
}

try {
    $data = Registro::getAlumnosDeGrupo($grupoId);
    $alumnos = $data['alumnos'];
    $grupoData = $data['grupoData'];

    if (count($alumnos) == 0) {
        die("No se encontraron alumnos en este grupo.");
    }

    $nombreGrupo = $grupoData['nombre_grupo'];
    $cantidadExamenes = $grupoData['cantidad_examenes'];
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}

if (isset($_GET['eliminar']) && isset($_GET['matricula'])) {
    $matricula = $_GET['matricula'];
    try {
        if (Registro::eliminarAlumnoDeGrupo($matricula, $grupoId)) {
            echo "<script>alert('Alumno eliminado exitosamente.'); window.location.href = 'alumnos.php?grupoId=" . $grupoId . "';</script>";
        }
    } catch (Exception $e) {
        echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../images/favicon/favicon.ico" type="image/x-icon">
    <title>Alumnos del Grupo</title>
    <link rel="stylesheet" href="css/view_students_group.css">
</head>
<body>
    <div class="title">
        <div class="container-title">
        <h1>Grupo - <?= htmlspecialchars($nombreGrupo) ?></h1>
        </div>
    </div>

    <div class="alumnos-container">
        <p><strong>Examenes realizados:</strong> <?= htmlspecialchars($cantidadExamenes) ?></p>

        <?php if (count($alumnos) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Foto</th>
                        <th>Matrícula</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Promedio</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($alumnos as $alumno): ?>
                        <tr>
                            <td>
                                <?php
                                    $fotoPath = '../../images/users/' . htmlspecialchars($alumno['foto']);
                                    if (!file_exists($fotoPath)) {
                                        $fotoPath = '../../images/default/default.png';
                                    }
                                ?>
                                <img class="foto-alumno" src="<?= $fotoPath ?>" alt="Foto del alumno">
                            </td>
                            <td><?= htmlspecialchars($alumno['matricula']) ?></td>
                            <td><?= htmlspecialchars($alumno['nombre']) ?></td>
                            <td><?= htmlspecialchars($alumno['apellidoP']) ?></td>
                            <td><?= htmlspecialchars($alumno['promedio']) ?></td>
                            <td>
                                <a href="?grupoId=<?= htmlspecialchars($grupoId) ?>&matricula=<?= htmlspecialchars($alumno['matricula']) ?>&eliminar=true" onclick="return confirm('¿Estás seguro de eliminar a este alumno del grupo?')">Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No hay alumnos registrados en este grupo.</p>
        <?php endif; ?>
    </div>
</body>
</html>

