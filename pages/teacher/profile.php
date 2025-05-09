<?php
include ('header.php');
include ('../../models/teacher.php');


$label = "Perfil";

if (isset($_SESSION['identificador_usuario']) && isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] === 'maestro') {

    $username = $_SESSION['identificador_usuario'];

    $a = teacher::get($_SESSION['identificador_usuario']);

    if (isset($_POST['telefono']) && $_POST['telefono'] != "" && isset($_POST['contrasena']) && $_POST['contrasena'] != "") {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $nuevoTelefono = $_POST['telefono'];
            $nuevaContrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);
            
            if ($_POST['contrasena2'] == $_POST['contrasena']){
                teacher::actualizarcontra($nuevaContrasena, $username);
                teacher::actualizartelefono($nuevoTelefono, $username);
                $label = "Datos Actualizados";
            } else {
                echo "<script>alert('Error: Las contraseñas no coinciden.');</script>";
                $label = "Teléfono actualizado";
                teacher::actualizartelefono($nuevoTelefono, $username);
            }
        }
    } else if (isset($_POST['contrasena']) && $_POST['contrasena'] != "") {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nuevaContrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);

            if ($_POST['contrasena2'] == $_POST['contrasena']){
                teacher::actualizarcontra($nuevaContrasena, $username);
                $label = "Contraseña actualizada";
            } else {
                echo "<script>alert('Error: Las contraseñas no coinciden.');</script>";
            }
        }
    } else if (isset($_POST['telefono']) && $_POST['telefono'] != "") {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nuevoTelefono = $_POST['telefono'];
            teacher::actualizartelefono($nuevoTelefono, $username);
            $label = "Teléfono actualizado";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/profile.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../images/favicon/favicon.ico" type="image/x-icon">
    <title>Perfil</title>
</head>
<body>
<script src="../../login/js/login.js"></script>

    <label id="actualizar"><?php echo $label ?></label>

    <div class="profile-card">
        <img id="profilephoto" src="../../images/users/<?php echo $a->getFoto(); ?>"></img>
        <div class="info-section">
            <div class="info-group">
                <label>Empleado:</label>
                <span><?php echo $a->getnumEmpleado(); ?></span>
            </div>
            <div class="info-group">
                <label>Nombre:</label>
                <span><?php echo $a->getApellidoP() ." ". $a->getApellidoM() . " " . $a->getNombre(); ?></span>
            </div>
            <div class="info-group">
                <label>Fecha de Nacimiento:</label>
                <span><?php echo $a->getFechaNacimiento(); ?></span>
            </div>
            <div class="info-group">
                <label>Curp:</label>
                <span><?php echo $a->getCurp(); ?></span>
            </div>
            <div class="info-group">
                <label>Correo:</label>
                <span><?php echo $a->getCorreo(); ?></span>
            </div>
            <div class="info-group">
                <label>Teléfono:</label>
                <span><?php echo $a->getTelefono(); ?></span>
            </div>
        </div>

        <h3>Actualizar Datos</h3>
        <form method="POST" class="update-form">
            <div class="form-group">
                <label for="telefono">Teléfono:</label>
                <input type="text" id="telefono" name="telefono" placeholder="<?php echo $a->getTelefono(); ?>"  pattern="[0-9]+" 
                oninput="this.value = this.value.replace(/[^0-9]/g, '');" >
            </div>
            <div class="form-group">
                <label for="contrasena">Nueva Contraseña:</label>
                <div class="textbox password-container">
                    <input type="password" id="password" name="contrasena" placeholder="••••••••" />
                    <i id="togglePassword" class="fas fa-eye" onclick="togglePasswordVisibility()"></i>
                </div>
            </div>
            
            <div class="form-group">
                <label for="contrasena">Confirmar Contraseña:</label>
                <input type="password" id="passwordConfirmation" name="contrasena2"  placeholder="••••••••">
            </div>

            <button type="submit">Guardar Cambios</button>
        </form>
    </div>
</body>
</html>

