<?php
    include 'header.php';
    include '../../includes3/authentication.php';
    include ('../../models3/respuesta.php');
    include ('../../models3/pregunta.php');
    include ('../../models3/examen.php');
    include ('../../models3/temaExamen.php');
    include ('../../models3/alumnoExamen.php');
    include ('../../includes/db_connection.php');

    checkAuthentication('alumno');

    $examen = examen:: get($_GET['examenNum']);

    $preguntas = pregunta::get_exam($_GET['examenNum']);

    $tema = temaExamen::get($_GET['examenNum']);

    $calificacion_examen = 0;

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
        <title> Examenes </title>
    </head>
    <body>
    

    <div class="examen-info-div">
      <div class="examen-titulo">
        <label><?php if ($examen){ echo $examen -> getTitulo(); }?></label>
      </div>
      
      <div class="examen-tema">
        <label>Tema:  <?php if ($tema){ echo $tema -> getNumExam(); }?> </label>
      </div>
      
    </div>
    <!--
    <div class="pregunta-div">
      <div class="numPregunta"><label class="pregunta-num" >1)</label></div>
      <div class="descripcion"><label class="pregunta-descripcion" >hola</label></div>
      <div class="valor" ><label class="pregunta-valor" >20 puntos</label></div>
    </div>
  -->

  <form action="form_examen-especifico.php">
    <?php 
    
    echo "tu calificacion es: ".$calificacion_examen;

  
  
  
  $i = 1;
  foreach ($preguntas as $pr) {
    ?>
    
    <div class="pregunta-div">
      <div class="numPregunta"><label class="pregunta-num" ><?php echo $i; ?>)</label></div>
      <div class="descripcion"><label class="pregunta-descripcion" > <?php echo $pr -> getDescripcion();?> </label></div>
      <div class="valor" ><label class="pregunta-valor" > <?php echo $pr -> getValor();?> puntos</label></div>
    </div>
    <?php
      $respuestas = respuesta::get_exam($pr -> getNumPregunta());
      ?>
<div class="respuestas-div">
    
    
      <?php
      $ires= 1;

      
      if ($pr -> getCodeTipo() == "selmult" ){
        foreach ($respuestas as $r) {
          ?>
        <div class="respuesta"> <label><input type="checkbox" name="<?php echo $i; ?>" value="<?php echo $r -> getValor(); ?>"> <?php echo $r -> getDescripcion(); ?></label></div>
        <?php
        $ires= $ires + 1 ;
      }

      } else {

      foreach ($respuestas as $r) {
        ?>
      <div class="respuesta"> <label class="respuesta-content"> <input class="input" type="radio" name="<?php echo $i; ?>" value="<?php echo $r -> getValor(); ?>"> <?php echo $r -> getDescripcion(); ?></label></div>
      <?php
      $ires= $ires + 1 ;
    }
  } ?>
      </div>
    
  <?php
  $i = $i + 1;
  }
  ?>
  
  <button type="submit" onclick="mostrarAlerta()">Enviar Respuestas</button>

</form>


    <!--

<?php
$i_respuesta = 1;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  
    $respuestas = $_POST;

    
    foreach ($preguntas as $pr) {

      if($i_respuesta = 1){
        echo "hola";
      }

    $valor_respuesta = $_POST[ $i_respuesta ];

    $calificacion_examen = $calificacion_examen + $valor_respuesta;

    $i_respuesta = $i_respuesta + 1;
  }

  echo "La suma total de las respuestas es". $calificacion_examen;
}
?>

<script>

        function mostrarAlerta() {
            alert("<?php echo "La suma total de las respuestas es ". $calificacion_examen. " respuestas ".$i_respuesta; ?>
                  <?php echo $_POST[ $i_respuesta ]. " y "; ?>");

        }

    </script>

    </body>



    
    
    <form>

  <h3>Encuesta rápida</h3>

  <p>1. ¿Cuál es tu color favorito?</p>
  <label>
    <input type="radio" name="color" value="rojo"> Rojo
  </label>
  <label>
    <input type="radio" name="color" value="azul"> Azul
  </label>
  <label>
    <input type="radio" name="color" value="verde"> Verde
  </label>

  <p>2. ¿Qué tecnologías has usado?</p>
  <label>
    <input type="checkbox" name="tecnologia" value="html"> HTML
  </label>
  <label>
    <input type="checkbox" name="tecnologia" value="css"> CSS
  </label>
  <label>
    <input type="checkbox" name="tecnologia" value="javascript"> JavaScript
  </label>
  <label>
    <input type="checkbox" name="tecnologia" value="python"> Python
  </label>
</form>
Title-->