<?php
    session_start();
    include 'header.php';
    include '../../includes/authentication.php';
    include ('../../models3/curso.php');
    include ('../../models3/examen.php');
    include ('../../models3/alumnoExamen.php');


    $examenes = examen::get_examenes_todos_sin_realizar($_SESSION['identificador_usuario']);

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
        <title> Examenes </title>
    </head>
    <body>
    
    <div id="examen" style=" background-color: <?php echo '#570064' ?>;"> Historial de Examenes </div>
        <div id="materia-todo">

        <?php
        foreach($examenes as $ex ){

        $estadoMostrar = "Sin Realizar";

        $calificacion = alumnoExamen::get($_SESSION['identificador_usuario'], $ex -> getNumExam());

        if ($ex -> getNumEstado() == 1){ ?>
        <div class="exam"> <div class="exam-icon" style=" background-color: <?php echo '#570064' ?>;"> <i class="fas fa-clipboard-list"></i></div>
        <div class="exam-titulo"> <?php echo $ex -> getTitulo();?> </div>
        <div class="exam-estado">
<?php
    if(isset($calificacion)){
        $estadoMostrar = "Realizado";
    } else {
        $estadoMostrar = $ex -> getFinalExamen();
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
        </div>
        </div>

        </div>
    </body>