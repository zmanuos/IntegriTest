<?php
include '../../models3/students.php';
$matricula = $_SESSION['identificador_usuario'];

$student = student::get($matricula);

if ($student) {
    $nombre_completo = $student->getNombre() . " " . $student->getApePat() . " " . $student->getApeMat();
} else {
    echo "No se encontró información del estudiante.";
}

$image_base_path = '../../images/users/' . $_SESSION['identificador_usuario'];
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
?>