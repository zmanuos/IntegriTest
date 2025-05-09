<?php
    include_once 'header.php';
    include_once '../../includes/authentication.php';
    include_once '../../models/students.php';

    checkAuthentication('administrador');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="../../css/dashboard.css"/>
    <link rel="stylesheet" href="css/user_details.css"/>
    <link rel="icon" href="../../images/favicon/favicon.ico" type="image/x-icon">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Alumnos</title>
</head>
<body>
<div class="content">
    <?php
    require_once('../../includes/db_connection.php');
    require_once('../../models/students.php');

    $limit = 30;

    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    if ($page < 1) $page = 1;

    $offset = ($page - 1) * $limit;

    $searchTerm = '';
    if (isset($_GET['search'])) {
        $searchTerm = htmlspecialchars(trim($_GET['search']));
    }

    $totalRecords = Student::getTotalStudents();
    $totalPages = ceil($totalRecords / $limit);

    if ($searchTerm) {
        $students = Student::searchStudent($searchTerm, $limit, $offset);
    } else {
        $students = Student::getAll($limit, $offset);
    }
    ?>

    <div class="student">
        <div class="search">
            <form method="GET" action="student_management.php">
                <input type="text" name="search" placeholder="Buscar alumno" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
                <button type="submit">Buscar</button>
            </form>
        </div>
        
        <table border="1px">
            <tr>
                <th>Matrícula</th>
                <th>Alumno</th>
                <th></th>
            </tr>
            <?php foreach ($students as $s) { ?>
                <tr>
                    <td><?php echo $s->getMatricula(); ?></td>
                    <td><?php echo $s->getNombreCompleto(); ?></td>
                    <td><a href="#" onclick="enviarMatricula('<?php echo $s->getMatricula(); ?>')">Ver más</a></td>
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
