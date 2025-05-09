<?php
session_start();
require_once '../../includes/db_connection.php';

if (!isset($_SESSION['identificador_usuario']) || !isset($_SESSION['tipo_usuario'])) {
    header("Location: ../../login/login.php?error=Acceso no autorizado.");
    exit();
}

$identificador = $_SESSION['identificador_usuario'];
$tipo_usuario = $_SESSION['tipo_usuario'];
$datos_usuario = [];

if ($tipo_usuario === 'administrador') {
    $query = "SELECT id_admin, nombre, apellidoP, apellidoM 
            FROM administrador 
            WHERE id_admin = ?";
} elseif ($tipo_usuario === 'maestro') {
    $query = "SELECT numEmpleado, nombre, apellidoP, apellidoM 
            FROM docente 
            WHERE numEmpleado = ?";
} else {
    echo "Tipo de usuario no reconocido.";
    exit();
}

$stmt = $conn->prepare($query);
$stmt->bind_param("s", $identificador);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $datos_usuario = $result->fetch_assoc();

    $_SESSION['nombre'] = $datos_usuario['nombre'] . " " . 
        $datos_usuario['apellidoP'];
}

$image_base_path = '../../images/users/' . $identificador;
$default_image = '../../images/default/default.png';
$allowed_extensions = ['jpg', 'jpeg', 'png'];
$image_url = $default_image;

foreach ($allowed_extensions as $extension) {
    $image_path = $image_base_path . '.' . $extension;
    if (file_exists($image_path)) {
        $image_url = $image_path;
        break;
    }
}

$nombre = $_SESSION['nombre'] ?? "Usuario desconocido"; 


