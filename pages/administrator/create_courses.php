<?php
include ("header.php");
include ('../../includes/authentication.php');
include ("../../models/curso.php");

checkAuthentication("administrador");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['id'])) {
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $duracion = $_POST['duracion'];
        $estado = $_POST['estado'];

        if (empty($nombre) || empty($descripcion) || empty($duracion) || !isset($estado)) {
            $_SESSION['responseMessage'] = "Todos los campos son obligatorios.";
            $_SESSION['showNotification'] = false; 
        } else {
            $cursoCreado = curso::create($nombre, $descripcion, $duracion, $estado);

            if ($cursoCreado) {
                $_SESSION['responseMessage'] = "Curso creado exitosamente.";
                $_SESSION['showNotification'] = true; 
            } else {
                $_SESSION['responseMessage'] = "Error al crear el curso.";
                $_SESSION['showNotification'] = false;
            }
        }
    } else {
        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $duracion = $_POST['duracion'];
        $estado = $_POST['estado'];

        if (empty($nombre) || empty($descripcion) || empty($duracion) || !isset($estado)) {
            $_SESSION['responseMessage'] = "Todos los campos son obligatorios.";
            $_SESSION['showNotification'] = false; 
        } else {
            $cursoActualizado = curso::update($id, $nombre, $descripcion, $duracion, $estado);

            if ($cursoActualizado) {
                $_SESSION['responseMessage'] = "Curso actualizado exitosamente.";
                $_SESSION['showNotification'] = true; 
            } else {
                $_SESSION['responseMessage'] = "Error al actualizar el curso.";
                $_SESSION['showNotification'] = false; 
            }
        }
    }

    header('Location: ' . $_SERVER['PHP_SELF'] . '?page=1');
    exit(); 
} else {
    if (isset($_SESSION['responseMessage'])) {
        $responseMessage = $_SESSION['responseMessage'];
        $showNotification = $_SESSION['showNotification'];

        unset($_SESSION['responseMessage']);
        unset($_SESSION['showNotification']);
    } else {
        $responseMessage = "";
        $showNotification = false; 
    }
}

$searchId = isset($_GET['searchId']) ? $_GET['searchId'] : '';
$searchName = isset($_GET['searchName']) ? $_GET['searchName'] : '';

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit =9 ; 
$offset = ($page - 1) * $limit; 

$cursos = curso::search($searchId, $searchName, $limit, $offset);

$connection = connection::get_connection();
$totalCursosQuery = "SELECT COUNT(*) FROM curso WHERE nombre LIKE '%$searchName%' AND cursonum LIKE '%$searchId%'";
$result = $connection->query($totalCursosQuery);

if ($result) {
    $totalCursos = $result->fetch_row()[0];
    $totalPages = ceil($totalCursos / $limit); 
} else {
    $errorMessage = "Error al contar los cursos: " . $connection->error;
    $totalPages = 0;
}
$connection->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../images/favicon/favicon.ico" type="image/x-icon">
    <title>Registrar Curso</title>
    <link rel="stylesheet" href="css/create_courses.css">
</head>
<body>
    <div id="notification" class="notification">
        Curso registrado exitosamente
    </div>

    <div class="title">
        <div class="container-title">
            <h1>Gestión de Cursos</h1>
        </div>
    </div>
    <div class="mega-container">
    <div class="form-wrapper">
        <h2>Registrar Curso</h2>
        <form id="cursoForm" method="POST">
            <div class="form-group">
                <label for="nombre">Nombre del curso:</label>
                <input type="text" id="nombre" name="nombre" required>
            </div>

            <div class="form-group">
                <label for="descripcion">Descripción:</label>
                <textarea id="descripcion" name="descripcion" rows="4" required></textarea>
            </div>

            <div class="form-group">
                <label for="duracion">Duración (Horas):</label>
                <input type="number" id="duracion" name="duracion" min="1" required>
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
        <h2>Cursos</h2>
            <form method="GET" action="">
                <div class="search-item">
                    <input type="text" name="searchId" placeholder="Buscar por ID" value="<?= $searchId ?>" class="search-input">
                </div>
                <div class="search-item">
                    <input type="text" name="searchName" placeholder="Buscar por nombre" value="<?= $searchName ?>" class="search-input">
                </div>
                <button type="submit" class="search-btn"><i class="fa fa-search"></i></button>
            </form>
        </div>

        <?php if (count($cursos) > 0): ?>
            <div class="courses-table" id="coursesTable">
                <div class="table-header">
                    <div class="table-cell">ID</div> 
                    <div class="table-cell">Nombre</div>
                    <div class="table-cell">Descripción</div>
                    <div class="table-cell">Duración</div>
                    <div class="table-cell">Estado</div>
                    <div class="table-cell">Acciones</div>
                </div>

                <?php foreach ($cursos as $curso): ?>
                    <div class="table-row">
                        <div class="table-cell"><?= $curso->getCursonum() ?></div> 
                        <div class="table-cell"><?= $curso->getNombre() ?></div>
                        <div class="table-cell"><?= $curso->getDescripcion() ?></div>
                        <div class="table-cell"><?= $curso->getDuracion() ?> Horas</div>
                        <div class="table-cell"><?= $curso->getNumEstado() == 1 ? 'Activo' : 'Inactivo' ?></div>
                        <div class="table-cell">
                            <a href="#" class="edit-btn" data-id="<?= $curso->getCursonum() ?>" data-nombre="<?= $curso->getNombre() ?>" data-descripcion="<?= $curso->getDescripcion() ?>" data-duracion="<?= $curso->getDuracion() ?>" data-estado="<?= $curso->getNumEstado() ?>">Editar</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="?page=<?= $page - 1 ?>&searchId=<?= $searchId ?>&searchName=<?= $searchName ?>" class="btn-prev">Anterior</a>
                <?php endif; ?>

                <?php if ($page < $totalPages): ?>
                    <a href="?page=<?= $page + 1 ?>&searchId=<?= $searchId ?>&searchName=<?= $searchName ?>" class="btn-next">Siguiente</a>
                <?php endif; ?>
            </div>

        <?php else: ?>
            <p id="b-message">No se encontraron coincidencias</p>
        <?php endif; ?>
    </div>
    </div>

    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h3>Editar Curso</h3>
            <form id="editForm" method="POST">
                <input type="hidden" id="editCourseId" name="id">
                <div class="form-group">
                    <label for="editNombre">Nombre:</label>
                    <input type="text" id="editNombre" name="nombre" required>
                </div>

                <div class="form-group">
                    <label for="editDescripcion">Descripción:</label>
                    <textarea id="editDescripcion" name="descripcion" rows="4" required></textarea>
                </div>

                <div class="form-group">
                    <label for="editDuracion">Duración (Horas):</label>
                    <input type="number" id="editDuracion" name="duracion" min="1" required>
                </div>

                <div class="form-group">
                    <label for="editEstado">Estado:</label>
                    <select id="editEstado" name="estado" required>
                        <option value="1">Activo</option>
                        <option value="0">Inactivo</option>
                    </select>
                </div>

                <button type="submit" class="btn-submit">Actualizar Curso</button>
            </form>
        </div>
    </div>
    <div id="notification" class="notification" style="display: <?= $_SESSION['showNotification'] ? 'block' : 'none'; ?>;">
    <?= $_SESSION['responseMessage']; ?>
</div>

    <script>
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const id = this.getAttribute('data-id');
                const nombre = this.getAttribute('data-nombre');
                const descripcion = this.getAttribute('data-descripcion');
                const duracion = this.getAttribute('data-duracion');
                const estado = this.getAttribute('data-estado');

                document.getElementById('editCourseId').value = id; 
                document.getElementById('editNombre').value = nombre;
                document.getElementById('editDescripcion').value = descripcion;
                document.getElementById('editDuracion').value = duracion;
                document.getElementById('editEstado').value = estado;

                document.getElementById('editModal').style.display = 'block';
            });
        });


        <?php if ($showNotification): ?>
            document.getElementById("notification").style.display = "block";
            setTimeout(function() {
                document.getElementById("notification").style.display = "none";
            }, 4000); 
        <?php endif; ?>
        

        document.querySelector('.close').addEventListener('click', function() {
            document.getElementById('editModal').style.display = 'none';
        });


    function searchCourses() {
    const searchId = document.querySelector('input[name="searchId"]').value;
    const searchName = document.querySelector('input[name="searchName"]').value;
    const page = 1; 

    const xhr = new XMLHttpRequest();
    xhr.open('GET', `?searchId=${searchId}&searchName=${searchName}&page=${page}`, true);
    
    xhr.onload = function() {
        if (xhr.status === 200) {
            const response = xhr.responseText;
            document.getElementById('coursesTable').innerHTML = response;
        }
    };

    xhr.send();
}

document.querySelector('form').addEventListener('submit', function(e) {
    e.preventDefault(); 
    searchCourses(); 
});
    
        
    </script>
</body>
</html>
