<?php
    session_start();
    
    include 'header.php';
    include ('../../models3/materia.php');
    include ('../../models3/grupo.php');
    include ('../../models3/examen.php');
    include ('../../models3/registro.php');
    include ('../../models3/alumnoExamen.php');
    include '../../includes/authentication.php';

    checkAuthentication('alumno');

$grupo = registro::get_grupo($_SESSION['identificador_usuario']);

if ($grupo) {
    $grupo_id = $grupo -> getGrupo();
    $grupo_nombre = $grupo -> getGruponombre();
}

$materia_get_all = materia::get($_SESSION['identificador_usuario']);

$colores = [
    "B71C1C", "0D47A1", "1B5E20", "F57F17", "4A148C", "006064", "006064", "004D40", "1B5E20", "33691E", "263238", "212121"];

    $i = 0;
    $it = 0;
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <!--style-->
        <link rel="stylesheet" href="../css/materia.css">
        <!--Meta-->
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!--Title-->
        <link rel="icon" href="../../images/favicon/favicon.ico" type="image/x-icon">
        <title> Materias </title>
    </head>
    <body>

<?php

if (isset($grupo_nombre)){ ?>

    <div id="group"> <label id="grupo-message" class="label-grupo"> Grupo <?php echo $grupo_nombre; ?> </label> </div>
<?php

if(!empty($materia_get_all)){
foreach($materia_get_all as $as){

$color = $colores[$it % count($colores)];

    if($i == 3){    $i = 0;}
    
    if($i == 0){ ?>
<div class="subject-row">
<?php  }?>
    <div class="materia-div" style="background-color: <?php echo '#'.$color ?>;"  >
    <div class="up-information" onclick="window.location.href='materia-exams.php?cursonum=<?php echo $as->getCursonum();?>&color=<?php echo $color;?>&profe=<?php echo $as->getprofesorNombre();?>';">
            <div class="materia-name"><?php echo $as->getcursoNombre();?></div>
            <div class="docente-name"><?php echo $as->getprofesorNombre(); ?></div>
        </div>
        <div class="down-information">
<?php $sin_realizar = examen::get_examenes_sin_realizar($_SESSION['identificador_usuario'], $as->getCursonum());

foreach($sin_realizar as $sin ){ $hayExamenes = alumnoExamen::get( $_SESSION['identificador_usuario'], $sin -> getNumExam()); }

if (empty($sin_realizar)){
    ?>
    <div class="materia-info">No hay examen(es) por realizar</div>
<?php } else {
    if (isset($hayExamenes)){
        ?>
        <div class="materia-info">No hay examen(es) por realizar</div>
    <?php 
    } else {
        ?>
        <div class="materia-info">hay examen(es) por realizar</div>
    <?php 
    }
?>
<?php } ?>
        </div>
    </div>

<?php
    $i = $i + 1;
    $it = $it + 1;
    if($i == 3){
    ?>
        </div>
        
    <?php
    }
}
?>

<?php } else { ?>
    <script> document.getElementById('grupo-message').textContent = "No Se Cuenta Con Materias Actualmente"; </script>
<?php }?>

<?php } else if (empty($grupo_nombre) || $grupo_nombre == null ){  ?>

<div id="group"> <label id="grupo-message" class="label-grupo"></label></div>
<script> document.getElementById('grupo-message').textContent = "<?php echo 'No se tiene grupo asignado' ?>"; </script>

<?php } ?>


</body>