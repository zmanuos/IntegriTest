<?php
    session_start();
    include 'header.php';
    include '../../includes/authentication.php';
    include ('../../models3/respuesta.php');
    include ('../../models3/pregunta.php');
    include ('../../models3/examen.php');
    include ('../../models3/temaExamen.php');
    include ('../../models3/alumnoExamen.php');

    checkAuthentication('alumno');

    $examen = examen:: get($_GET['examenNum']);

    $preguntas = pregunta::get_exam($_GET['examenNum']);

    $tema = temaExamen::get($_GET['examenNum']);

    $calificacion_examen = 0;

    $realizado = alumnoExamen::get( $_SESSION['identificador_usuario'] ,$_GET['examenNum']);


  $examen_enviar = $_GET['examenNum'];

  $color_enviar = $_GET['color'];

  $curso_enviar = $_GET['curso'];

  $profe_enviar = $_GET['profe'];

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <!--Styles-->
        <link rel="stylesheet" href="../css/examen.css">
        <!--Meta-->
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!--Title-->
        <link rel="icon" href="../../images/favicon/favicon.ico" type="image/x-icon">
        <title> Examen </title>
    </head>
    <body>
    
    <?php if(empty($realizado)){  ?>

    <div class="examen-info-div" style=" background-color: <?php echo '#'.$_GET['color']; ?> ;">
      <div class="examen-titulo">
        <label><?php if ($examen){ echo $examen -> getTitulo(); }?></label>
      </div>
      
      <div class="examen-tema">
        <label>Tema:  <?php if ($tema){ echo $tema -> getNumExam(); }?> </label>
      </div>
      
    </div>

  <form id="miFormulario" method="POST" >
    
<?php

  $i = 1;
  $valorExamen = 0;
  foreach ($preguntas as $pr) {

    $valorPregunta = $pr -> getValor();

    $valorExamen = $valorExamen + $valorPregunta;
    
    ?>
    
    <div class="pregunta-div" >
      <div class="numPregunta"><label class="pregunta-num" ><?php echo $i; ?>)</label></div>
      <div class="descripcion"><label class="pregunta-descripcion" > <?php echo $pr -> getDescripcion();?> </label></div>
      <div class="valor" ><label class="pregunta-valor" > <?php echo $pr -> getValor();?> puntos</label></div>
    </div>

    <?php $respuestas = respuesta::get_exam($pr -> getNumPregunta()); ?>

<div class="respuestas-div">
      <?php $ires= 1; if ($pr -> getCodeTipo() == "selmult" ){ foreach ($respuestas as $r) { ?>

        <div class="respuesta"> <label><input type="checkbox" name="<?php echo $i."[]"; ?>" value="<?php echo $r -> getValor(); ?>"> <?php echo $r -> getDescripcion(); ?></label></div>

      <?php $ires= $ires + 1 ; } } else { foreach ($respuestas as $r) { ?>

      <div class="respuesta"> <label class="respuesta-content"> <input class="input" type="radio" name="<?php echo $i;  ?>" value="<?php echo $r -> getValor(); ?>" required > <?php echo $r -> getDescripcion(); ?></label></div>
      <?php $ires= $ires + 1 ; } } ?>
  </div>

  <?php  $i = $i + 1; } ?>
  
  <button id="boton" type="submit" >Enviar Respuestas</button>

</form>

<?php
$i_respuesta = 1;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  
    foreach ($preguntas as $pr) {
    $valor_respuesta = $_POST[ $i_respuesta ];
    if($pr -> getCodeTipo() == "selmult" ){
      $i_sel=0;
      foreach ($valor_respuesta as $res){
    $calificacion_examen = $calificacion_examen + $res;
      } } else {
    $calificacion_examen = $calificacion_examen + $valor_respuesta;
    }
    $i_respuesta = $i_respuesta + 1;
  }
  


  $calificacion_final = $calificacion_examen/$valorExamen;

  if ($calificacion_final >= 0.98) $calificacion_final = 10;

  $calificacion_final = $calificacion_final * 10;
  
  if ($calificacion_final >= 0.98) $calificacion_final = 10;



  $AlumnoCalif = alumnoExamen::insert( $_GET['examenNum'], $_SESSION['identificador_usuario'], $calificacion_final);


}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {


  echo "<script>window.location.href = 'examen-especifico.php?examenNum=$examen_enviar&color=$color_enviar&curso= $curso_enviar&profe=$profe_enviar';</script>";
  exit();
  }

?>

<script>
        function mostrarAlerta() {
            alert("<?php echo "La suma total de las respuestas es ". $calificacion_examen. " respuestas ".$i_respuesta; ?>");
        }
    </script>

<?php } else { $calificacion = alumnoExamen::get($_SESSION['identificador_usuario'], $_GET['examenNum']);?>
  
  <div class="examen-realizado-info-div" style=" background-color: <?php echo '#'.$_GET['color']; ?> ;">
  
  <div class="examen-realizado-titulo">
        <label>Examen Realizado</label><div class="<?php
    if(isset($calificacion)){
        if ($calificacion -> getCalificacion() >= 8 && $calificacion -> getCalificacion() <= 10 ){
            echo "exam-aprobado";
        } else if($calificacion <= 8) {
            echo "exam-reprobado";
        }
    } else {
        echo "exam-no-realizado";
    }
?>">

<?php


    if(isset($calificacion)){
        echo $calificacion -> getCalificacion();
    } else {
        echo "0.0";
    }
?>
        </div>
      </div>

      
      <div class="examen-realizado-tema">
        <label> Examen: <?php if ($examen){ echo $examen -> getTitulo(); }?> </label>
      </div>
      
    </div>

    <button id="boton" type="submit" onclick="window.location.href='materia-exams.php?color=<?php echo $_GET['color']; ?>&cursonum=<?php echo $_GET['curso']; ?>&profe=<?php echo $_GET['profe']; ?>';">Regresar</button>

<?php } ?>

    </body>
