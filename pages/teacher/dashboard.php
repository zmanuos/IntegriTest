<?php
include 'header.php';
include '../../includes/authentication.php';
include '../../models2/students.php';

checkAuthentication('maestro');

if (!isset($_SESSION['colores'])) {
    $colores = ['#FF5733', '#33FF57', '#3357FF', '#F39C12', '#9B59B6', '#1ABC9C', '#E74C3C'];
    shuffle($colores);
    $_SESSION['colores'] = $colores;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="../../css/dashboard.css"/>
    <link rel="stylesheet" href="css/group.css"/>
    <link rel="stylesheet" href="css/styles.css"/>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" href="../../images/favicon/favicon.ico" type="image/x-icon">
    <title>Cursos</title>
</head>
<body>
<div class="content">
    <?php
    require_once('../../models2/courses.php');


    $numEmpleado = $_SESSION['identificador_usuario'];
    $courses = Course::getCourses($numEmpleado, 1);
    ?>

<div class="courses">
    <div class="grid-container" id="grid">
        <?php foreach ($courses as $index => $c) { ?>
            <div class="course-card" data-index="<?php echo $index; ?>">
                <div class="card-header" data-color-index="<?php echo $index; ?>">
                    <h3><?php echo htmlspecialchars($c->getCurso()); ?></h3>
                </div>
                <div class="card-body">
                    <label><?php echo htmlspecialchars($c->getDescripcion()); ?></label>
                </div>
                <div class="card-footer">
                    <label>Duración: <?php echo htmlspecialchars($c->getDuracion()); ?> horas</label>
                </div>
            </div>
        <?php } ?>
    </div>

    
</div>

</div>

<script>
    window.colores = <?php echo json_encode($_SESSION['colores']); ?>;
</script>

<script src="js/colors.js"></script>
<script src="js/script.js"></script>


</body>
</html>
