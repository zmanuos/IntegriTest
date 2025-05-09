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
    <title>Grupos</title>
</head>
<body>
<div class="content">
    <?php
    require_once('../../includes/db_connection.php');
    require_once('../../models2/groups.php');


    $numEmpleado = $_SESSION['identificador_usuario'];
    $groups = Group::getGroups($numEmpleado, 1);
    ?>

<div class="groups">
    <div class="grid-container" id="grid">
        <?php foreach ($groups as $index => $g) { ?>
            <div class="group-card" data-index="<?php echo $index; ?>">
                <div class="card-header" data-color-index="<?php echo $index; ?>">
                    <h3><?php echo htmlspecialchars($g->getGrupo()); ?></h3>
                </div>
                <div class="card-body">
                </div>
                <div class="card-footer">
                    <i class="fas fa-user-graduate" title="Ver alumnos" data-popup-id="popup-<?php echo $index; ?>"></i>
                    <i class="fas fa-info-circle" title="Detalles" data-popup-id="popup2-<?php echo $index; ?>"></i>
                </div>
            </div>
        <?php } ?>
    </div>

    <?php foreach ($groups as $index => $g) { ?>
        <div class="popup" id="popup-<?php echo $index; ?>">
            <div class="popup-contenido">
                <span class="cerrarPopup" data-popup-id="popup-<?php echo $index; ?>">&times;</span>
                <h1>Alumnos</h1>
                <label><?php echo $g->getCantidadAlumnos(); ?> estudiantes<label>
                <div class="datosPopup"> 
                
                    <?php 
                    $grupo = $g->getGrupo();
                    $students = Group::getStudents($grupo); ?>
        
            
                    <?php foreach ($students as $s) { ?>
                        <span class="student-card"><?php echo $s->getNombreCompleto(); ?><br></span>
                    <?php } ?>
                </div>
            </div>
        </div>
    <?php } ?>

    <?php foreach ($groups as $index => $g) { ?>
        <div class="popup" id="popup2-<?php echo $index; ?>">
            <div class="popup-contenido">
                <span class="cerrarPopup" data-popup-id="popup2-<?php echo $index; ?>">&times;</span>
                <h2>Cursos impartidos</h2>
                <div class="datosPopup"> 
                
                    <?php 
                    
                    $courses = Group::getCourses($grupo, $numEmpleado);
                    ?>
        
            
                    <?php foreach ($courses as $c) { ?>
                        <span class="student-card"><?php echo $c->getCurso(); ?><br></span>
                    <?php } ?>
                </div>
            </div>
        </div>
    <?php } ?>

    
</div>

</div>

<script>
    window.colores = <?php echo json_encode($_SESSION['colores']); ?>;
</script>

<script src="js/colors.js"></script>
<script src="js/script.js"></script>


</body>
</html>
