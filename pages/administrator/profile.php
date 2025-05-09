<?php
session_start();
include 'header.php';
include_once '../../includes/db_connection.php';
include_once '../../models/admin.php';

if (isset($_SESSION['identificador_usuario'])) {
    $id_admin = $_SESSION['identificador_usuario'];
    $admin = admin::get($id_admin);

    if ($admin):
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nuevoTelefono = $_POST['telefono'];
            $nuevaContraseña = $_POST['password'];
            $confirmarContraseña = $_POST['confirm-password'];

            if ($nuevaContraseña !== $confirmarContraseña) {
                echo "Las contraseñas no coinciden.";
            } else {
                if (!empty($nuevoTelefono) && $nuevoTelefono !== $admin->getTelefono()) {
                    admin::actualizartelefono($nuevoTelefono, $id_admin);
                    echo "Teléfono actualizado con éxito.<br>";
                }

                if (!empty($nuevaContraseña)) {
                    $hashedPassword = password_hash($nuevaContraseña, PASSWORD_DEFAULT);
                    admin::actualizarcontra($hashedPassword, $id_admin);
                    echo "Contraseña actualizada con éxito.<br>";
                }
            }
        }

        $image_base_path = '../../images/users/' . $id_admin;
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
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/profile.css">
    <link rel="icon" href="../../images/favicon/favicon.ico" type="image/x-icon">
    <title>Mi Perfil</title>
</head>
<body>
    <div class="profile-container">
        <div class="profile-content">
            <div class="profile-header">
                <img src="<?php echo $image_url; ?>" alt="Imagen perfil">
                <div>
                    <h1><?php echo $admin->getNombre() . " " . $admin->getApellidoP() . " " . $admin->getApellidoM(); ?></h1>
                    <p><?php echo $admin->getCorreo(); ?></p>
                </div>
            </div>

            <div class="profile-info">
                <div class="info-item">
                    <label>ID Admin: </label>
                    <span><?php echo $admin->getid_admin(); ?></span>
                </div>
                <div class="info-item">
                    <label>Teléfono:</label>
                    <span><?php echo $admin->getTelefono(); ?></span>
                </div>
                <div class="info-item">
                    <label>Género:</label>
                    <span><?php echo $admin->getGenero(); ?></span>
                </div>
                <div class="info-item">
                    <label>Fecha de Nacimiento:</label>
                    <span><?php echo $admin->getFechaNacimiento(); ?></span>
                </div>
                <div class="info-item">
                    <label>Fecha de registro: </label>
                    <span><?php echo $admin->getfechacreacion(); ?></span>
                </div>
            </div>

            <div class="form-section">
                <h2>Actualizar Información</h2>
                <form action="profile.php" method="POST" class="form-group">
                    <label for="telefono">Actualizar telefono:</label>
                    <input type="text" id="telefono" name="telefono" value="<?php echo $admin->getTelefono(); ?>" required>

                    <label for="password">Nueva Contraseña:</label>
                    <input type="password" id="password" name="password" placeholder="Ingresa una nueva contraseña">

                    <label for="confirm-password">Confirmar Contraseña:</label>
                    <input type="password" id="confirm-password" name="confirm-password" placeholder="Confirma tu nueva contraseña">

                    <div class="btn-container">
                        <button type="submit">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
<?php else: ?>
    <p>No se encontraron datos para el administrador.</p>
<?php endif; ?>
<?php
}
?>
