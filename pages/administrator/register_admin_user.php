<?php
include('../../includes/db_connection.php');
session_start();

include('../../includes/authentication.php');
checkAuthentication('administrador');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
    $apellidoP = mysqli_real_escape_string($conn, $_POST['apellidoP']);
    $apellidoM = mysqli_real_escape_string($conn, $_POST['apellidoM']);
    $telefono = mysqli_real_escape_string($conn, $_POST['telefono']);
    $fechaNacimiento = mysqli_real_escape_string($conn, $_POST['fechaNacimiento']);
    $genero = mysqli_real_escape_string($conn, $_POST['genero']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $query1 = "INSERT INTO administrador (nombre, apellidoP, apellidoM, telefono, fecha_creacion, fechaNacimiento, genero)
            VALUES ('$nombre', '$apellidoP', '$apellidoM', '$telefono', CURDATE(), '$fechaNacimiento', '$genero')";

    if (mysqli_query($conn, $query1)) {
        $query_get_id_admin = "SELECT id_admin FROM administrador WHERE nombre = '$nombre' AND apellidoP = '$apellidoP' AND telefono = '$telefono' AND fecha_creacion = CURDATE() AND fechaNacimiento = '$fechaNacimiento' AND genero = '$genero' LIMIT 1";
        $result = mysqli_query($conn, $query_get_id_admin);

        if ($result) {
            $row = mysqli_fetch_assoc($result);
            $id_admin = $row['id_admin'];

            if (empty($id_admin)) {
                echo json_encode(['success' => false, 'error' => "Error: id_admin está vacío o no se generó correctamente."]);
                exit();
            }

            $fotoFinalName = null;
            if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
                $fotoTmpPath = $_FILES['foto']['tmp_name'];
                $fotoType = $_FILES['foto']['type'];

                $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                if (!in_array($fotoType, $allowedTypes)) {
                    echo json_encode(['success' => false, 'error' => 'Solo se permiten imágenes JPEG y PNG.']);
                    exit();
                }

                if ($fotoType == 'image/jpeg') {
                    $image = imagecreatefromjpeg($fotoTmpPath);
                } elseif ($fotoType == 'image/png') {
                    $image = imagecreatefrompng($fotoTmpPath);
                } elseif ($fotoType == 'image/jpg') {
                    $image = imagecreatefromjpeg($fotoTmpPath);
                }

                $width = 1080;
                $height = 1080;

                $resizedImage = imagecreatetruecolor($width, $height);
                imagecopyresampled($resizedImage, $image, 0, 0, 0, 0, $width, $height, imagesx($image), imagesy($image));

                $fotoFinalName = $id_admin . '.' . pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
                $uploadDir = '../../images/users/';
                $fotoDestPath = $uploadDir . $fotoFinalName;

                if ($fotoType == 'image/jpeg') {
                    imagejpeg($resizedImage, $fotoDestPath);
                } elseif ($fotoType == 'image/png') {
                    imagepng($resizedImage, $fotoDestPath);
                } elseif ($fotoType == 'image/jpg') {
                    imagejpeg($resizedImage, $fotoDestPath);
                }

                imagedestroy($image);
                imagedestroy($resizedImage);
            }

            $query2 = "INSERT INTO usuarios (password, tipo_usuario, identificador_usuario)
                        VALUES ('$password', 'administrador', '$id_admin')";

            if (mysqli_query($conn, $query2)) {
                echo json_encode(['success' => true, 'id_admin' => $id_admin]);
                exit();
            } else {
                echo json_encode(['success' => false, 'error' => "Error al registrar en la tabla usuarios."]);
                exit();
            }
        } else {
            echo json_encode(['success' => false, 'error' => "Error: No se pudo obtener el id_admin."]);
            exit();
        }
    } else {
        echo json_encode(['success' => false, 'error' => "Error al registrar el administrador."]);
        exit();
    }
} else {
    echo json_encode(['success' => false, 'error' => "Solicitud no válida."]);
    exit();
}
?>
