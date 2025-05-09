<?php
function checkAuthentication($required_user_type) {
    if (!isset($_SESSION['identificador_usuario'])) {
        header("Location: ../../login/login.php?error=Acceso no autorizado.");
        exit();
    }

    if ($_SESSION['tipo_usuario'] !== $required_user_type) {
        header("Location: ../../login/login.php?error=Acceso no autorizado.");
        exit();
    }
}
?>
