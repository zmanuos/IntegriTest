<?php
include('../includes/db_connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $curp = $_POST['curp'];

    if (strlen($curp) !== 18) {
        http_response_code(400); 
        echo "CURP inválido.";
        exit;
    }

    $query_alumno = "SELECT matricula FROM alumno WHERE curp = ?";
    $stmt = $conn->prepare($query_alumno);
    $stmt->bind_param("s", $curp);
    $stmt->execute();
    $stmt->bind_result($matricula);
    $stmt->fetch();
    $stmt->close();

    if ($matricula) {
        echo $matricula;
        exit;
    }

    $query_docente = "SELECT numEmpleado FROM docente WHERE curp = ?";
    $stmt = $conn->prepare($query_docente);
    $stmt->bind_param("s", $curp);
    $stmt->execute();
    $stmt->bind_result($numEmpleado);
    $stmt->fetch();
    $stmt->close();

    if ($numEmpleado) {
        echo $numEmpleado; 
        exit;
    }

    http_response_code(404); 
    echo "No se encontró un usuario con ese CURP.";
} else {
    http_response_code(405); 
    echo "Método no permitido.";
}
?>
