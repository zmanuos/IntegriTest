<?php
session_start();
include('../../includes/db_connection.php');
include('../../includes/authentication.php');

checkAuthentication('administrador');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = ucwords(strtolower($conn->real_escape_string($_POST['nombre'])));
    $apellidoP = ucwords(strtolower($conn->real_escape_string($_POST['apellidoP'])));
    $apellidoM = ucwords(strtolower($conn->real_escape_string($_POST['apellidoM'])));
    $telefono = $conn->real_escape_string($_POST['telefono']);
    $genero = $conn->real_escape_string($_POST['genero']);
    $fechaNacimiento = $conn->real_escape_string($_POST['fechaNacimiento']);
    $curp = strtoupper($conn->real_escape_string($_POST['curp']));

    $fechaActual = date('Y-m-d');
    $fechaMinima = date('Y-m-d', strtotime('-100 years', strtotime($fechaActual)));
    $fechaMaxima = $fechaActual;

    if ($fechaNacimiento < $fechaMinima || $fechaNacimiento > $fechaMaxima) {
        echo json_encode(['success' => false, 'error' => 'La fecha de nacimiento debe indicar una edad entre 0 y 100 años.']);
        exit();
    }

    if (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]{1,30}$/", $nombre) ||
        !preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]{1,30}$/", $apellidoP) ||
        (!empty($apellidoM) && !preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]{1,30}$/", $apellidoM))
    ) {
        echo json_encode(['success' => false, 'error' => 'Los nombres y apellidos solo pueden contener letras, espacios y acentos, con un máximo de 30 caracteres.']);
        exit();
    }

    if (!preg_match("/^[0-9]{10}$/", $telefono)) {
        echo json_encode(['success' => false, 'error' => 'El teléfono solo puede contener números, sin espacios ni letras, y debe de tener 10 caracteres.']);
        exit();
    }

    if (!preg_match("/^[A-Z0-9]{18}$/", $curp)) {
        echo json_encode(['success' => false, 'error' => 'La CURP debe tener exactamente 18 caracteres, solo números y letras mayúsculas sin espacios ni símbolos.']);
        exit();
    }

    $sql_check_curp_docente = "SELECT * FROM docente WHERE curp = '$curp'";
    $sql_check_curp_alumno = "SELECT * FROM alumno WHERE curp = '$curp'";
    $result_check_curp_docente = $conn->query($sql_check_curp_docente);
    $result_check_curp_alumno = $conn->query($sql_check_curp_alumno);

    if ($result_check_curp_docente->num_rows > 0 || $result_check_curp_alumno->num_rows > 0) {
        echo json_encode(['success' => false, 'error' => 'La CURP ya está registrada en el sistema.']);
        exit();
    }

    $sql_check_telefono_docente = "SELECT * FROM docente WHERE telefono = '$telefono'";
    $result_check_telefono_docente = $conn->query($sql_check_telefono_docente);

    if ($result_check_telefono_docente->num_rows > 0) {
        echo json_encode(['success' => false, 'error' => 'El teléfono ya está registrado en el sistema.']);
        exit();
    }

    $sql_check_telefono_alumno = "SELECT * FROM alumno WHERE telefono = '$telefono'";
    $result_check_telefono_alumno = $conn->query($sql_check_telefono_alumno);

    if ($result_check_telefono_alumno->num_rows > 0) {
        echo json_encode(['success' => false, 'error' => 'El teléfono ya está registrado en el sistema.']);
        exit();
    }

    $sql_insert = "INSERT INTO docente (nombre, apellidoP, apellidoM, telefono, genero, fechaNacimiento, curp) 
                VALUES ('$nombre', '$apellidoP', '$apellidoM', '$telefono', '$genero', '$fechaNacimiento', '$curp')";

    if ($conn->query($sql_insert) === TRUE) {
        $sql_numEmpleado = "SELECT numEmpleado, foto, correo FROM docente WHERE curp = '$curp'";
        $result = $conn->query($sql_numEmpleado);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $numEmpleado = $row['numEmpleado'];
            $fotoFinalName = $row['foto'];
            $correo = $row['correo'];

            if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
                $fotoTmpPath = $_FILES['foto']['tmp_name'];
                $fotoType = $_FILES['foto']['type'];
                $fotoSize = $_FILES['foto']['size'];
                $fotoExtension = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));

                if ($fotoSize > 5 * 1024 * 1024) {
                    echo json_encode(['success' => false, 'error' => 'La foto no debe pesar más de 5 MB.']);
                    exit();
                }

                $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                if (!in_array($fotoType, $allowedTypes)) {
                    echo json_encode(['success' => false, 'error' => 'Solo se permiten imágenes JPEG y PNG.']);
                    exit();
                }

                $image = null;
                if ($fotoType == 'image/jpeg' || $fotoType == 'image/jpg') {
                    $image = imagecreatefromjpeg($fotoTmpPath);
                } elseif ($fotoType == 'image/png') {
                    $image = imagecreatefrompng($fotoTmpPath);
                }

                $resizedImage = imagecreatetruecolor(1080, 1080);
                $originalWidth = imagesx($image);
                $originalHeight = imagesy($image);
                imagecopyresampled($resizedImage, $image, 0, 0, 0, 0, 1080, 1080, $originalWidth, $originalHeight);

                $uploadDir = '../../images/users/';
                $fotoDestPath = $uploadDir . $fotoFinalName;

                if ($fotoType == 'image/jpeg' || $fotoType == 'image/jpg') {
                    imagejpeg($resizedImage, $fotoDestPath, 90);
                } elseif ($fotoType == 'image/png') {
                    imagepng($resizedImage, $fotoDestPath, 9);
                }

                imagedestroy($image);
                imagedestroy($resizedImage);
            } else {
                $fotoFinalName = 'default_profile.jpg';
            }

            echo json_encode([
                'success' => true,
                'numEmpleado' => $numEmpleado,
                'nombre' => $nombre,
                'apellidoP' => $apellidoP,
                'apellidoM' => $apellidoM,
                'telefono' => $telefono,
                'genero' => $genero,
                'fechaNacimiento' => $fechaNacimiento,
                'curp' => $curp,
                'foto' => $fotoFinalName,
                'correo' => $correo,
            ]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Error al obtener los datos del nuevo docente.']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Error al registrar el docente.']);
    }
}
?>