<?php
include("header.php");
include('../../includes/authentication.php');
include("../../models/tema.php");

checkAuthentication("administrador");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['numTema'])) {
        $numTema = $_POST['numTema']; 
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $cursonum = $_POST['cursonum'];

        if (empty($nombre) || empty($descripcion) || empty($cursonum)) {
            $_SESSION['responseMessage'] = "Todos los campos son obligatorios.";
            $_SESSION['showNotification'] = false;
        } else {
            $temaActualizado = tema::update($numTema, $nombre, $descripcion, $cursonum);

            if ($temaActualizado) {
                $_SESSION['responseMessage'] = "Tema actualizado exitosamente.";
                $_SESSION['showNotification'] = true;
            } else {
                $_SESSION['responseMessage'] = "Error al actualizar el tema.";
                $_SESSION['showNotification'] = false;
            }
        }
    } else {
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $cursonum = $_POST['cursonum'];

        if (empty($nombre) || empty($descripcion) || empty($cursonum)) {
            $_SESSION['responseMessage'] = "Todos los campos son obligatorios.";
            $_SESSION['showNotification'] = false;
        } else {
            $temaCreado = tema::create($nombre, $descripcion, $cursonum);

            if ($temaCreado) {
                $_SESSION['responseMessage'] = "Tema creado exitosamente.";
                $_SESSION['showNotification'] = true;
            } else {
                $_SESSION['responseMessage'] = "Error al crear el tema.";
                $_SESSION['showNotification'] = false;
            }
        }
    }

    header('Location: ' . $_SERVER['PHP_SELF'] . '?page=1');
    exit();
} else {
    if (isset($_SESSION['responseMessage'])) {
        $responseMessage = $_SESSION['responseMessage'];
        $showNotification = $_SESSION['showNotification'] ?? false;
        
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
$limit = 9;
$offset = ($page - 1) * $limit;

$temas = tema::search($searchId, $searchName, $limit, $offset);

$connection = connection::get_connection();
$totalTemasQuery = "SELECT COUNT(*) FROM tema WHERE nombre LIKE '%$searchName%' AND cursonum LIKE '%$searchId%'";
$result = $connection->query($totalTemasQuery);

if ($result) {
    $totalTemas = $result->fetch_row()[0];
    $totalPages = ceil($totalTemas / $limit);
} else {
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
    <title>Registrar Tema</title>
    <link rel="stylesheet" href="css/create_themes.css">
</head>
<body>
    <div id="notification" class="notification" style="display: <?= $showNotification ? 'block' : 'none'; ?>;">
        <?= $responseMessage; ?>
    </div>

    <div class="title">
        <div class="container-title">
            <h1>Gestión de Temas</h1>
        </div>
    </div>
    
    <div class="mega-container">
        <div class="form-wrapper">
            <h2>Registrar Tema</h2>
            <form id="temaForm" method="POST">
                <div class="form-group">
                    <label for="nombre">Nombre del tema:</label>
                    <input type="text" id="nombre" name="nombre" required>
                </div>

                <div class="form-group">
                    <label for="descripcion">Descripción:</label>
                    <textarea id="descripcion" name="descripcion" rows="4" required></textarea>
                </div>

                <div class="form-group">
                    <label for="cursonum">Curso:</label>
                    <select id="cursonum" name="cursonum" required>
                        <?php
                        $connection = connection::get_connection();
                        $query = "SELECT cursonum, nombre FROM curso";
                        $result = $connection->query($query);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<option value='" . $row['cursonum'] . "'>" . $row['nombre'] . "</option>";
                            }
                        } else {
                            echo "<option value=''>No hay cursos disponibles</option>";
                        }

                        $connection->close();
                        ?>
                    </select>
                </div>

                <div class="container-submit">
                    <button type="submit" class="btn-submit">Registrar</button>
                </div>
            </form>
        </div>

        <div class="courses-container">
            <div class="search-bar">
                <h2>Temas</h2>
                <form method="GET" action="">
                    <div class="search-item">
                        <input type="text" name="searchName" placeholder="Buscar por nombre" value="<?= $searchName ?>" class="search-input">
                    </div>
                    <button type="submit" class="search-btn"><i class="fa fa-search"></i></button>
                </form>
            </div>

            <?php if (count($temas) > 0): ?>
                <div class="courses-table">
                    <div class="table-header">
                        <div class="table-cell">ID</div>
                        <div class="table-cell">Nombre</div>
                        <div class="table-cell">Descripción</div>
                        <div class="table-cell">Curso</div>
                        <div class="table-cell">Acciones</div>
                    </div>

                    <?php foreach ($temas as $tema): ?>
                        <div class="table-row">
                            <div class="table-cell"><?= $tema->getNumTema() ?></div>
                            <div class="table-cell"><?= $tema->getNombre() ?></div>
                            <div class="table-cell"><?= $tema->getDescripcion() ?></div>

                            <?php
                            $connection = connection::get_connection();
                            $cursonum = $tema->getCursonum();
                            $query = "SELECT nombre FROM curso WHERE cursonum = ?";
                            $command = $connection->prepare($query);
                            $command->bind_param('i', $cursonum);
                            $command->execute();
                            $command->bind_result($cursoNombre);
                            $command->fetch();
                            mysqli_stmt_close($command);
                            $connection->close();

                            echo "<div class='table-cell'>{$cursoNombre}</div>";
                            ?>

                            <div class="table-cell">
                                <a href="#" class="edit-btn" data-id="<?= $tema->getNumTema() ?>" data-nombre="<?= $tema->getNombre() ?>" data-descripcion="<?= $tema->getDescripcion() ?>" data-cursonum="<?= $tema->getCursonum() ?>">Editar</a>
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
            <h3>Editar Tema</h3>
            <form id="editForm" method="POST">
                <input type="hidden" id="editTemaId" name="numTema">
                <div class="form-group">
                    <label for="editNombre">Nombre:</label>
                    <input type="text" id="editNombre" name="nombre" required>
                </div>

                <div class="form-group">
                    <label for="editDescripcion">Descripción:</label>
                    <textarea id="editDescripcion" name="descripcion" rows="4" required></textarea>
                </div>

                <div class="form-group">
                    <label for="editCursonum"></label>
                    <input type="hidden" id="editCursonum" name="cursonum" required>
                </div>

                <button type="submit" class="btn-submit">Guardar Cambios</button>
            </form>
        </div>
    </div>

    <script>
        const editBtns = document.querySelectorAll('.edit-btn');
        const modal = document.getElementById('editModal');
        const closeModal = document.querySelector('.close');
        
        editBtns.forEach(button => {
    button.addEventListener('click', function() {
        const id = this.dataset.id;
        const nombre = this.dataset.nombre;
        const descripcion = this.dataset.descripcion;
        const cursonum = this.dataset.cursonum;

        document.getElementById('editTemaId').value = id; 
        document.getElementById('editNombre').value = nombre;
        document.getElementById('editDescripcion').value = descripcion;
        document.getElementById('editCursonum').value = cursonum;

        modal.style.display = 'block';
    });
});
        closeModal.addEventListener('click', function() {
            modal.style.display = 'none';
        });

        window.addEventListener('click', function(event) {
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        });

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
