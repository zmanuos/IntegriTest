<?php
include ('../../includes/authentication.php');
include('header.php');
include ('../../models/grupohistorial.php');

checkAuthentication('administrador');

$grupohistorial = new GrupoHistorial($conn);

$resultados = [];

if ($_SERVER['REQUEST_METHOD'] === 'GET' && !empty($_GET)) {
    if (!empty($_GET['matricula'])) {
        $resultados = $grupohistorial->buscarPorMatricula($_GET['matricula']);
    } elseif (!empty($_GET['grupo_numero'])) {
        $resultados = $grupohistorial->buscarPorGrupoNumero($_GET['grupo_numero']);
    } elseif (!empty($_GET['grupo_nombre'])) {
        $resultados = $grupohistorial->buscarPorGrupoNombre($_GET['grupo_nombre']);
    } else {
        $resultados = $grupohistorial->obtenerTodos();
    }
} else {
    $resultados = $grupohistorial->obtenerTodos();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/queries_groups_history.css"> 
    <link rel="icon" href="../../images/favicon/favicon.ico" type="image/x-icon">
    <title>Historial de Grupos</title>

</head>
<body>
    <div class="container">
        <div id="containers-title">
            <label for="">Historial de grupos</label>
        </div>
        
        <div class="form-container">
            <form method="GET" action="">
                <input type="text" name="matricula" placeholder="Buscar por matrícula" value="<?= htmlspecialchars($_GET['matricula'] ?? '') ?>">
                <input type="text" name="grupo_numero" placeholder="Buscar por número de grupo" value="<?= htmlspecialchars($_GET['grupo_numero'] ?? '') ?>">
                <input type="text" name="grupo_nombre" placeholder="Buscar por nombre del grupo" value="<?= htmlspecialchars($_GET['grupo_nombre'] ?? '') ?>">
                <button type="submit">Buscar</button>
            </form>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Número de Grupo</th>
                    <th>Nombre del Grupo</th>
                    <th>Fecha de Inicio</th>
                    <th>Fecha de Finalización</th>
                    <th>Matrícula del Alumno</th>
                    <th>Nombre del Alumno</th>
                    <th>Promedio del Alumno</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($resultados)): ?>
                    <?php foreach ($resultados as $registro): ?>
                        <tr>
                            <td><?= htmlspecialchars($registro['numero']) ?></td>
                            <td><?= htmlspecialchars($registro['grupo_numero']) ?></td>
                            <td><?= htmlspecialchars($registro['grupo_nombre']) ?></td>
                            <td><?= htmlspecialchars($registro['fechaInicio']) ?></td>
                            <td><?= htmlspecialchars($registro['fechaFinal']) ?></td>
                            <td><?= htmlspecialchars($registro['alumno_matricula']) ?></td>
                            <td><?= htmlspecialchars($registro['alumno_nombre']) ?></td>
                            <td><?= htmlspecialchars($registro['promedio_alumno']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8">No se encontraron registros.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
