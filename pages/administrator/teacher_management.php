<?php
    include_once 'header.php';
    include_once '../../includes/authentication.php';
    include_once '../../models2/students.php';

    checkAuthentication('administrador');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="../../css/dashboard.css"/>
    <link rel="stylesheet" href="css/styles.css"/>
    <link rel="stylesheet" href="css/user_details.css"/>
    <link rel="icon" href="../../images/favicon/favicon.ico" type="image/x-icon">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Docentes</title>
</head>
<body>
<div class="content">
    <?php
    require_once('../../models2/teachers.php');

    $limit = 30;

    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    if ($page < 1) $page = 1;

    $offset = ($page - 1) * $limit;

    $searchTerm = '';
    if (isset($_GET['search'])) {
        $searchTerm = htmlspecialchars(trim($_GET['search']));
    }

    $totalRecords = Teacher::getTotalTeachers();
    $totalPages = ceil($totalRecords / $limit);

    if ($searchTerm) {
        $teachers = Teacher::searchTeacher($searchTerm, $limit, $offset);
    } else {
        $teachers = Teacher::getAll($limit, $offset);
    }
    ?>

    <div class="teacher">
        <div class="search">
            <form method="GET" action="teacher_management.php">
                <input type="text" name="search" placeholder="Buscar docente" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
                <button type="submit">Buscar</button>
            </form>
        </div>
        
        <table border="1px">
            <tr>
                <th>No. Empleado</th>
                <th>Docente</th>
                <th></th>
            </tr>
            <?php foreach ($teachers as $t) { ?>
                <tr>
                    <td><?php echo $t->getNumEmpleado(); ?></td>
                    <td><?php echo $t->getNombreCompleto(); ?></td>
                    <td><a href="#" onclick="enviarNumEmpleado('<?php echo $t->getNumEmpleado(); ?>')">Ver más</a></td>
                </tr>
            <?php } ?>
        </table>

        <div class="pagination" id="pagination-container">
            <?php if ($page > 1): ?>
                <a href="?page=<?php echo $page - 1; ?>&search=<?php echo $searchTerm; ?>"><i class="fas fa-caret-left"></i></a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?page=<?php echo $i; ?>&search=<?php echo $searchTerm; ?>" 
                class="<?php echo $i == $page ? 'active' : ''; ?>">
                <?php echo $i; ?>
                </a>
            <?php endfor; ?>

            <?php if ($page < $totalPages): ?>
                <a href="?page=<?php echo $page + 1; ?>&search=<?php echo $searchTerm; ?>"><i class="fas fa-caret-right"></i></a>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="../../js/pagination.js"></script>
<script src="js/get.js"></script>

</body>
</html>
