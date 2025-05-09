<?php
session_start();
include ('../../includes/db_connection.php');
include ('../../includes/authentication.php');

checkAuthentication('administrador');



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $matricula = $conn->real_escape_string($_POST['matricula']);
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $apellidoP = $conn->real_escape_string($_POST['apellidoP']);
    $apellidoM = $conn->real_escape_string($_POST['apellidoM']);
    $promedio = $conn->real_escape_string($_POST['promedio']);
    $telefono = $conn->real_escape_string($_POST['telefono']);
    $fechaNacimiento = $conn->real_escape_string($_POST['fechaNacimiento']);
    $curp = $conn->real_escape_string($_POST['curp']);
    $genero = $conn->real_escape_string($_POST['genero']);




    $sql_alumno = "UPDATE alumno SET 
                nombre = '$nombre', 
                apellidoP = '$apellidoP', 
                apellidoM = '$apellidoM', 
                telefono = '$telefono', 
                curp = '$curp', 
                fechaNacimiento = '$fechaNacimiento',
                promedio = '$promedio'
                WHERE
                matricula = '$matricula'
                ";

    if ($conn->query($sql_alumno) === TRUE) {
        echo json_encode(['success' => true, 'message' => "Estudiante actualizado exitosamente."]);
        exit();
    } else {
        echo json_encode(['success' => false, 'error' => "Error al registrar en alumno: " . $conn->error]);
        exit();
    }

    $conn->close();
} else {
    echo json_encode(['success' => false, 'error' => "Solicitud no válida."]);
    exit();
}
?>
