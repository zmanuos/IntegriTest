<?php
    session_start();
    include 'header.php';
    include '../../includes/authentication.php';
    include ('../../models3/asignacion.php');
    include ('../../models3/respuesta.php');
    include ('../../models3/teacher.php');
    include ('../../models3/curso.php');
    include ('../../models3/pregunta.php');
    include ('../../models3/tipopregunta.php');
    include ('../../models3/examen.php');
    include ('../../models3/tema.php');
    include ('../../models3/temaExamen.php');

    checkAuthentication('maestro');


    $examen = examen:: get($_GET['examenNum']);

    $temaexamen = temaExamen::get($_GET['examenNum']);

    $preguntas = pregunta::get_exam($_GET['examenNum']);

    $examen_curso = examen::get_curso_examen($_GET['examenNum']);

    $tema_seleccionado = tema::get($_GET['tema']);
    
    $temas = tema::get_temacurso($examen_curso -> getInicioExamen());

    $tipo = tipopregunta::get();

    $calificacion_examen = 0;

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <link rel="stylesheet" href="css/examen.css">

        <meta charset="UTF-8">
        
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" href="../../images/favicon/favicon.ico" type="image/x-icon">
        <title> Examenes </title>
    </head>
    <body>

    <form id="miFormulario" method="POST" action="" >

    <div class="examen-info-div">
    <div class="examen-titulo">
        <input id="titulo" name="titulo" type="text" value="<?php echo $examen -> getTitulo();?>">
        
    </div>
    
    <div class="examen-tema">
    <select name="tema" id="examen-tema-select" class="select">
        <option id="placeholder" value="<?php echo $temaexamen -> getNumTema(); ?>" selected> <?php echo $temaexamen -> getNumExam(); ?> </option>';
    <?php foreach ($temas as $t) {?>
        <option value="<?php echo $t->getNumTema() ?>">  <?php echo $t->getNombre() ?> </option>
    <?php } ?>
    </select>
    </div>
</div>


    
<?php
    $i = 1;
    $valorExamen = 0;
    foreach ($preguntas as $pr) {

    $valorPregunta = $pr -> getValor();
    $valorExamen = $valorExamen + $valorPregunta;
    
    ?>
    
    <!--------------------------------------------------- Inicio Pregunta ingresadas -------------------------------------------------------------->
    <div class="pregunta-div" >
        <div class="numPregunta"><label class="pregunta-num" ><?php echo $i; ?>)</label></div>
    
        
    <!-- Descripcion de la pregunta -->
    <div class="descripcion-ingresada">
        <textarea name="descripcion-pregunta-<?php echo $i;?>" id="pregunta" placeholder="Ingresa la pregunta..."><?php echo $pr -> getDescripcion();?></textarea>
        <input style="display: none;" type="text" name="id-pregunta-<?php echo $i;?>" value="<?php echo $pr->getNumPregunta(); ?>">
    </div>

    <!-- Valor de la pregunta -->
        <div class="valor" ><input class="input-valor" type="text" name="valor-pregunta-<?php echo $i;?>" value="<?php echo $pr -> getValor();?>" min="0" max="100" > <label class="pregunta-valor" >  Puntos</label></div>
    </div>

    <!--------------------------------------------------- Pregunta ingresadas fin -------------------------------------------------------------->



    <!--------------------------------------------------- Inicio Preguntas por ingresar --------------------------------------------------------------
        <div class="pregunta-div" >
        <div class="numPregunta"><label class="pregunta-num" ><?php //echo $i; ?>)</label></div>
    
    <div class="descripcion-ingresando">
        <textarea name="descripcion-pregunta-<?php //echo $i;?>" id="pregunta" placeholder="Ingresa la pregunta..."><?php //echo $pr -> getDescripcion();?></textarea>
    </div>

        <div class="tipo">
            <?php // $tipo_seleccionado = tipopregunta::get($pr -> getCodeTipo()); ?>
            
        <select name="tipo-pregunta-<?php // echo $i;?>" id="select-tipo" class="select" required>
        <option id="placeholder" value="<?php //echo $tipo_seleccionado -> getCodeTipo(); ?>" disabled selected> <?php //echo $tipo_seleccionado -> getDescripcion(); ?> </option>;

    <?php //foreach ($tipo as $t) {?>
        <option value="<?php //echo $t->getCodeTipo(); ?>">  <?php //echo $t->getDescripcion() ?> </option>
    <?php //} ?>
    </select>
        </div>

        <div class="valor" ><input class="input-valor" type="text" name="valor-pregunta-<?php //echo $i;?>" value="<?php //echo $pr -> getValor();?>"> </input> <label class="pregunta-valor" >  Puntos</label></div>
    </div>

    ------------------------------------------------------ Preguntas por ingresar fin -------------------------------------------------------------->
    

    <?php $respuestas = respuesta::get_exam($pr -> getNumPregunta()); ?>
    <div class="respuestas-div">
    <?php
    $ires = 1;
    if ($pr->getCodeTipo() == "selmult") {
        // Para respuestas de tipo múltiple
        foreach ($respuestas as $r) { ?>
            <div class="respuesta">
                <?php if($r -> getValor() > 0){ ?>
                <input type="checkbox" name="<?php echo $i.'[]'; ?>" value="<?php echo $r->getNumRespuesta(); ?>" checked>
            <?php } else { ?>
                <input type="checkbox" name="<?php echo $i.'[]'; ?>" value="<?php echo $r->getNumRespuesta(); ?>" >
                <?php } ?>
                <input class="descripcion-respuesta" type="text" name="<?php echo "descripcion-respuesta-".$i."-".$ires?>" value="<?php echo $r->getDescripcion(); ?>" placeholder="Ingrese descripción de la respuesta">
                <input style="display: none;" type="text" name="<?php echo "id-respuesta-".$i."-".$ires?>" value="<?php echo $r->getNumRespuesta(); ?>">
            </div>
        <?php
            $ires++;
        }
    } else if ($pr->getCodeTipo() == "opcmult") {
        foreach ($respuestas as $r) { ?>
            <div class="respuesta">
                <label class="respuesta-content">
                <?php if($r -> getValor() > 0){ ?>
                    <input class="input" type="radio" name="respuesta-correcta-pregunta-<?php echo $i; ?>" value="<?php echo $r -> getNumRespuesta(); ?>" required checked>
            <?php } else { ?>
                <input class="input" type="radio" name="respuesta-correcta-pregunta-<?php echo $i; ?>" value="<?php echo $r -> getNumRespuesta(); ?>" required>
                <?php } ?>
                    <input class="descripcion-respuesta" type="text" name="<?php echo "descripcion-respuesta-".$i."-".$ires?>" value="<?php echo $r->getDescripcion(); ?>" placeholder="Ingrese descripción de la respuesta">
                    <input style="display: none;" type="text" name="<?php echo "id-respuesta-".$i."-".$ires?>" value="<?php echo $r->getNumRespuesta(); ?>">
                </label>
            </div>
        <?php
            $ires++;
        }
    } else {
        foreach ($respuestas as $r) { ?>
            <div class="respuesta">
                <label class="respuesta-content">
                <?php if($r -> getValor() > 0){ ?>
                    <input class="input" type="radio" name="respuesta-correcta-pregunta-<?php echo $i; ?>" value="<?php echo $r -> getNumRespuesta(); ?>" required checked>
            <?php } else { ?>
                <input class="input" type="radio" name="respuesta-correcta-pregunta-<?php echo $i; ?>" value="<?php echo $r -> getNumRespuesta(); ?>" required>
                <?php } ?>
                    <label class="descripcion-respuesta" name="<?php echo "descripcion-respuesta-".$i."-".$ires?>" > <?php echo $r->getDescripcion(); ?></label>
                    <input style="display: none;" type="text" name="<?php echo "id-respuesta-".$i."-".$ires?>" value="<?php echo $r->getNumRespuesta(); ?>">
                </label>
            </div>
        <?php
            $ires++;
        }
    }
    ?>
</div>

    <?php  $i = $i + 1; } ?>

    <button id="boton" type="submit">Guardar Examen</button>

</form>

<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "Funciona"."<br>";
    echo "titulo: ".$_POST['titulo']."<br>";
    examen::updateTitulo($_POST['titulo'], $_GET['examenNum']);

    echo "tema: ".$_POST['tema']."<br><br>";
    temaExamen::updateTema($_POST['tema'], $_GET['examenNum']);

    $i = 1;
    foreach ($preguntas as $pr) {
        echo "Identificador de Pregunta ".$i.": ".$_POST["id-pregunta-$i"]."<br>";
        echo "Descripcion de Pregunta ".$i.": ".$_POST["descripcion-pregunta-$i"]."<br>";
        pregunta::updateDescripcion( $_POST["descripcion-pregunta-$i"], $_POST["id-pregunta-$i"]);

        echo "Valor de Pregunta ".$i.": ".$_POST["valor-pregunta-$i"]."<br>";
        $ires = 1;
        if($_POST["valor-pregunta-$i"] > 100){
            $_POST["valor-pregunta-$i"] = 100;
        }

        $respuestas = respuesta::get_exam($pr -> getNumPregunta());
        pregunta::updateValor($_POST["valor-pregunta-$i"], $_POST["id-pregunta-$i"]);

if($pr -> getCodeTipo() == "selmult"){
    foreach ($respuestas as $r) {
        echo "Descripcion de Respuesta ".$ires." de pregunta ".$i.": ".$_POST["descripcion-respuesta-".$i."-".$ires]."<br>";
        respuesta::updateDescripcion($_POST["id-respuesta-".$i."-".$ires], $_POST["descripcion-respuesta-".$i."-".$ires]);
        echo "Identificador de Respuesta ".$ires." de pregunta ".$i.": ".$_POST["id-respuesta-".$i."-".$ires]."<br><br>";
        respuesta::update_0($_POST["id-respuesta-".$i."-".$ires]);
        $ires++;
    }

    $resp = $_POST["$i"];
    $cantidad_correctas = 0;
    foreach ($resp as $res) {
        $cantidad_correctas ++;
        echo "Respuesta correcta de pregunta ".$i.": ".$res."<br><br>";
    }

    
    $valor_por_respuesta = $_POST["valor-pregunta-$i"] / $cantidad_correctas;

    echo "el valor de cada respuesta ".$valor_por_respuesta."<br><br>";

    foreach ($resp as $res) {
        respuesta::update_valor( $valor_por_respuesta, $res);
        
    }


} else if($pr -> getCodeTipo() == "opcmult") {
        foreach ($respuestas as $r) {
            echo "Descripcion de Respuesta ".$ires." de pregunta ".$i.": ".$_POST["descripcion-respuesta-".$i."-".$ires]."<br>";
            respuesta::updateDescripcion($_POST["id-respuesta-".$i."-".$ires], $_POST["descripcion-respuesta-".$i."-".$ires]);

            echo "Identificador de Respuesta ".$ires." de pregunta ".$i.": ".$_POST["id-respuesta-".$i."-".$ires]."<br><br>";
            respuesta::update_0($_POST["id-respuesta-".$i."-".$ires]);
            $ires++;
        }
               echo "Respuesta correcta de pregunta ".$i.": ".$_POST["respuesta-correcta-pregunta-".$i]."<br><br><br>";
        respuesta::update_valor( $_POST["valor-pregunta-$i"], $_POST["respuesta-correcta-pregunta-".$i]);
    } else {
        foreach ($respuestas as $r) {
            echo "Identificador de Respuesta ".$ires." de pregunta ".$i.": ".$_POST["id-respuesta-".$i."-".$ires]."<br><br>";
            respuesta::update_0($_POST["id-respuesta-".$i."-".$ires]);
            $ires++;
        }
           echo "Respuesta correcta de pregunta ".$i.": ".$_POST["respuesta-correcta-pregunta-".$i]."<br><br><br>";
    respuesta::update_valor( $_POST["valor-pregunta-$i"], $_POST["respuesta-correcta-pregunta-".$i]);
    }
    $i++;
}
}
?>

<script>
        function showAlert() {
            alert("¡Hola! Has pulsado el botón");
        }
    </script>


<script>
    function validarFormulario(event) {
        var checkboxes = document.querySelectorAll('input[type="checkbox"]');
        
        var algunoSeleccionado = false;
        checkboxes.forEach(function(checkbox) {
            if (checkbox.checked) {
                algunoSeleccionado = true;
            }
        });

        if (!algunoSeleccionado) {
            event.preventDefault();
        }
    }

    document.getElementById('miFormulario').addEventListener('submit', validarFormulario);
</script>


<script>
        const textarea = document.getElementById('pregunta');

        textarea.addEventListener('input', function () {
            this.style.height = 'auto';
            this.style.height = `${this.scrollHeight}px`;
        });
    </script>


    </body>