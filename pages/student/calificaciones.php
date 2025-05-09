<?php
    session_start();

    
    include 'header.php';
    include ('../../models3/grupohistorial.php');
    include ('../../models3/materia.php');
    include ('../../models3/grupo.php');
    include ('../../models3/curso.php');
    include ('../../models3/student.php');
    include ('../../models3/registro.php');
    include '../../includes/authentication.php';

    checkAuthentication('alumno');
    
    $identificador = $_SESSION['identificador_usuario'];

    $image_base_path = '../../images/users/' . $identificador;
$default_image = '../../images/default/default.png';
$allowed_extensions = ['jpg', 'jpeg', 'png'];
$image_url = $default_image;

foreach ($allowed_extensions as $extension) {
    $image_path = $image_base_path . '.' . $extension;
    if (file_exists($image_path)) {
        $image_url = $image_path;
        break;
    }
}

    $promedio = curso::get_promedios($_SESSION['identificador_usuario'], 1);


    $a = student::get($_SESSION['identificador_usuario']);

    $grupo = registro::get_grupo($_SESSION['identificador_usuario']);

$grupo_pertenecio = grupohistorial::get($_SESSION['identificador_usuario']);

    $cuatrimestre = 0;

    $i_calif = 0;
    $calificacion_materias = 0;

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <link rel="stylesheet" href="../css/calificaciones.css">

        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <link rel="icon" href="../../images/favicon/favicon.ico" type="image/x-icon">
        <title>Calificaciones</title>
    </head>
    <body>
    
    <div class="alumno-div">

<div id="alumno-info">
    <div id="alumno-labels">
        <label class="alumno-label">Matricula: <?php echo $a->getMatricula(); ?></label><br>
        <label class="alumno-label">Alumno: <?php echo $a->getNombre()." ".$a->getApellidoM()." ".$a->getApellidoP(); ?></label><br>
        <label class="alumno-label">Examenes Realizados: <?php echo $a->getCantidadExams(); ?></label><br>
        <label class="alumno-label">Promedio General: <?php echo $a->getPromedio(); ?></label><br>
        <label class="alumno-label" id="promedio">Promedio Cuatrimestral: <?php echo $cuatrimestre ?></label>
    </div>
    <img src="<?php echo $image_url; ?>" alt="Imagen de perfil" id="alumno-photo">
    </div>

    
    <div class="alumno-grupo">
        <label> Grupo: <?php echo $grupo->getGruponombre(); ?></label>
    </div>
</div>
<?php
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


<?php

if (isset($grupo_nombre)){ ?>

<?php

if(!empty($materia_get_all)){
foreach($materia_get_all as $as){

$color = $colores[$it % count($colores)];

    if($i == 2){    $i = 0;}
if($i == 0){ ?>
<div class="subject-row">
<?php  }?>
    <div class="materia-div">
    <div class="up-information" >
            <div class="materia-name"  style="background-color: <?php echo '#'.$color ?>;"><?php echo $as->getcursoNombre();?></div>
            
        <div class="right-information">
            <?php
    $promedio = curso::get_promedios($_SESSION['identificador_usuario'], $as->getCursonum());

    $i_calif = $i_calif + 1;

    try {
        if ($promedio -> getDuracion() == null){
            echo 0;
        } else {
            echo number_format($promedio -> getDuracion(), 1);
        }
    } catch (\Throwable $th) {
        echo 0;
    }

    try {

        $calificacion_materias = $calificacion_materias + $promedio -> getDuracion();

    } catch (\Throwable $th) {
        $calificacion_materias = $calificacion_materias + 0;
    }


    
?>

        </div>
        </div>
        
    </div>

<?php
    $i = $i + 1;
    $it = $it + 1;
    
        if($i == 2){
            ?>
</div>
<?php  }?>

    <?php
}

$cuatrimestre = $calificacion_materias / $i_calif;

?>
    <script> document.getElementById('promedio').textContent = "Promedio Cuatrimestral: <?php echo number_format($cuatrimestre, 2); ?>"; </script>


<?php } else { ?>
    <script> document.getElementById('grupo-message').textContent = "No Se Cuenta Con Materias Actualmente"; </script>
<?php }?>

<?php } else if (empty($grupo_nombre) || $grupo_nombre == null ){  ?>

<div id="group"> <label id="grupo-message" class="label-grupo"></label></div>
<script> document.getElementById('grupo-message').textContent = "<?php echo 'No se tiene grupo asignado' ?>"; </script>

<?php } ?>

<div class="grupos-div">

<div id="grupos_pertenecio"> Historial de grupos </div>

<?php
    if(isset($grupo_pertenecio)){

        foreach($grupo_pertenecio as $gp){
?>

<div id="grupos-info">
    <div class="grupo-pertenecio-divs">
        <div class="grupo-nombre"> <?php echo $gp -> getGrupoNombre() ?> </div>
        <div class="grupo-fecha-inicio"> <?php echo $gp -> getFechaInicio() ?></div>
        <div class="grupo-fecha-final"> <?php echo $gp -> getFechaFinal() ?></div>
        <div class="grupo-alumno"> <?php echo $gp -> getAlumnoNombre() ?></div>
        <div class="grupo-promedio"> <?php echo $gp -> getPromedioAlumno() ?></div>
    </div>
</div>
<?php }

}  else { ?>
    <div id="grupos-info">
        <div class="grupo-pertenecio-divs">
            No se ha pertenecido a otro grupo
        </div>
    </div>

    <?php } ?>

    </div>
</body>