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
    include ('../../models3/tema.php');
    include ('../../models3/temaExamen.php');

    checkAuthentication('maestro');

    $examen = examen:: get($_GET['examenNum']);

    $temaexamen = temaExamen::get($_GET['examenNum']);

    $preguntas = pregunta::get_exam($_GET['examenNum']);

    $examen_curso = examen::get_curso_examen($_GET['examenNum']);

    $temas = tema::get_temacurso($examen_curso -> getInicioExamen());

    $tipo = tipopregunta::get();

    $calificacion_examen = 0;

    $examen_enviar = $_GET['examenNum'];


?>

<!DOCTYPE html>

<html lang="en">
    <head>
        <!--Styles-->
        <link rel="stylesheet" href="css/examen.css">
        <!--Meta-->
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!--Title-->
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

    <div class="examen-fechas">
    <label class="fecha-label">Fecha de inicio: </label>
    <input id="fechaInicio"  type="date" class="fecha" name="fechaInicio" required value="<?php echo $examen -> getInicioExamen() ?>">
    <label class="fecha-label">Fecha de Limite: </label>
    <input id="fechaLimite"  type="date" class="fecha" name="fechaLimite" required value="<?php echo $examen -> getFinalExamen() ?>">
    </div>

</div>


    
<?php
    $i = 1;
    $valorExamen = 0;

    foreach ($preguntas as $pr) {

    $valorPregunta = $pr -> getValor();
    $valorExamen = $valorExamen + $valorPregunta;
    
    ?>
    

    <div class="pregunta-div" >
        <div class="numPregunta"><label class="pregunta-num" ><?php echo $i; ?></label></div>
    
        

    <div class="descripcion-ingresada">
        <textarea name="descripcion-pregunta-<?php echo $i;?>" id="pregunta" placeholder="Ingresa la pregunta..."><?php echo $pr -> getDescripcion();?></textarea>
        <input style="display: none;" type="text" name="id-pregunta-<?php echo $i;?>" value="<?php echo $pr->getNumPregunta(); ?>">
    </div>

        <div class="valor" ><input class="input-valor" type="text" name="valor-pregunta-<?php echo $i;?>" value="<?php echo $pr -> getValor();?>" min="0" max="100" > <label class="pregunta-valor" >  Puntos</label></div>
    </div>







    <?php $respuestas = respuesta::get_exam($pr -> getNumPregunta()); ?>
    <div class="respuestas-div">
    <?php
    $ires = 1;
    if ($pr->getCodeTipo() == "selmult") {


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
                <div class="respuesta-content">
                <?php if($r -> getValor() > 0){ ?>
                    <input class="input" type="radio" name="respuesta-correcta-pregunta-<?php echo $i; ?>" value="<?php echo $r -> getNumRespuesta(); ?>" required checked>
            <?php } else { ?>
                <input class="input" type="radio" name="respuesta-correcta-pregunta-<?php echo $i; ?>" value="<?php echo $r -> getNumRespuesta(); ?>" required>
                <?php } ?>
                    <label class="descripcion-respuesta" name="<?php echo "descripcion-respuesta-".$i."-".$ires?>" > <?php echo $r->getDescripcion(); ?></label>
                    <input style="display: none;" type="text" name="<?php echo "id-respuesta-".$i."-".$ires?>" value="<?php echo $r->getNumRespuesta(); ?>">
            </div>
            </div>
        <?php
            $ires++;
        }
    }
    ?>
</div>
    <?php  $i = $i + 1; } ?>



    <div id="contenedor-preguntas"></div>
</div>


<div id="botones">
    <button id="add-question-btn" type="button" class="boton">Añadir Pregunta</button>
    <button id="boton" type="submit">Guardar Examen</button>
</div>


</form>


<script>

        function showAlert() {
            alert("¡Hola! Has pulsado el botón");
        }
    </script>



    <script>
        let questionIndex = <?php echo $i ?>; // Controla el número de preguntas dinámicas
        let questionIndexverdadero = 1;
        <?php $questionIndexverdadero = 1; ?>
        console.log( questionIndexverdadero);

        document.getElementById('add-question-btn').addEventListener('click', function () {


            const contenedorPrincipal = document.getElementById('contenedor-preguntas');


            const lastQuestionContainer = contenedorPrincipal.lastElementChild;
            if (lastQuestionContainer) {
                const respuestasContainer = lastQuestionContainer.querySelector('.respuestas-container');
                const totalRespuestas = respuestasContainer ? respuestasContainer.querySelectorAll('.respuesta-content').length : 0;
                if (totalRespuestas < 2) {
                    alert('Debes agregar al menos 2 respuestas antes de crear una nueva pregunta.');
                    return;
                } else {
                    questionIndex++;
                    questionIndexverdadero ++;
                    console.log( questionIndexverdadero);

                    <?php $questionIndexverdadero = $questionIndexverdadero + 1;?>
                    <?php echo "console.log('Cantidad de preguntas almacenadas en php: $questionIndexverdadero');"; ?>;
                }
            }


            const preguntaCompletaDiv = document.createElement('div');
            preguntaCompletaDiv.className = 'pregunta-completa';
            preguntaCompletaDiv.setAttribute('data-question-index', questionIndex);


            const preguntasContainer = document.createElement('div');
            preguntasContainer.id = `preguntas-container-${questionIndex}`;
            preguntasContainer.className = 'preguntas-container';


            const respuestasContainer = document.createElement('div');
            respuestasContainer.id = `respuestas-container-${questionIndex}`;
            respuestasContainer.className = 'respuestas-container';


            const preguntaDiv = document.createElement('div');
            preguntaDiv.className = 'pregunta-div';


            const numPreguntaDiv = document.createElement('div');
            numPreguntaDiv.className = 'numPregunta';
            numPreguntaDiv.innerHTML = `<label class="pregunta-num">${questionIndex})</label>`;
            preguntaDiv.appendChild(numPreguntaDiv);


            const descripcionDiv = document.createElement('div');
            descripcionDiv.className = 'descripcion-ingresando';
            descripcionDiv.innerHTML = `
                <textarea name="ingresando-descripcion-pregunta-${questionIndexverdadero}" placeholder="Ingresa la pregunta..."></textarea>
            `;
            preguntaDiv.appendChild(descripcionDiv);


            const tipoDiv = document.createElement('div');
            tipoDiv.className = 'tipo';
            tipoDiv.innerHTML = `
                <select name="ingresando-tipo-pregunta-${questionIndexverdadero}" class="select-tipo" required>
                    <option value="" disabled selected>Selecciona el tipo de pregunta</option>
                    <option value="opcmult">Opción múltiple</option>
                    <option value="verofal">Verdadero/Falso</option>
                    <option value="selmult">Selección múltiple</option>
                </select>
            `;
            preguntaDiv.appendChild(tipoDiv);

            const valorDiv = document.createElement('div');
            valorDiv.className = 'valor';
            valorDiv.innerHTML = `
                <input class="input-valor" type="number" name="ingresando-valor-pregunta-${questionIndexverdadero}" value="0" min="0" max="100" required>
                <label class="pregunta-valor">Puntos</label>
                <input style="display:none;" type="checkbox" name="cantidad-preguntas[]" value="hola" checked>
            `;
            preguntaDiv.appendChild(valorDiv);

            const addAnswerBtn = document.createElement('button');
            addAnswerBtn.type = 'button';
            addAnswerBtn.innerText = 'Agregar Respuestas';
            addAnswerBtn.className = 'agregar-respuestas';
            addAnswerBtn.addEventListener('click', function () {
                const tipoPregunta = preguntaDiv.querySelector('select').value;
                const textoPregunta = preguntaDiv.querySelector('textarea').value;
                const valor = preguntaDiv.querySelector('input').value;

                if (!textoPregunta) {
                    alert("Por favor ingrese la descripcion de la pregunta.");
                    return;
                }

                if (!valor || valor == 0) {
                    alert("Por favor ingrese el valor de la pregunta.");
                    return;
                }

                if (!tipoPregunta) {
                    alert("Por favor selecciona el tipo de pregunta antes de añadir respuestas.");
                    return;
                }

                switch (tipoPregunta) {
                    case "opcmult":
                        addAnswerGroup(respuestasContainer, questionIndexverdadero);
                        break;

                        case "verofal":
                        addAnswerGroupverofal(respuestasContainer, questionIndexverdadero);
                        break;

                        case "selmult":
                        addAnswerGroupMulti(respuestasContainer, questionIndexverdadero);
                        break;
                }
                addAnswerBtn.disabled = true;
            });

            preguntaDiv.appendChild(addAnswerBtn);

            preguntasContainer.appendChild(preguntaDiv);

            preguntaCompletaDiv.appendChild(preguntasContainer);
            preguntaCompletaDiv.appendChild(respuestasContainer);

            contenedorPrincipal.appendChild(preguntaCompletaDiv);

        });


        function addAnswerGroup(respuestasContainer, questionIndexverdadero) {

    const respuestaDiv = document.createElement('div');

    const answersList = document.createElement('div');
    answersList.className = 'respuestas-div';
    respuestaDiv.appendChild(answersList);

    const respuestas_div = document.createElement('div');
    respuestas_div.className = 'respuesta';
    answersList.appendChild(respuestas_div);

    const addAnswerBtn = document.createElement('button');
    addAnswerBtn.type = 'button';
    addAnswerBtn.className = "btn-agregar-respuesta"
    addAnswerBtn.innerText = 'Agregar Respuesta';
    addAnswerBtn.addEventListener('click', function () {
        addAnswer(answersList, questionIndexverdadero);
    });

    answersList.appendChild(addAnswerBtn);

    respuestasContainer.appendChild(respuestaDiv);

    addAnswer(answersList, questionIndexverdadero);
    addAnswer(answersList, questionIndexverdadero);
}

function addAnswer(answersList, questionIndexverdadero) {
    const answerIndex = answersList.childElementCount;

    const respuesta = document.createElement('div');
    respuesta.className = 'respuesta';
    answersList.appendChild(respuesta);

    const singleAnswerDiv = document.createElement('div');
    singleAnswerDiv.className = 'respuesta-content';
    singleAnswerDiv.innerHTML = `
        <input type="radio" name="ingresando-respuesta-correcta-pregunta-${questionIndexverdadero}" value="${answerIndex - 1}" required>
        <input type="text" class="descripcion-respuesta" name="ingresando-respuesta-pregunta-${questionIndexverdadero}-${answerIndex - 1}" placeholder="Ingresa la respuesta" required>
        <input style="display:none;" type="checkbox" name="cantidad-respuestas-${questionIndexverdadero}[]" value="${answerIndex - 1}" checked>
        `;
        
        respuesta.appendChild(singleAnswerDiv);
    answersList.insertBefore(respuesta, answersList.lastElementChild);
}

function addAnswerGroupMulti(respuestasContainer, questionIndexverdadero) {

    const respuestaDiv = document.createElement('div');

    const answersList = document.createElement('div');
    answersList.className = 'respuestas-div';
    respuestaDiv.appendChild(answersList);

    const respuestas_div = document.createElement('div');
    respuestas_div.className = 'respuesta';
    answersList.appendChild(respuestas_div);

    const addAnswerBtn = document.createElement('button');
    addAnswerBtn.type = 'button';
    addAnswerBtn.className = "btn-agregar-respuesta"
    addAnswerBtn.innerText = 'Agregar Respuesta';
    addAnswerBtn.addEventListener('click', function () {
        addAnswerMulti(answersList, questionIndexverdadero);
    });
    answersList.appendChild(addAnswerBtn);
    
    respuestasContainer.appendChild(respuestaDiv);

    addAnswerMulti(answersList, questionIndexverdadero);
    addAnswerMulti(answersList, questionIndexverdadero);
}

function addAnswerMulti(answersList, questionIndexverdadero) {
    const answerIndex = answersList.childElementCount;

    const respuesta = document.createElement('div');
    respuesta.className = 'respuesta';
    answersList.appendChild(respuesta);

    const singleAnswerDiv = document.createElement('div');
    singleAnswerDiv.className = 'respuesta-content';
    singleAnswerDiv.innerHTML = `
        <input type="checkbox" name="ingresando-${questionIndexverdadero}[]" value="${answerIndex - 1}">
        <input type="text" class="descripcion-respuesta" name="ingresando-respuesta-pregunta-${questionIndexverdadero}-${answerIndex - 1}" placeholder="Ingresa la respuesta" required>

        <input style="display:none;" type="checkbox" name="cantidad-respuestas-${questionIndexverdadero}[]" value="${answerIndex + 1}" checked>
        `;
        respuesta.appendChild(singleAnswerDiv);

    answersList.insertBefore(respuesta, answersList.lastElementChild);
}

function addAnswerGroupverofal(respuestasContainer, questionIndexverdadero) {

    const respuestaDiv = document.createElement('div');

    const answersList = document.createElement('div');
    answersList.className = 'respuestas-div';
    respuestaDiv.appendChild(answersList);

    const respuestas_div = document.createElement('div');
    respuestas_div.className = 'respuesta';
    answersList.appendChild(respuestas_div);

    respuestasContainer.appendChild(respuestaDiv);

    addTrueFalseAnswer(answersList, questionIndexverdadero);

}

function addTrueFalseAnswer(answersList, questionIndexverdadero) {
    const answerIndex = answersList.childElementCount;


    const respuesta = document.createElement('div');

    respuesta.className = 'respuesta';
    answersList.appendChild(respuesta);

    const respuesta2 = document.createElement('div');

    respuesta2.className = 'respuesta';
    answersList.appendChild(respuesta2);

    const singleAnswerDiv = document.createElement('div');

    singleAnswerDiv.className = 'respuesta-content';
    singleAnswerDiv.innerHTML = `
            <input type="radio" name="ingresando-respuesta-correcta-pregunta-${questionIndexverdadero}" value="1" required >
        
            <label class="descripcion-respuesta"> Verdadero </label>
    <input style="display:none;" type="checkbox" name="cantidad-respuestas-${questionIndexverdadero}[]" value="${answerIndex}" checked>

    <input type="hidden" name="ingresando-respuesta-pregunta-${questionIndexverdadero}-${answerIndex}" value="Verdadero">
    `;

    const singleAnswerDiv2 = document.createElement('div');
    singleAnswerDiv2.className = 'respuesta-content';
    singleAnswerDiv2.innerHTML = `
            <input type="radio" name="ingresando-respuesta-correcta-pregunta-${questionIndexverdadero}" value="2" required >
        
            <label class="descripcion-respuesta"> Falso </label>

            <input type="hidden" name="ingresando-respuesta-pregunta-${questionIndexverdadero}-${answerIndex+1}" value="Falso">

    <input style="display:none;" type="checkbox" name="cantidad-respuestas-${questionIndexverdadero}[]" value="${answerIndex + 1}" checked>
    `;

    respuesta.appendChild(singleAnswerDiv);
    respuesta2.appendChild(singleAnswerDiv2);

    answersList.insertBefore(respuesta, answersList.lastElementChild);
}
    </script>




<script>
        const textarea = document.getElementById('pregunta');

        textarea.addEventListener('input', function () {
            this.style.height = 'auto';
            this.style.height = `${this.scrollHeight}px`;
        });
    </script>

    
<?php



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //echo "Funciona"."<br>";
    //echo "titulo: ".$_POST['titulo']."<br>";
    examen::updateTitulo($_POST['titulo'], $_GET['examenNum']);

    //echo "tema: ".$_POST['tema']."<br><br>";
    temaExamen::updateTema($_POST['tema'], $_GET['examenNum']);

    examen::updateFinalExamen($_POST['fechaLimite'], $_GET['examenNum']);

    examen::updateInicioExamen($_POST['fechaInicio'], $_GET['examenNum']);

    $i = 1;
    foreach ($preguntas as $pr) {
        //echo "Identificador de Pregunta ".$i.": ".$_POST["id-pregunta-$i"]."<br>";
       // echo "Descripcion de Pregunta ".$i.": ".$_POST["descripcion-pregunta-$i"]."<br>";
        pregunta::updateDescripcion( $_POST["descripcion-pregunta-$i"], $_POST["id-pregunta-$i"]);

        //echo "Valor de Pregunta ".$i.": ".$_POST["valor-pregunta-$i"]."<br>";
        $ires = 1;
        if($_POST["valor-pregunta-$i"] > 100){
            $_POST["valor-pregunta-$i"] = 100;
        }

        $respuestas = respuesta::get_exam($pr -> getNumPregunta());
        pregunta::updateValor($_POST["valor-pregunta-$i"], $_POST["id-pregunta-$i"]);

if($pr -> getCodeTipo() == "selmult"){
    foreach ($respuestas as $r) {
        //echo "Descripcion de Respuesta ".$ires." de pregunta ".$i.": ".$_POST["descripcion-respuesta-".$i."-".$ires]."<br>";
        respuesta::updateDescripcion($_POST["id-respuesta-".$i."-".$ires], $_POST["descripcion-respuesta-".$i."-".$ires]);
        //echo "Identificador de Respuesta ".$ires." de pregunta ".$i.": ".$_POST["id-respuesta-".$i."-".$ires]."<br><br>";
        respuesta::update_0($_POST["id-respuesta-".$i."-".$ires]);
        $ires++;
    }

    $resp = $_POST["$i"];
    $cantidad_correctas = 0;
    foreach ($resp as $res) {
        $cantidad_correctas ++;
        //echo "Respuesta correcta de pregunta ".$i.": ".$res."<br><br>";
    }

    
    $valor_por_respuesta = $_POST["valor-pregunta-$i"] / $cantidad_correctas;

    //echo "el valor de cada respuesta ".$valor_por_respuesta."<br><br>";

    foreach ($resp as $res) {
        respuesta::update_valor( $valor_por_respuesta, $res);
        
    }



} else if($pr -> getCodeTipo() == "opcmult") {
        foreach ($respuestas as $r) {
            //echo "Descripcion de Respuesta ".$ires." de pregunta ".$i.": ".$_POST["descripcion-respuesta-".$i."-".$ires]."<br>";
            respuesta::updateDescripcion($_POST["id-respuesta-".$i."-".$ires], $_POST["descripcion-respuesta-".$i."-".$ires]);

            //echo "Identificador de Respuesta ".$ires." de pregunta ".$i.": ".$_POST["id-respuesta-".$i."-".$ires]."<br><br>";
            respuesta::update_0($_POST["id-respuesta-".$i."-".$ires]);
            $ires++;
        }
                //echo "Respuesta correcta de pregunta ".$i.": ".$_POST["respuesta-correcta-pregunta-".$i]."<br><br><br>";
        respuesta::update_valor( $_POST["valor-pregunta-$i"], $_POST["respuesta-correcta-pregunta-".$i]);
    } else {
        foreach ($respuestas as $r) {
            //echo "Identificador de Respuesta ".$ires." de pregunta ".$i.": ".$_POST["id-respuesta-".$i."-".$ires]."<br><br>";
            respuesta::update_0($_POST["id-respuesta-".$i."-".$ires]);
            $ires++;
        }
            //echo "Respuesta correcta de pregunta ".$i.": ".$_POST["respuesta-correcta-pregunta-".$i]."<br><br><br>";
    respuesta::update_valor( $_POST["valor-pregunta-$i"], $_POST["respuesta-correcta-pregunta-".$i]);
    }
    $i++;
}


$i_preguntas_new = 1;
$i=1;

if (isset($_POST['cantidad-preguntas'])) {
    $array_preguntas = $_POST['cantidad-preguntas'];


    $i = 1;

    foreach ($array_preguntas as $pre) {


        $ultimapr = pregunta::getLastPregunta();
        $id_pr_new = $ultimapr->getNumPregunta() + $i;


        pregunta::insert(
            $id_pr_new,
            $_POST["ingresando-descripcion-pregunta-$i"],
            $_POST["ingresando-valor-pregunta-$i"],
            1,
            $_GET['examenNum'],
            $_POST["ingresando-tipo-pregunta-$i"]
        );

        
        $respuestas_ingresadas = $_POST["cantidad-respuestas-$i"];

        switch ($_POST["ingresando-tipo-pregunta-$i"]) {
            case "opcmult":
                $i_rnew = 1;
                foreach ($respuestas_ingresadas as $ri) {
                    $esCorrecta = ($i_rnew == $_POST["ingresando-respuesta-correcta-pregunta-$i"]);
                    $valor = $esCorrecta ? $_POST["ingresando-valor-pregunta-$i"] : 0;

                    respuesta::insert(
                        1,
                        $valor,
                        $_POST["ingresando-respuesta-pregunta-$i-$i_rnew"],
                        $id_pr_new
                    );

                    $i_rnew++;
                }
                break;

            case "verofal":
                $i_rnew = 1;
                foreach ($respuestas_ingresadas as $ri) {
                    $esCorrecta = ($i_rnew == $_POST["ingresando-respuesta-correcta-pregunta-$i"]);
                    $valor = $esCorrecta ? $_POST["ingresando-valor-pregunta-$i"] : 0;

                    respuesta::insert(
                        1,
                        $valor,
                        $_POST["ingresando-respuesta-pregunta-$i-$i_rnew"],
                        $id_pr_new
                    );

                    $i_rnew++;
                }
                break;

            case "selmult": 
                $valores = $_POST["ingresando-$i"]; 
                $valor_por_respuesta = $_POST["ingresando-valor-pregunta-$i"] / count($valores);

                $i_rnew = 1;
                foreach ($respuestas_ingresadas as $ri) {
                    $esCorrecta = in_array($i_rnew, $valores);
                    $valor = $esCorrecta ? $valor_por_respuesta : 0;

                    respuesta::insert(
                        1,
                        $valor,
                        $_POST["ingresando-respuesta-pregunta-$i-$i_rnew"],
                        $id_pr_new
                    );

                    $i_rnew++;
                }
                break;
        }

        $i++;
    }
}
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
echo "<script>window.location.href = 'examen-ingresar.php?examenNum=$examen_enviar';</script>";
exit();
}

?>

<script>
    const fechaInicio = document.getElementById('fechaInicio');
    const fechaLimite = document.getElementById('fechaLimite');

    const hoy = new Date().toISOString().split('T')[0];

    fechaInicio.setAttribute('min', hoy);
    fechaLimite.setAttribute('min', hoy);

    fechaInicio.addEventListener('change', () => {
        const fechaInicioValor = fechaInicio.value;
        const fechaLimiteValor = fechaLimite.value;

        if (fechaLimiteValor && fechaInicioValor > fechaLimiteValor) {
            alert('La fecha de inicio no puede ser posterior a la fecha límite.');
            fechaInicio.value = '';
        }
    });

    fechaLimite.addEventListener('change', () => {
        const fechaInicioValor = fechaInicio.value;
        const fechaLimiteValor = fechaLimite.value;

        if (fechaInicioValor && fechaLimiteValor < fechaInicioValor) {
            alert('La fecha límite no puede ser anterior a la fecha de inicio.');
            fechaLimite.value = '';
        }
    });
</script>


    </body>