<?php
include("header.php");
include('../../includes/authentication.php');
include('../../models/grupo.php');

checkAuthentication("administrador");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $grupoId = $_POST['grupo'] ?? null;
    $nombre = trim($_POST['nombre']);
    $fechaInicio = $_POST['fechaInicio'];
    $fechaFinal = $_POST['fechaFinal'];
    $estado = (int)$_POST['estado'];
    $promedio = $_POST['promedio'] ?? 0;

    if (!DateTime::createFromFormat('Y-m-d', $fechaInicio)) {
        die('La fecha de inicio es inválida. Debe tener el formato YYYY-MM-DD.');
    }

    if (!DateTime::createFromFormat('Y-m-d', $fechaFinal)) {
        die('La fecha final es inválida. Debe tener el formato YYYY-MM-DD.');
    }

    if ($fechaInicio > $fechaFinal) {
        die('Error: La fecha final no puede ser anterior a la fecha de inicio.');
    }

    if ($grupoId) {
        $success = Grupo::update($grupoId, $nombre, $estado, $fechaInicio, $fechaFinal, $promedio);
        $_SESSION['responseMessage'] = $success;
    } else {
        $success = Grupo::create($nombre, $estado, $fechaInicio, $fechaFinal, $promedio);
        $_SESSION['responseMessage'] = $success;
    }
    $_SESSION['responseMessage'] = $success 
        ? "Grupo " . (isset($grupoId) && !empty($grupoId) ? "actualizado" : "creado") . " exitosamente."
        : "Error al procesar el grupo.";

    $_SESSION['showNotification'] = true;
} else {
    $_SESSION['showNotification'] = false;
}

$searchGrupo = $_GET['searchGrupo'] ?? '';
$searchName = $_GET['searchName'] ?? '';

$page = max(1, (int)($_GET['page'] ?? 1));
$limit = 9;
$offset = ($page - 1) * $limit;

$grupos = Grupo::search($searchGrupo, $searchName, $limit, $offset);
$totalGrupos = Grupo::count($searchGrupo, $searchName);
$totalPages = ceil($totalGrupos / $limit);
?>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../images/favicon/favicon.ico" type="image/x-icon">
    <title>Registrar Grupo</title>
    <link rel="stylesheet" href="css/create_groups.css">
</head>
<body>

    <div class="title">
        <div class="container-title">
            <h1>Gestión de grupos</h1>
        </div>
    </div>

    <div class="mega-container">
        <div class="form-wrapper">
            <h2>Registrar grupo</h2>
            <form id="grupoForm" method="POST">
                <div class="form-group">
                    <label for="nombre">Nombre del grupo:</label>
                    <input type="text" id="nombre" name="nombre" required>
                </div>

                <div class="form-group">
                    <label for="fechaInicio">Fecha inicio:</label>
                    <input type="date" id="fechaInicio" name="fechaInicio" required>
                </div>
                <div class="form-group">
                    <label for="fechaFinal">Fecha Final:</label>
                    <input type="date" id="fechaFinal" name="fechaFinal" required>
                </div>

                <div class="form-group">
                    <label for="estado">Estado:</label>
                    <select id="estado" name="estado" required>
                        <option value="1">Activo</option>
                        <option value="0">Inactivo</option>
                    </select>
                </div>

                <div class="container-submit">
                    <button type="submit" class="btn-submit">Registrar</button>
                </div>
            </form>
        </div>

        <div class="courses-container">
            <div class="search-bar">
                <h2>Grupos</h2>
                <form method="GET" action="">
                    <div class="search-item">
                        <input type="text" name="searchGrupo" placeholder="Buscar por ID" value="<?= htmlspecialchars($searchGrupo) ?>" class="search-input">
                    </div>
                    <div class="search-item">
                        <input type="text" name="searchName" placeholder="Buscar por nombre" value="<?= htmlspecialchars($searchName) ?>" class="search-input">
                    </div>
                    <button type="submit" class="search-btn"><i class="fa fa-search"></i></button>
                </form>
            </div>

            <?php if (count($grupos) > 0): ?>
                <div class="courses-table" id="groupsTable">
                    <div class="table-header">
                        <div class="table-cell">ID</div> 
                        <div class="table-cell">Nombre</div>
                        <div class="table-cell">Fecha Inicio</div>
                        <div class="table-cell">Fecha final</div>
                        <div class="table-cell">Alumnos</div>
                        <div class="table-cell">Promedio</div>
                        <div class="table-cell">Estado</div>
                        <div class="table-cell">Acciones</div>
                    </div>

                    <?php foreach ($grupos as $grupo): ?>
                        <div class="table-row">
                            <div class="table-cell"><?= htmlspecialchars($grupo->getGrupo()) ?></div>
                            <div class="table-cell"><?= htmlspecialchars($grupo->getNombre()) ?></div>
                            <div class="table-cell"><?= htmlspecialchars($grupo->getFechaInicio()) ?></div>
                            <div class="table-cell"><?= htmlspecialchars($grupo->getFechaFinal()) ?></div>
                            <div class="table-cell"><?= htmlspecialchars($grupo->getCantidadAlumnos()) ?></div>
                            <div class="table-cell"><?= htmlspecialchars($grupo->getPromedio()) ?></div>
                            <div class="table-cell"><?= $grupo->getEstado() == 1 ? 'Activo' : 'Inactivo' ?></div>
                            <div class="table-cell">
                                <a href="#" class="edit-btn" 
                                    data-id="<?= $grupo->getGrupo() ?>"
                                    data-nombre="<?= htmlspecialchars($grupo->getNombre()) ?>" 
                                    data-estado="<?= $grupo->getEstado() ?>" 
                                    data-fechaInicio="<?= htmlspecialchars($grupo->getFechaInicio()) ?>" 
                                    data-fechaFinal="<?= htmlspecialchars($grupo->getFechaFinal()) ?>">Editar
                                </a> 
                                <a href="view_students_group.php?grupoId=<?= $grupo->getGrupo() ?>" class="view-btn">Ver más</a>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <div class="pagination">
                        <?php if ($page > 1): ?>
                            <a href="?page=<?= $page - 1 ?>&searchGrupo=<?= htmlspecialchars($searchGrupo) ?>&searchName=<?= htmlspecialchars($searchName) ?>" class="pagination-link">Anterior</a>
                        <?php endif; ?>

                        <span class="pagination-info">Página <?= $page ?> de <?= $totalPages ?></span>

                        <?php if ($page < $totalPages): ?>
                            <a href="?page=<?= $page + 1 ?>&searchGrupo=<?= htmlspecialchars($searchGrupo) ?>&searchName=<?= htmlspecialchars($searchName) ?>" class="pagination-link">Siguiente</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="no-results">
                    <p>No se encontraron grupos.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h3>Editar Grupo</h3>
        <form id="editForm" method="POST">
            <input type="hidden" id="editGrupoId" name="grupo">
            <div class="form-group">
                <label for="editNombre">Nombre:</label>
                <input type="text" id="editNombre" name="nombre" required>
            </div>
            <div class="form-group">
                <label for="editFechaInicio">Fecha inicio:</label>
                <input type="date" id="editFechaInicio" name="fechaInicio" required>
            </div>
            <div class="form-group">
                <label for="editFechaFinal">Fecha Final:</label>
                <input type="date" id="editFechaFinal" name="fechaFinal" required>
            </div>
            <div class="form-group">
                <label for="editEstado">Estado:</label>
                <select id="editEstado" name="estado" required>
                    <option value="1">Activo</option>
                    <option value="0">Inactivo</option>
                </select>
            </div>
            <div class="form-group">
                <button type="submit" class="btn-submit">Actualizar</button>
            </div>
        </form>
    </div>
</div>

<div id="notification" class="notification" style="display: <?= $_SESSION['showNotification'] ? 'block' : 'none'; ?>;">
    <?= $_SESSION['responseMessage']; ?>
</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const notification = document.getElementById("notification");
        if (notification) {
            setTimeout(() => {
                notification.style.display = "none";
            }, 3000);
        }

        const editModal = document.getElementById("editModal");
        const editButtons = document.querySelectorAll(".edit-btn");
        const closeModal = document.querySelector(".close");

        editButtons.forEach(button => {
            button.addEventListener("click", (event) => {
                event.preventDefault();

                const grupoId = button.getAttribute("data-id");
                const nombre = button.getAttribute("data-nombre");
                const estado = button.getAttribute("data-estado");
                const fechaInicio = button.getAttribute("data-fechaInicio");
                const fechaFinal = button.getAttribute("data-fechaFinal");

                document.getElementById("editGrupoId").value = grupoId;
                document.getElementById("editNombre").value = nombre;
                document.getElementById("editFechaInicio").value = fechaInicio;
                document.getElementById("editFechaFinal").value = fechaFinal;
                document.getElementById("editEstado").value = estado;

                editModal.style.display = "block";
            });
        });

        closeModal.addEventListener("click", () => {
            editModal.style.display = "none";
        });

        window.addEventListener("click", (event) => {
            if (event.target === editModal) {
                editModal.style.display = "none";
            }   
        });
    });
</script>
