<?php
    include 'header.php';
    include '../../includes/authentication.php';
    include ('../../models3/curso.php');
    include ('../../models3/examen.php');
    include ('../../models3/alumnoExamen.php');


    $examenes = examen::get_examenes_docente($_SESSION['identificador_usuario']);

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
    checkAuthentication('maestro');
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
        <title> <?php echo "hola" ?> </title>
    </head>
    <body>

    
        <div id="materia2" class="exam-bgc">
            <div class="materia-name"> Editar Examenes</div>
        </div>

        <div id="materia-todo">

        <?php

        $iterador = 0;

        foreach($examenes as $ex ){

            $iterador = $iterador + 1;

        $estadoMostrar = "Sin Realizar";


        if ($ex -> getNumEstado() == 1){ ?>
        <div class="exam" onclick="window.location.href='examen-ingresar.php?examenNum=<?php echo $ex->getNumExam()?>;'"> <div class="exam-icon" > <i class="fas fa-clipboard-list"></i></div>
        <div class="exam-titulo"> <?php echo $ex -> getTitulo();?> </div>
        <div class="exam-estado"></div>
    <div class="<?php ?>" ></div>
        </div>
    
    <?php } }?>

        <?php
        foreach($examenes as $ex ){

        $estadoMostrar = "Sin Realizar";

        $calificacion = alumnoExamen::get($_SESSION['identificador_usuario'], $ex -> getNumExam());

        if ($ex -> getNumEstado() == 0){ ?>
        <div class="exam" onclick="window.location.href='examen-ingresar.php?examenNum=<?php echo $ex->getNumExam()?>;'"> <div class="exam-icon" > <i class="fas fa-clipboard-list"></i></div>
        <div class="exam-titulo"> <?php echo $ex -> getTitulo();?> </div>
        <div class="exam-estado">
        </div>
    
        <div class="<?php ?>">

        </div>
        
        </div>
        <?php } } if ($iterador == 0){ ?>
            <div class="exam"> <label id="no-hay"> No Hay Examenes Disponibles Actualmente</label> </div>

            <button id="boton" type="submit" onclick="window.location.href='view_courses.php'">Regresar</button>
        <?php } ?>
        </div>
        </div>

        </div>
    </body>