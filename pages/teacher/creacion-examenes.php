<?php
    include 'header.php';
    include '../../includes/authentication.php';
    include ('../../models3/asignacion.php');
    include ('../../models3/respuesta.php');
    include ('../../models3/teacher.php');
    include ('../../models3/curso.php');
    include ('../../models3/pregunta.php');
    include ('../../models3/tipopregunta.php');
    include ('../../models3/examen.php');
    include ('../../models3/temaExamen.php');
    include ('../../models3/tema.php');
    

    checkAuthentication('maestro');

    $cursos = asignacion::get_curso_imparte_docente($_SESSION['identificador_usuario']);

    $topic = "Seleccionar Tema";
    $course = "Seleccionar Curso";
    
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <!--Styles-->
        <link rel="stylesheet" href="css/crear-examen.css">
        <!--Meta-->
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!--Title-->
        <link rel="icon" href="../../images/favicon/favicon.ico" type="image/x-icon">
        <title>Creación de examenes</title>
    </head>
    <body>

<?php if(isset($cursos)){ ?>

    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['curso'])) {
    $cursoSeleccionado = $_POST['curso'];
    $curso = curso::get($cursoSeleccionado);
    $course = $curso -> getNombre();
    }
    ?>



<?php if ($_SERVER['REQUEST_METHOD'] !== 'POST') {?>
    <div class="examen-info-div" >
<form id="formulario" method="POST" action="">
    <select name="curso" id="examen-curso-select-1" class="select" onchange="this.form.submit()">
        <option id="placeholder" value="" disabled selected> <?php echo $course ?> </option>
        <?php foreach ($cursos as $c) { ?>
            <option value="<?php echo $c->getCursonum(); ?>" > <?php echo $c->getGrupo(); ?> </option>
        <?php }
        ?>
    </select>
</form>
    </div>
<?php } ?>

<?php if ($_SERVER['REQUEST_METHOD'] === 'POST' ) {?>
    <div class="examen-info-div" >
    <form id="formulario2" method="POST" action="">
    <select name="curso" id="examen-curso-select" class="select" onchange="this.form.submit()">
        <option id="placeholder" value="" disabled selected> <?php echo $course ?> </option>
        <?php foreach ($cursos as $c) { ?>
            <option value="<?php echo $c->getCursonum(); ?>" > <?php echo $c->getGrupo(); ?> </option>
        <?php }
        ?>
    </select>
</form>


<?php $temas = tema::get_temacurso($cursoSeleccionado); ?>


<form id="formulario3" method="POST">
<input style="display: none;" name="curso" value="<?php echo $cursoSeleccionado; ?>">  </input>

<div class="examen-titulo">
    <input id="titulo" type="text" name="titulo" placeholder="Ingresar Titulo" required></input>
</div>
<div class="examen-tema">
    <select name="tema" id="examen-tema-select" class="select" required>
        <option id="placeholder" value="" disabled selected> <?php echo $topic ?> </option>';
    <?php foreach ($temas as $t) {?>
        <option value="<?php echo $t->getNumTema() ?>">  <?php echo $t->getNombre() ?> </option>
    <?php } ?>
    </select>
    </div>
    </div>

    <button id="boton" type="submit">Ingresar Preguntas</button>
</form> <?php } ?>

<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['titulo']) && isset($_POST['tema'])) {
    $ultimo = examen::get_ultimo_examen();
    $numExam = $ultimo->getNumExam() + 1;
    $insertar_boolean = false;
    $tema = $_POST['tema'];
    echo $_POST['titulo'];
    echo $_POST['tema'];
    echo $numExam;

    try {
        $insertar_boolean = true;
        $insertar_examen = examen::insert($numExam, $_POST['titulo'], '0-00-0000', '0-00-0000', 0 , $_SESSION['identificador_usuario'], $cursoSeleccionado);
        $insertar_tema_examen = temaExamen::insert( $_POST['tema'], $numExam );


    } catch (Throwable $th) {
        $insertar_boolean = false;
        $numExam = $numExam + 1;
    }

    
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    echo "<script>window.location.href = 'examen-ingresar.php?examenNum=$numExam';</script>";
    exit();
    }
    ?>

<?php } ?>


<?php } else { ?>

<div class="examen-info-div" >
<div class="examen-titulo" id="centrar"> No Se Tienen Cursos Asignados</div>
<div class="examen-tema">   </div>
</div>

<button id="boton" type="submit" onclick="window.location.href='dashboard.php'">Regresar</button>

<?php } ?>

</body>

<script>
    const select = document.getElementById('examen-curso-select');
    const resultado = document.getElementById('resultado');

    select.addEventListener('change', () => {
        const valorSeleccionado = select.value;
        if (valorSeleccionado) {

        } else {

            resultado.textContent = "0";
        }
    });
</script>

<html>