<?php
    include 'header.php';
    include ('../../models3/grupohistorial.php');
    include ('../../models3/materia.php');
    include ('../../models3/grupo.php');
    include ('../../models3/curso.php');
    include ('../../models3/teacher.php');
    include ('../../models3/student.php');
    include ('../../models3/asignacion.php');
    include ('../../models3/registro.php');
    include '../../includes/authentication.php';

    checkAuthentication('maestro');
    
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


    $a = teacher::get($_SESSION['identificador_usuario']);

    $grupo = registro::get_grupo($_SESSION['identificador_usuario']);

    $grupo_pertenecio = grupohistorial::get($_SESSION['identificador_usuario']);

    $cursos = asignacion::get_curso_docente($_SESSION['identificador_usuario']);

$it = 0;
foreach($cursos as $c){
    $it ++;
}


    $cuatrimestre = 0;

    $i_calif = 0;
    $calificacion_materias = 0;

    //$misCursos = asignacion::get_cursos_docente($numEmpleado);

/*foreach ($misCursos as $curso) {
    echo "Curso Número: " . $curso['cursonum'] . "<br>";
    echo "Nombre del Curso: " . $curso['nombre'] . "<br>";
    echo "Descripción: " . $curso['descripcion'] . "<br>";
    echo "Duración: " . $curso['duracion'] . "<br>";
    echo "Estado: " . $curso['estado'] . "<br><br>";
}*/

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
        <label class="alumno-label">Numero de Empleado: <?php echo $a->getNumEmpleado(); ?></label><br>
        <label class="alumno-label">Docente: <?php echo $a->getNombre()." ".$a->getApellidoM()." ".$a->getApellidoP(); ?></label><br>
        <label class="alumno-label">Cursos Asignados: <?php echo $it; ?></label><br>
    </div>
    <img src="<?php echo $image_url; ?>" alt="Imagen de perfil" id="alumno-photo">
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
/*
foreach($cursos as $c){
    $curso = curso::get($c -> getCursonum());
    $grupo = grupo::get($c -> getGrupo());
    echo $curso -> getNombre();
    echo $grupo -> getNombre();
    echo $c -> getPromedio();
}
*/
if (isset($cursos)){ ?>

<?php

if(!empty($cursos)){
foreach($cursos as $c){

    $curso = curso::get($c -> getCursonum());
    $grupo = grupo::get($c -> getGrupo());

    $promedio = asignacion::getPromediosPorCurso($c -> getCursonum(), $_SESSION['identificador_usuario'], $c -> getGrupo() );

$color = $colores[$it % count($colores)];

    if($i == 2){    $i = 0;}
if($i == 0){ ?>
<div class="subject-row">
<?php  }?>
    <div class="materia-div">
    <div class="up-information" >
            <div class="materia-name"  style="background-color: <?php echo '#'.$color ?>;"><?php echo $grupo -> getNombre()." - ". $curso -> getNombre();?></div>
            
        <div class="right-information">
            <?php
            if (isset($promedio)){
            echo number_format($promedio -> getPromedio(), 2);
        } else echo "0";
    
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


?>
    <script> document.getElementById('promedio').textContent = "Promedio Cuatrimestral: <?php echo number_format($cuatrimestre, 2); ?>"; </script>


<?php } else { ?>
    <script> document.getElementById('grupo-message').textContent = "No Se Cuenta Con Materias Actualmente"; </script>
<?php }?>

<?php } else {  ?>

<div id="group"> <label id="grupo-message" class="label-grupo"></label></div>
<script> document.getElementById('grupo-message').textContent = "<?php echo 'No se tiene grupo asignado' ?>"; </script>

<?php } ?>

<div class="grupos-div">
<?php
    if(isset($grupo_pertenecio)){

        foreach($grupo_pertenecio as $gp){
?>
<?php }

} ?>

    </div>
</body>