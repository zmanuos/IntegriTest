<?php
include("header.php");
include('../../includes/authentication.php');
include('../../models/examen.php');

checkAuthentication("administrador");

$searchExamen = isset($_GET['searchExamen']) ? $_GET['searchExamen'] : '';
$searchName = isset($_GET['searchName']) ? $_GET['searchName'] : '';

$examenesPorPagina = 9;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $examenesPorPagina;

if ($searchName) {
    $examenes = examen::searchByName($searchName);
} else {
    $examenes = examen::get();
}

if ($searchExamen) {
    $examenes = array_filter($examenes, function($examen) use ($searchExamen) {
        return stripos($examen->getNumExam(), $searchExamen) !== false;
    });
}

$totalExamenes = count($examenes);
$examenes = array_slice($examenes, $offset, $examenesPorPagina);
$totalPages = ceil($totalExamenes / $examenesPorPagina);
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/queries_exam.css">
    <link rel="icon" href="../../images/favicon/favicon.ico" type="image/x-icon">
    <title>Gestionar Exámenes</title>
    <link rel="stylesheet" href="css/create_examenes.css">
</head>
<body>
    <div class="title">
        <div class="container-title">
            <h1>Gestión de Exámenes</h1>
        </div>
    </div>

    <div class="mega-container">
        <div class="courses-container">
            <div class="search-bar">
                <h2>Exámenes</h2>
                <form method="GET" action="">
                    <div class="search-item">
                        <input type="text" name="searchExamen" placeholder="Buscar por ID" value="<?= htmlspecialchars($searchExamen) ?>" class="search-input">
                    </div>
                    <div class="search-item">
                        <input type="text" name="searchName" placeholder="Buscar por nombre" value="<?= htmlspecialchars($searchName) ?>" class="search-input">
                    </div>
                    <button type="submit" class="search-btn"><i class="fa fa-search"></i></button>
                </form>
            </div>

            <?php if (count($examenes) > 0): ?>
                <div class="courses-table" id="examenesTable">
                    <div class="table-header">
                        <div class="table-cell">ID</div>
                        <div class="table-cell">Nombre</div>
                        <div class="table-cell">Tema</div>
                        <div class="table-cell">Docente</div>
                        <div class="table-cell">Fecha inicio</div>
                        <div class="table-cell">Fecha final</div>
                        <div class="table-cell">Estado</div>
                        <div class="table-cell">Acciones</div>
                    </div>

                    <?php foreach ($examenes as $examen): ?>
                        <?php 
                            $temas = examen::getTemas($examen->getNumExam());
                            $temasString = implode(', ', $temas);
                            $nombreDocente = examen::getNombreDocente($examen->getNumEmpleado());
                        ?>
                        <div class="table-row">
                            <div class="table-cell"><?= htmlspecialchars($examen->getNumExam()) ?></div>
                            <div class="table-cell"><?= htmlspecialchars($examen->getTitulo()) ?></div>
                            <div class="table-cell"><?= $temasString ?></div>
                            <div class="table-cell"><?= htmlspecialchars($nombreDocente) ?></div>
                            <div class="table-cell"><?= htmlspecialchars($examen->getInicioExamen()) ?></div>
                            <div class="table-cell"><?= htmlspecialchars($examen->getFinalExamen()) ?></div>
                            <div class="table-cell"><?= $examen->getNumEstado() == 1 ? 'Activo' : 'Inactivo' ?></div>
                            <div class="table-cell">
                                <a href="query_exam_details.php?id=<?= $examen->getNumExam() ?>" class="edit-btn">Seleccionar</a>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <div class="pagination">
                        <?php if ($page > 1): ?>
                            <a href="?page=<?= $page - 1 ?>&searchExamen=<?= htmlspecialchars($searchExamen) ?>&searchName=<?= htmlspecialchars($searchName) ?>" class="pagination-link">Anterior</a>
                        <?php endif; ?>

                        <span class="pagination-info">Página <?= $page ?> de <?= $totalPages ?></span>

                        <?php if ($page < $totalPages): ?>
                            <a href="?page=<?= $page + 1 ?>&searchExamen=<?= htmlspecialchars($searchExamen) ?>&searchName=<?= htmlspecialchars($searchName) ?>" class="pagination-link">Siguiente</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="no-results">
                    <p>No se encontraron exámenes.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const notification = document.getElementById("notification");
            if (notification) {
                setTimeout(() => {
                    notification.style.display = "none";
                }, 3000);
            }
        });
    </script>
</body>
</html>
