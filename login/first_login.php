<?php
session_start();
include('../includes/db_connection.php');


if (!isset($_SESSION['identificador_usuario']) || !isset($_SESSION['tipo_usuario'])) {
    header("Location: login.php");
    exit;
}

$identificador_usuario = $_SESSION['identificador_usuario'];
$tipo_usuario = $_SESSION['tipo_usuario'];

$saludo = '';
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nueva_contrasena = $_POST['nueva_contrasena'];
    $confirmar_contrasena = $_POST['confirmar_contrasena'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nueva_contrasena = $_POST['nueva_contrasena'];
    $confirmar_contrasena = $_POST['confirmar_contrasena'];

    if ($nueva_contrasena !== $confirmar_contrasena) {
        $error = "Las contraseñas no coinciden. Por favor, intentalo de nuevo.";
    } elseif ($nueva_contrasena === $identificador_usuario) {
        $error = "La nueva contraseña no puede ser igual que tu número de usuario.";
    } elseif (strlen($nueva_contrasena) < 8) {
        $error = "La contraseña debe tener al menos 8 caracteres.";
    } else {
        $hashed_password = password_hash($nueva_contrasena, PASSWORD_DEFAULT);

        $sql = "UPDATE usuarios SET password = ? WHERE identificador_usuario = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ss", $hashed_password, $identificador_usuario);
            if ($stmt->execute()) {
                $success = "Contraseña cambiada exitosamente.";

                    switch ($tipo_usuario) {
                    case 'administrador':
                        header("Location: ../pages/administrator/dashboard.php");
                        break;
                    case 'maestro':
                        header("Location: ../pages/teacher/dashboard.php");
                        break;
                    case 'alumno':
                        header("Location: ../pages/student/dashboard.php");
                        break;
                }
                exit;
            } else {
                $error = "Error al cambiar la contraseña. Inténtelo nuevamente.";
            }
            $stmt->close();
        } else {
            $error = "Error al preparar la consulta para actualizar.";
        }
    }
}
}

if ($tipo_usuario === 'maestro') {
    $tabla = 'docente';
    $campo_identificador = 'numEmpleado';
    $campo_nombre = 'nombre';
    $campo_genero = 'genero';
} elseif ($tipo_usuario === 'alumno') {
    $tabla = 'alumno';
    $campo_identificador = 'matricula';
    $campo_nombre = 'nombre';
    $campo_genero = 'genero';
} else {
    $tabla = null;
}

if ($tabla) {
    $sql = "SELECT $campo_nombre, $campo_genero FROM $tabla WHERE $campo_identificador = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $identificador_usuario);
        $stmt->execute();
        $stmt->bind_result($nombre, $genero);

        if ($stmt->fetch()) {
            $primer_nombre = strtok($nombre, ' ');
            $saludo = $genero === 'H' ? "Bienvenido" : ($genero === 'M' ? "Bienvenida" : "Bienvenido/a");
        } else {
            $error = "Usuario no encontrado en la tabla $tabla.";
        }
        $stmt->close();
    } else {
        $error = "Error al preparar la consulta para obtener datos del usuario.";
    }
} else {
    $error = "Tipo de usuario no válido.";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/first_login.css">
    <link rel="stylesheet" href="../css/fontawesome/fontawesome.css">
    <link rel="stylesheet" href="../css/fontawesome/solid.css">
    <title>Cambiar Contraseña</title>
</head>
<body>
    <div class="contenedor">
        <div class="formulario">
            <div class="imagen">
                <img src="../images/header/logoGenius.png" alt="Logo">
            </div>
            <div class="formulario-contenido">
                <h3><?php echo "$saludo a IntegriTest, $primer_nombre!"; ?></h3>
                <p>Por razones de seguridad, debes cambiar tu contraseña.</p>
                <form action="first_login.php" method="POST">
                    <div class="input-container">
                        <label for="nueva_contrasena">Nueva contraseña:</label>
                        <input type="password" name="nueva_contrasena" required id="nueva_contrasena">
                        <i id="togglePasswordNueva" class="fas fa-eye" onclick="togglePasswordVisibilityL('nueva_contrasena', 'togglePasswordNueva')"></i>
                    </div>
                    <div class="input-container">
                        <label for="confirmar_contrasena">Confirmar nueva contraseña:</label>
                        <input type="password" name="confirmar_contrasena" required id="confirmar_contrasena">
                        <i id="togglePasswordConfirm" class="fas fa-eye" onclick="togglePasswordVisibilityL('confirmar_contrasena', 'togglePasswordConfirm')"></i>
                    </div>
                    <input type="submit" value="Cambiar contraseña">
                </form>
                <script src="js/first_login.js"></script>
                <?php if (!empty($error)): ?>
                    <script>
                        var errorMessage = <?php echo json_encode($error); ?>;
                        showNotificationL(errorMessage, "error");
                    </script>
                <?php endif; ?>
                <?php if (!empty($success)): ?>
                    <script>
                        var successMessage = <?php echo json_encode($success); ?>;
                        showNotificationL(successMessage, "success");
                    </script>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
