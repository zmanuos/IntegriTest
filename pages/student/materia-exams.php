<?php
    session_start();
    include 'header.php';
    include '../../includes/authentication.php';
    include ('../../models3/curso.php');
    include ('../../models3/examen.php');
    include ('../../models3/alumnoExamen.php');

    $curso = curso::get($_GET['cursonum'],);

    $examenes = examen::get_examenes_sin_realizar($_SESSION['identificador_usuario'], $_GET['cursonum']);

    /*
    foreach($examenes as $ex ){
        echo $ex -> getNumExam();
        echo $ex -> getTitulo();
        echo $ex -> getInicioExamen();
        echo $ex -> getFinalExamen();
        echo $ex -> getNumExam();
        }

    
    echo $_GET['color'];

    if (isset($_GET['color'])) {
        $color = $_GET['color'];
        echo "El valor de color es: $color";
    } else {
        echo "No se proporcionó un valor para 'color'.";
    }

    if($curso){
        echo $curso -> getCursonum();
        echo $curso -> getNombre();
        echo $curso -> getDescripcion();
        echo $curso -> getNumEstado();

        
            
            echo $ex -> getInicioExamen();
            echo $ex -> getFinalExamen();
            echo $ex -> getNumExam();
    }
*/
    checkAuthentication('alumno');
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <!--Styles-->
        <link rel="stylesheet" href="../css/materia-exams.css">
        <link rel="icon" href="../../images/favicon/favicon.ico" type="image/x-icon">
        <!--Meta-->
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!--Title-->
        <title> <?php echo $curso->getNombre() ?> </title>
    </head>
    <body>

    
        <div id="materia" style=" background-color: <?php echo '#'.$_GET['color']; ?> ;">
            <div class="materia-name"><?php echo $curso->getNombre() ?></div>
            <div class="descripcion"><?php echo $curso->getDescripcion() ?></div>
            <div class="docente-name"><?php echo $_GET['profe'] ?></div>
        </div>
        <div id="materia-todo">

        <?php

        $iterador = 0;

        foreach($examenes as $ex ){

            $iterador = $iterador + 1;

        $estadoMostrar = "Sin Realizar";

        $calificacion = alumnoExamen::get($_SESSION['identificador_usuario'], $ex -> getNumExam());

        if ($ex -> getNumEstado() == 1){ ?>
        <div class="exam" onclick="window.location.href='examen-especifico.php?examenNum=<?php echo $ex->getNumExam();?>&color=<?php echo $_GET['color']; ?>&curso=<?php echo $curso->getCursonum(); ?>&profe=<?php echo $_GET['profe'] ?>';"> <div class="exam-icon" style=" background-color: <?php echo '#'.$_GET['color']; ?> ;"> <i class="fas fa-clipboard-list"></i></div>
        <div class="exam-titulo"> <?php echo $ex -> getTitulo();?> </div>
        <div class="exam-estado">
<?php
    if(isset($calificacion)){
        $estadoMostrar = "Realizado";
    } else {
        $estadoMostrar = $ex -> getFinalExamen();
    }
    echo $estadoMostrar; ?>

        </div>
    <div class="<?php
    if(isset($calificacion)){
        if ($calificacion -> getCalificacion() >= 8 && $calificacion -> getCalificacion() <= 10 ){
            echo "exam-aprobado";
        } else if($calificacion <= 8) {
            echo "exam-reprobado";
        }
    } else {
        echo "exam-no-realizado";
    }
?>" >

<?php
    if(isset($calificacion)){
        echo $calificacion -> getCalificacion();
    } else {
        echo "0.0";
    }
?>
    </div>
</div>
    
    <?php } }?>

        <?php
        foreach($examenes as $ex ){

        $estadoMostrar = "Sin Realizar";

        $calificacion = alumnoExamen::get($_SESSION['identificador_usuario'], $ex -> getNumExam());

        if ($ex -> getNumEstado() == 0){ ?>
        <div class="exam-tarde"> <div class="exam-icon" > <i class="fas fa-clipboard-list"></i></div>
        <div class="exam-titulo"> <?php echo $ex -> getTitulo();?> </div>
        <div class="exam-estado">
<?php
    if(isset($calificacion)){
        $estadoMostrar = "Realizado";
    } else {
        $estadoMostrar = "No realizado";
    }
    echo $estadoMostrar;
?>
        </div>
    
        <div class="<?php
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
        <?php } } if ($iterador == 0){ ?>
            <div class="exam"> <label id="no-hay"> No Hay Examenes Disponibles Actualmente</label> </div>

            <button id="boton" type="submit" onclick="window.location.href='materias.php'">Regresar</button>
        <?php } ?>
        </div>
        </div>

        </div>
    </body>