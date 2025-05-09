<?php
include("header.php");
include('../../includes/authentication.php');
include('../../models/examen.php');
include('../../models/respuesta.php');
include('../../models/pregunta.php');

checkAuthentication("administrador");

$examenId = isset($_GET['id']) ? $_GET['id'] : null;
$examen = examen::getExamenById($examenId);

if (!$examen) {
    echo "Examen no encontrado.";
    exit();
}

$temas = examen::getTemas($examenId);
$docenteDetails = examen::getDocenteDetails($examen->getNumEmpleado());

$preguntas = pregunta::get_exam($examenId);

$tiposPregunta = [
    'verofal' => 'Pregunta de verdadero o falso',
    'selmult' => 'Pregunta de selección múltiple',
    'opcmult' => 'Pregunta de opción múltiple'
];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['preguntaId']) && isset($_POST['preguntaDescripcion'])) {
        $preguntaId = $_POST['preguntaId'];
        $descripcion = $_POST['preguntaDescripcion'];

        $updated = pregunta::updateDescripcion($preguntaId, $descripcion);

        if ($updated) {
            echo "<script>
                    alert('Descripción de la pregunta actualizada correctamente.');
                    mostrarNotificacion('Pregunta actualizada', 'success');
                </script>";
        } else {
            echo "<script>
                    alert('No se pudo actualizar la descripción de la pregunta.');
                    mostrarNotificacion('Error al actualizar', 'error');
                </script>";
        }
    }

    if (isset($_POST['respuestaId']) && isset($_POST['descripcion']) && isset($_POST['valor'])) {
        $respuestaId = $_POST['respuestaId'];
        $descripcionRespuesta = $_POST['descripcion'];
        $valorRespuesta = $_POST['valor'];

        $updatedRespuesta = respuesta::updateRespuesta($respuestaId, $descripcionRespuesta, $valorRespuesta);

        if ($updatedRespuesta) {
            echo "<script>
                    alert('Descripción de la respuesta actualizada correctamente.');
                    mostrarNotificacion('Respuesta actualizada', 'success');
            </script>";
        } else {
            echo "<script>
                    alert('No se pudo actualizar la descripción de la respuesta.');
                    mostrarNotificacion('Error al actualizar', 'error');
                </script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../images/favicon/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/query_exam_details.css">
    <title>Detalles del Examen</title>
</head>
<body>
    <div class="exam-container">
        <div class="header">
            <h1>
            <img src="../../images/header/logoOF.png" alt="IntegriTest Logo" class="logo">
            IntegriTest</h1>
            <div class="exam-info">
                <h2><?= htmlspecialchars($examen->getTitulo()) ?></h2>
                <p><strong>ID:</strong> <?= htmlspecialchars($examen->getNumExam()) ?></p>
                <p><strong>Tema(s):</strong> <?= implode(', ', $temas) ?></p>
                <p><strong>Fecha Inicio:</strong> <?= htmlspecialchars($examen->getInicioExamen()) ?></p>
                <p><strong>Fecha Final:</strong> <?= htmlspecialchars($examen->getFinalExamen()) ?></p>
                <p><strong>Estado:</strong> <?= $examen->getNumEstado() == 1 ? 'Activo' : 'Inactivo' ?></p>
            </div>
        </div>

        <div class="docente-details">
            <h3>Detalles del Docente</h3>
            <p><strong>Nombre:</strong> <?= htmlspecialchars($docenteDetails['nombre'] . ' ' . $docenteDetails['apellido']) ?></p>
            <p><strong>Número de Empleado:</strong> <?= htmlspecialchars($docenteDetails['numEmpleado']) ?></p>
            <p><strong>Correo:</strong> <?= htmlspecialchars($docenteDetails['correo']) ?></p>
            <p><strong>Teléfono:</strong> <?= htmlspecialchars($docenteDetails['telefono']) ?></p>
        </div>

        <div class="preguntas-section">
            <h3>Preguntas del Examen</h3>
            <?php if (count($preguntas) > 0): ?>
                <ul>
                    <?php foreach ($preguntas as $pregunta): ?>
                        <li id="pregunta-<?= htmlspecialchars($pregunta->getNumPregunta()) ?>">
                            <p><strong>Pregunta <?= htmlspecialchars($pregunta->getPreguntaNumero()) ?>:</strong> 
                                <span id="descripcionPregunta"><?= htmlspecialchars($pregunta->getDescripcion()) ?></span>
                            </p>
                            <p><strong>Valor:</strong> <?= htmlspecialchars($pregunta->getValor()) ?></p>

                            <p><strong>Tipo:</strong> <?= isset($tiposPregunta[$pregunta->getCodeTipo()]) ? $tiposPregunta[$pregunta->getCodeTipo()] : 'Desconocido' ?></p>

                            <button onclick="openEditPreguntaModal(<?= $pregunta->getNumPregunta() ?>)">Editar Pregunta</button>

                            <h4>Respuestas</h4>
                            <?php 
                            $respuestas = respuesta::get_exam($pregunta->getNumPregunta()); 
                            if (count($respuestas) > 0): ?>
                                <ul>
                                    <?php foreach ($respuestas as $respuesta): ?>
                                        <li id="respuesta-<?= htmlspecialchars($respuesta->getNumRespuesta()) ?>">
                                            <p><strong>Respuesta <?= htmlspecialchars($respuesta->getRespuestaNum()) ?>:</strong> 
                                                <span class="descripcion"><?= htmlspecialchars($respuesta->getDescripcion()) ?></span>
                                            </p>
                                            <p><strong>Valor:</strong> <span class="valor"><?= htmlspecialchars($respuesta->getValor()) ?></span></p>
                                            <button onclick="openEditRespuestaModal(<?= $respuesta->getNumRespuesta() ?>)">Editar Respuesta</button>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                <p>No hay respuestas para esta pregunta.</p>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No hay preguntas disponibles para este examen.</p>
            <?php endif; ?>
        </div>
    </div>

    <div id="editPreguntaModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('editPreguntaModal')">&times;</span>
            <h3>Editar Pregunta</h3>
            <form id="editPreguntaForm" method="POST" action="">
                <input type="hidden" id="preguntaId" name="preguntaId">
                <label for="preguntaDescripcion">Descripción de la Pregunta:</label>
                <textarea id="preguntaDescripcion" name="preguntaDescripcion" rows="4" cols="50"></textarea>
                <br>
                <button type="submit">Actualizar</button>
            </form>
        </div>
    </div>

    <div id="editRespuestaModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('editRespuestaModal')">&times;</span>
            <h3>Editar Respuesta</h3>
            <form id="editRespuestaForm" method="POST" action="">
                <input type="hidden" id="respuestaId" name="respuestaId">
                <label for="respuestaDescripcion">Descripción de la Respuesta:</label>
                <textarea id="respuestaDescripcion" name="descripcion" required></textarea>

                <label for="respuestaValor">Valor de la Respuesta:</label>
                <input type="number" id="respuestaValor" name="valor" required>

                <button type="submit">Actualizar</button>
            </form>
        </div>
    </div>

    <div id="notification" class="notification"></div>

    <script>
        function openEditPreguntaModal(preguntaId) {
            document.getElementById("preguntaId").value = preguntaId;
            var preguntaDescripcion = document.getElementById("descripcionPregunta").textContent;
            document.getElementById("preguntaDescripcion").value = preguntaDescripcion;
            document.getElementById("editPreguntaModal").style.display = "block";
        }

        function openEditRespuestaModal(respuestaId) {
            document.getElementById("respuestaId").value = respuestaId;
            var respuestaDescripcion = document.getElementById("respuesta-" + respuestaId).querySelector(".descripcion").textContent;
            var respuestaValor = document.getElementById("respuesta-" + respuestaId).querySelector(".valor").textContent;
            document.getElementById("respuestaDescripcion").value = respuestaDescripcion;
            document.getElementById("respuestaValor").value = respuestaValor;
            document.getElementById("editRespuestaModal").style.display = "block";
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = "none";
        }

        function mostrarNotificacion(mensaje, tipo) {
            var notification = document.getElementById("notification");
            notification.textContent = mensaje;
            notification.classList.add(tipo);
            notification.style.display = "block";
            setTimeout(function () {
                notification.style.display = "none";
                notification.classList.remove(tipo);
            }, 3000);
        }
    </script>
</body>
</html>
