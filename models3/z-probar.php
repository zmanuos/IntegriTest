

<!DOCTYPE html>
	<head>
		<!--title-->
        <title>Sample01</title>
        <!--javascript-->
        <script src="data/config.js"></script>
        <script src="js/fontawesome/main.js"></script>
        <!--stylesheets-->
        <link href="css/style.css" rel="stylesheet" />
        <link href="css/menu.css" rel="stylesheet" />
        <link href="css/themes.css" rel="stylesheet" />
        <link href="css/fontawesome/fontawesome.css" rel="stylesheet" />
        <link href="css/fontawesome/solid.css" rel="stylesheet" />
		<!--metadata-->
        <meta name="viewport" content="width=device-width, initial-scale=1"/>
        <meta charset="utf-8"/>
	</head>
    <body onload="">
            <header>
            <div id="header-left">
            <div id="header-menu"> <i class="fas fa-bars"></i> </div>
            <div id="header-logo">
            </div>
            </div>

            <div id="header-right">
                <div id="user-photo"></div>

                    <div id="user-settings"> <i class="fas fa-cog"></i> </div>
                    <div id="user-region"> <i class="fas fa-globe"> </i></div>
                    <div id="user-sign-out"><i class="fas fa-sign-out-alt"></a> </i></div>
                </div>
            </div>
        </header>

<?php
    require_once ('student.php');
    require_once ('admin.php');
    require_once ('teacher.php');
    require_once ('alumnoExamen.php');
    require_once ('asignacion.php');
    require_once ('curso.php');
    require_once ('examen.php');
    require_once ('grupo.php');
    require_once ('pregunta.php');
    require_once ('registro.php');
    require_once ('respuesta.php');
    require_once ('temaExamen.php');
    require_once ('tema.php');
    require_once ('materia.php');

    $materia_get_all = materia::get("2203290000");

    $admin_get_all = admin::get();
    $admin_get_one = admin::get("410002");

    $docente_get_all = teacher::get();
    $docente_get_sin_grupo = teacher::get_docentes_sin_grupo();
    $docente_get_one = teacher::get("1");

    $alumnos_get_all = student::get();
    $alumnos_get_one = student::get("2203290000");
    $alumnos_sin_grupo = student::get_alumnos_sin_grupo();

    $alumno_exam_get_all = alumnoExamen::get();
    $alumno_exam_get_one = alumnoExamen::get("2203290000",1);

    $asignacion_get_all = asignacion::get();
    $asignacion_get_one = asignacion::get(1,1);
    $asignacion_get_curso_docente = asignacion::get_curso_docente(1);

    $curso_get_all = curso::get();
    $curso_get_one = curso::get(1);
    $curso_get_cursos_que_no_estan_en_un_grupo = curso::get_cursos_que_no_estan_en_un_grupo(1);

    $examen_get_all = examen::get();
    $examen_get_one = examen::get(1);

    $grupo_get_all = grupo::get();
    $grupo_get_one = grupo::get(1);

    $pregunta_get_all = pregunta::get();
    $pregunta_get_one = pregunta::get(1);
    $pregunta_exam_get_all = pregunta::get_exam(4);

    $registro_get_all = registro::get();
    $registro_get_one = registro::get("2203290000",1);

    //$insert_registro = registro::insert_registro("1124070085",1);

    $respuesta_get_all = respuesta::get();
    $respuesta_get_one = respuesta::get(2);
    $respuestasPregunta_get = respuesta::get_exam(1);

    $temaExamen_get_all = temaExamen::get();
    $temaExamen_get_one = temaExamen::get(1,7);

    $tema_get_all = tema::get();
    $tema_get_one = tema::get(1);
    $tema_get_temacurso = tema::get_temacurso(1);
?>
<br>

Obtener todas las materias de un alumno
<table style="background-color: #c3c3c3;">
<?php foreach($materia_get_all as $as){; ?>
    <tr>
        <td><?php echo $as->getCursonum(); ?></td>
        <td><?php echo $as->getcursoNombre(); ?></td>
        
        <td><?php echo $as->getGrupo(); ?></td>
        <td><?php echo $as->getgrupoNombre(); ?></td>
        
        <td><?php echo $as->getPromedio(); ?></td>
        
        <td><?php echo $as->getNumEmpleado(); ?></td>
        <td><?php echo $as->getprofesorNombre(); ?></td>
    <?php }?>
</table>
<br><br>

Obtener un docente
<table style="background-color: #c3c3c3;">
<?php if($admin_get_one){  $a = $admin_get_one;?>
    <tr>
            <td><?php echo $a->getid_admin(); ?></td>
            <td><?php echo $a->getNombre(); ?></td>
            <td><?php echo $a->getApellidoP(); ?></td>
            <td><?php echo $a->getApellidoM(); ?></td>
            <td><?php echo $a->getCorreo(); ?></td>
            <td><?php echo $a->getTelefono(); ?></td>
            <td><?php echo $a->getFoto(); ?></td>
            <td><?php echo $a->getGenero(); ?></td>
            <td><?php echo $a->getfechacreacion(); ?></td>
            <td><?php echo $a->getcontador(); ?></td>
            <td><?php echo $a->getFechaNacimiento(); ?></td>
    <?php }?>
</table>




Obtener todos los cursos que no estan en un grupo
<table style="background-color: #c3c3c3;">
<?php foreach($curso_get_cursos_que_no_estan_en_un_grupo as $c){; ?>
    <tr>
            <td><?php echo $c->getCursonum(); ?></td>
            <td><?php echo $c->getNombre(); ?></td>
            <td><?php echo $c->getDuracion(); ?></td>
            <td><?php echo $c->getDescripcion(); ?></td>
            <td><?php echo $c->getNumEstado(); ?></td>
    <?php }?>
</table>
<br><br>

Obtener las asignaciones de un profesor
<table style="background-color: #c3c3c3;">
<?php foreach($asignacion_get_curso_docente as $as){; ?>
    <tr>
            <td><?php echo $as->getCursonum(); ?></td>
            <td><?php echo $as->getGrupo(); ?></td>
            <td><?php echo $as->getPromedio(); ?></td>
            <td><?php echo $as->getNumEmpleado(); ?></td>
    <?php }?>
</table>
<br><br>

Obtener todos los docentes sin grupo
<table style="background-color: #c3c3c3;">
<?php foreach($docente_get_sin_grupo as $d){ ?>
    <tr>
            <td><?php echo $d->getnumEmpleado(); ?></td>
            <td><?php echo $d->getNombre(); ?></td>
            <td><?php echo $d->getApellidoP(); ?></td>
            <td><?php echo $d->getApellidoM(); ?></td>
            <td><?php echo $d->getCorreo(); ?></td>
            <td><?php echo $d->getTelefono(); ?></td>
            <td><?php echo $d->getFoto(); ?></td>
            <td><?php echo $d->getGenero(); ?></td>
            <td><?php echo $d->getFechaNacimiento(); ?></td>
            <td><?php echo $d->getCurp(); ?></td> </tr>
    <?php }?>
</table>
<br><br>

Obtener todos los alumnos sin grupo
<table style="background-color: #c3c3c3;">
<?php foreach($alumnos_sin_grupo as $a){ ?>
    <tr>
            <td><?php echo $a->getMatricula(); ?></td>
            <td><?php echo $a->getNombre(); ?></td>
            <td><?php echo $a->getApellidoP(); ?></td>
            <td><?php echo $a->getApellidoM(); ?></td>
            <td><?php echo $a->getPromedio(); ?></td>
            <td><?php echo $a->getCantidadExams(); ?></td>
            <td><?php echo $a->getCorreo(); ?></td>
            <td><?php echo $a->getTelefono(); ?></td>
            <td><?php echo $a->getFoto(); ?></td>
            <td><?php echo $a->getGenero(); ?></td>
            <td><?php echo $a->getFechaNacimiento(); ?></td>
            <td><?php echo $a->getCurp(); ?></td> </tr>
    <?php }?>
</table>
<br><br>

Obtener todos los temas de un curso
<table style="background-color: #c3c3c3;">
<?php foreach($tema_get_temacurso as $t){; ?>
    <tr>
            <td><?php echo $t->getNumTema(); ?></td>
            <td><?php echo $t->getNombre(); ?></td>
            <td><?php echo $t->getDescripcion(); ?></td>
            <td><?php echo $t->getCursonum(); ?></td>
    <?php }?>
</table>
<br>

Obtener un temas
<table style="background-color: #c3c3c3;">
<?php if($tema_get_one){ $t = $tema_get_one; ?>
    <tr>
            <td><?php echo $t->getNumTema(); ?></td>
            <td><?php echo $t->getNombre(); ?></td>
            <td><?php echo $t->getDescripcion(); ?></td>
            <td><?php echo $t->getCursonum(); ?></td>
    <?php }?>
</table>
<br>

Obtener todos los temas
<table style="background-color: #c3c3c3;">
<?php foreach($tema_get_all as $t){; ?>
    <tr>
            <td><?php echo $t->getNumTema(); ?></td>
            <td><?php echo $t->getNombre(); ?></td>
            <td><?php echo $t->getDescripcion(); ?></td>
            <td><?php echo $t->getCursonum(); ?></td>
    <?php }?>
</table>
<br>


Obtener un tema de un examen
<table style="background-color: #c3c3c3;">
<?php if($temaExamen_get_one){ $tm = $temaExamen_get_one; ?>
    <tr>
            <td><?php echo $tm->getNumTema(); ?></td>
            <td><?php echo $tm->getNumExam(); ?></td>
    <?php }?>
</table>
<br>

Obtener todos los temas de los examenes
<table style="background-color: #c3c3c3;">
<?php foreach($temaExamen_get_all as $tm){; ?>
    <tr>
            <td><?php echo $tm->getNumTema(); ?></td>
            <td><?php echo $tm->getNumExam(); ?></td>
    <?php }?>
</table>
<br><br>

Obtener todas las respuestas de una pregunta
<table style="background-color: #c3c3c3;">
<?php foreach($respuestasPregunta_get as $rp){; ?>
    <tr>
            <td><?php echo $rp->getNumRespuesta(); ?></td>
            <td><?php echo $rp->getRespuestaNum(); ?></td>
            <td><?php echo $rp->getValor(); ?></td>
            <td><?php echo $rp->getDescripcion(); ?></td>
            <td><?php echo $rp->getPregunta(); ?></td>
    <?php }?>
</table>
<br>

Obtener una respuesta
<table style="background-color: #c3c3c3;">
<?php if($respuesta_get_one){  $re = $respuesta_get_one; ?>
    <tr>
            <td><?php echo $re->getNumRespuesta(); ?></td>
            <td><?php echo $re->getRespuestaNum(); ?></td>
            <td><?php echo $re->getValor(); ?></td>
            <td><?php echo $re->getDescripcion(); ?></td>
            <td><?php echo $re->getPregunta(); ?></td>
    <?php }?>
</table>
<br>


Obtener todas las respuestas
<table style="background-color: #c3c3c3;">
<?php foreach($respuesta_get_all as $re){; ?>
    <tr>
            <td><?php echo $re->getNumRespuesta(); ?></td>
            <td><?php echo $re->getRespuestaNum(); ?></td>
            <td><?php echo $re->getValor(); ?></td>
            <td><?php echo $re->getDescripcion(); ?></td>
            <td><?php echo $re->getPregunta(); ?></td>
    <?php }?>
</table>
<br>


Obtener un registro
<table style="background-color: #c3c3c3;">
<?php if($registro_get_one) {  $r = $registro_get_one; ?>
    <tr>
            <td><?php echo $r->getMatricula(); ?></td>
            <td><?php echo $r->getGrupo(); ?></td>
    <?php }?>
</table>
<br>
Obtener todos los registros (grupos a los que pertenecen los alumnos)
<table style="background-color: #c3c3c3;">
<?php foreach($registro_get_all as $r){; ?>
    <tr>
            <td><?php echo $r->getMatricula(); ?></td>
            <td><?php echo $r->getGrupo(); ?></td>
    <?php }?>
</table>
<br>

Obtener todas las preguntas de un examen (no todos los examenes tienen preguntas)
<table style="background-color: #c3c3c3;">
<?php foreach($pregunta_exam_get_all as $pe){; ?>
    <tr>
            <td><?php echo $pe->getNumPregunta(); ?></td>
            <td><?php echo $pe->getDescripcion(); ?></td>
            <td><?php echo $pe->getValor(); ?></td>
            <td><?php echo $pe->getPreguntaNumero(); ?></td>
            <td><?php echo $pe->getNumExam(); ?></td>
            <td><?php echo $pe->getCodeTipo(); ?></td>
    <?php }?>
</table>
<br>

Obtener una pregunta
<table style="background-color: #c3c3c3;">
<?php if($pregunta_get_all){; $p = $pregunta_get_one ?>
    <tr>
            <td><?php echo $p->getNumPregunta(); ?></td>
            <td><?php echo $p->getDescripcion(); ?></td>
            <td><?php echo $p->getValor(); ?></td>
            <td><?php echo $p->getPreguntaNumero(); ?></td>
            <td><?php echo $p->getNumExam(); ?></td>
            <td><?php echo $p->getCodeTipo(); ?></td>
    <?php }?>
</table>
<br>

Obtener todas las preguntas
<table style="background-color: #c3c3c3;">
<?php foreach($pregunta_get_all as $p){; ?>
    <tr>
            <td><?php echo $p->getNumPregunta(); ?></td>
            <td><?php echo $p->getDescripcion(); ?></td>
            <td><?php echo $p->getValor(); ?></td>
            <td><?php echo $p->getPreguntaNumero(); ?></td>
            <td><?php echo $p->getNumExam(); ?></td>
            <td><?php echo $p->getCodeTipo(); ?></td>
    <?php }?>
</table>
<br><br>

Obtener un grupo
<table style="background-color: #c3c3c3;">
<?php if($grupo_get_one){; $g = $grupo_get_one;  ?>
    <tr>
            <td><?php echo $g->getGrupo(); ?></td>
            <td><?php echo $g->getNombre(); ?></td>
            <td><?php echo $g->getFechaInicio(); ?></td>
            <td><?php echo $g->getFechaFinal(); ?></td>
            <td><?php echo $g->getPromedio(); ?></td>
            <td><?php echo $g->getCantidadAlumnos(); ?></td>
            <td><?php echo $g->getEstado(); ?></td>
    <?php }?>
</table>
<br>

Obtener todos los grupos
<table style="background-color: #c3c3c3;">
<?php foreach($grupo_get_all as $g){; ?>
    <tr>
            <td><?php echo $g->getGrupo(); ?></td>
            <td><?php echo $g->getNombre(); ?></td>
            <td><?php echo $g->getFechaInicio(); ?></td>
            <td><?php echo $g->getFechaFinal(); ?></td>
            <td><?php echo $g->getPromedio(); ?></td>
            <td><?php echo $g->getCantidadAlumnos(); ?></td>
            <td><?php echo $g->getEstado(); ?></td>
    <?php }?>
</table>
<br><br>

Obtener un examen
<table style="background-color: #c3c3c3;">
<?php if($examen_get_one){ $e = $examen_get_one ; ?>
    <tr>
            <td><?php echo $e->getNumExam(); ?></td>
            <td><?php echo $e->getTitulo(); ?></td>
            <td><?php echo $e->getInicioExamen(); ?></td>
            <td><?php echo $e->getFinalExamen(); ?></td>
            <td><?php echo $e->getNumEstado(); ?></td>
            <td><?php echo $e->getNumEmpleado(); ?></td>
    <?php }?>
</table>
<br>

Obtener todos los examenes
<table style="background-color: #c3c3c3;">
<?php foreach($examen_get_all as $e){; ?>
    <tr>
            <td><?php echo $e->getNumExam(); ?></td>
            <td><?php echo $e->getTitulo(); ?></td>
            <td><?php echo $e->getInicioExamen(); ?></td>
            <td><?php echo $e->getFinalExamen(); ?></td>
            <td><?php echo $e->getNumEstado(); ?></td>
            <td><?php echo $e->getNumEmpleado(); ?></td>
    <?php }?>
</table>
<br><br>

Obtener un curso
<table style="background-color: #c3c3c3;">
<?php if($curso_get_one){ $c = $curso_get_one; ?>
    <tr>
            <td><?php echo $c->getCursonum(); ?></td>
            <td><?php echo $c->getNombre(); ?></td>
            <td><?php echo $c->getDuracion(); ?></td>
            <td><?php echo $c->getDescripcion(); ?></td>
            <td><?php echo $c->getNumEstado(); ?></td>
    <?php }?>
</table>

<br>

Obtener todos los cursos
<table style="background-color: #c3c3c3;">
<?php foreach($curso_get_all as $c){; ?>
    <tr>
            <td><?php echo $c->getCursonum(); ?></td>
            <td><?php echo $c->getNombre(); ?></td>
            <td><?php echo $c->getDuracion(); ?></td>
            <td><?php echo $c->getDescripcion(); ?></td>
            <td><?php echo $c->getNumEstado(); ?></td>
    <?php }?>
</table>
<br><br>

Obtener una asignacion
<table style="background-color: #c3c3c3;">
<?php if($asignacion_get_one){ $as = $asignacion_get_one; ?>
    <tr>
            <td><?php echo $as->getCursonum(); ?></td>
            <td><?php echo $as->getGrupo(); ?></td>
            <td><?php echo $as->getPromedio(); ?></td>
            <td><?php echo $as->getNumEmpleado(); ?></td>
    <?php }?>
</table>

Obtener todas las asignaciones
<table style="background-color: #c3c3c3;">
<?php foreach($asignacion_get_all as $as){; ?>
    <tr>
            <td><?php echo $as->getCursonum(); ?></td>
            <td><?php echo $as->getGrupo(); ?></td>
            <td><?php echo $as->getPromedio(); ?></td>
            <td><?php echo $as->getNumEmpleado(); ?></td>
    <?php }?>
</table>

<br>
Obtener un resultado de examen
<table style="background-color: #c3c3c3;">
<?php if($alumno_exam_get_one){ $ae = $alumno_exam_get_one; ?>
    <tr>
            <td><?php echo $ae->getNumExam(); ?></td>
            <td><?php echo $ae->getMatricula(); ?></td>
            <td><?php echo $ae->getCalificacion(); ?></td>
            <td><?php echo $ae->getFechaRealizacion(); ?></td>
    <?php }?>
</table>


<br>
Obtener todos los resultados de examenes
<table style="background-color: #c3c3c3;">
<?php foreach($alumno_exam_get_all as $ae){ ?>
    <tr>
            <td><?php echo $ae->getNumExam(); ?></td>
            <td><?php echo $ae->getMatricula(); ?></td>
            <td><?php echo $ae->getCalificacion(); ?></td>
            <td><?php echo $ae->getFechaRealizacion(); ?></td>
    <?php }?>
</table>

<br><br>

Obtener un docente
<table style="background-color: #c3c3c3;">
<?php if($docente_get_one){  $d = $docente_get_one;?>
    <tr>
            <td><?php echo $d->getnumEmpleado(); ?></td>
            <td><?php echo $d->getNombre(); ?></td>
            <td><?php echo $d->getApellidoP(); ?></td>
            <td><?php echo $d->getApellidoM(); ?></td>
            <td><?php echo $d->getCorreo(); ?></td>
            <td><?php echo $d->getTelefono(); ?></td>
            <td><?php echo $d->getFoto(); ?></td>
            <td><?php echo $d->getGenero(); ?></td>
            <td><?php echo $d->getFechaNacimiento(); ?></td>
            <td><?php echo $d->getCurp(); ?></td> </tr>
    <?php }?>
</table>


<br>
Obtener todos los docentes
<table style="background-color: #c3c3c3;">
<?php foreach($docente_get_all as $d){ ?>
    <tr>
            <td><?php echo $d->getnumEmpleado(); ?></td>
            <td><?php echo $d->getNombre(); ?></td>
            <td><?php echo $d->getApellidoP(); ?></td>
            <td><?php echo $d->getApellidoM(); ?></td>
            <td><?php echo $d->getCorreo(); ?></td>
            <td><?php echo $d->getTelefono(); ?></td>
            <td><?php echo $d->getFoto(); ?></td>
            <td><?php echo $d->getGenero(); ?></td>
            <td><?php echo $d->getFechaNacimiento(); ?></td>
            <td><?php echo $d->getCurp(); ?></td> </tr>
    <?php }?>
</table>

<br><br>

Obtener un alumno
<table style="background-color: #c3c3c3;">
<?php if($alumnos_get_one){ $a = $alumnos_get_one;?>
    <tr>
            <td><?php echo $a->getMatricula(); ?></td>
            <td><?php echo $a->getNombre(); ?></td>
            <td><?php echo $a->getApellidoP(); ?></td>
            <td><?php echo $a->getApellidoM(); ?></td>
            <td><?php echo $a->getPromedio(); ?></td>
            <td><?php echo $a->getCantidadExams(); ?></td>
            <td><?php echo $a->getCorreo(); ?></td>
            <td><?php echo $a->getTelefono(); ?></td>
            <td><?php echo $a->getFoto(); ?></td>
            <td><?php echo $a->getGenero(); ?></td>
            <td><?php echo $a->getFechaNacimiento(); ?></td>
            <td><?php echo $a->getCurp(); ?></td> 
    </tr>
    <?php }?>
</table>


<br><br>
Obtener todos los alumnos
<table style="background-color: #c3c3c3;">
<?php foreach($alumnos_get_all as $a){ ?>
    <tr>
            <td><?php echo $a->getMatricula(); ?></td>
            <td><?php echo $a->getNombre(); ?></td>
            <td><?php echo $a->getApellidoP(); ?></td>
            <td><?php echo $a->getApellidoM(); ?></td>
            <td><?php echo $a->getPromedio(); ?></td>
            <td><?php echo $a->getCantidadExams(); ?></td>
            <td><?php echo $a->getCorreo(); ?></td>
            <td><?php echo $a->getTelefono(); ?></td>
            <td><?php echo $a->getFoto(); ?></td>
            <td><?php echo $a->getGenero(); ?></td>
            <td><?php echo $a->getFechaNacimiento(); ?></td>
            <td><?php echo $a->getCurp(); ?></td> </tr>
    <?php }?>
</table>



</body>
</html>


