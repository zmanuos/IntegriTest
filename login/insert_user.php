<?php
include('../includes/db_connection.php');

$identificador_usuario = '1';
$password = 'admin';
$hashed_password = password_hash($password, PASSWORD_DEFAULT); 

$stmt_usuario = $conn->prepare("INSERT INTO usuarios (identificador_usuario, password) VALUES (?, ?)");
$stmt_usuario->bind_param("ss", $identificador_usuario, $hashed_password);

if ($stmt_usuario->execute()) {
    echo "Usuario registrado exitosamente.";
} else {
    echo "Error al registrar usuario: " . $stmt_usuario->error;
}

$stmt_usuario->close();
$conn->close();
?>
