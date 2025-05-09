<?php
session_start();
include('../includes/db_connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $identificador_usuario = $conn->real_escape_string($_POST['identificador_usuario']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM usuarios WHERE identificador_usuario = '$identificador_usuario'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $storedPassword = $row['password'];
        $isPasswordValid = false;

        if (password_verify($password, $storedPassword)) {
            $isPasswordValid = true;
        } elseif (strlen($storedPassword) === 64) { 
            $password_hashed = hash('sha256', $password);
            $isPasswordValid = ($storedPassword === $password_hashed);
        }

        if ($isPasswordValid) {
            if ($password === $identificador_usuario) {
                $_SESSION['identificador_usuario'] = $row['identificador_usuario'];
                $_SESSION['tipo_usuario'] = $row['tipo_usuario'];
                $_SESSION['requiere_cambio_contrasena'] = true;

                header("Location: first_login.php");
                exit;
            }

            $_SESSION['identificador_usuario'] = $row['identificador_usuario'];
            $_SESSION['tipo_usuario'] = $row['tipo_usuario'];

            switch ($row['tipo_usuario']) {
                case 'administrador':
                    header("Location: ../pages/administrator/dashboard.php");
                    break;
                case 'maestro':
                    header("Location: ../pages/teacher/view_groups.php");
                    break;
                case 'alumno':
                    header("Location: ../pages/student/materias.php");
                    break;
            }
            exit;
        } else {
            $error = "Contraseña incorrecta.";
        }
    } else {
        $error = "Usuario no encontrado.";
    }

    header("Location: login.php?error=" . urlencode($error));
    exit;
}

$conn->close();
?>
