<!DOCTYPE html>
<?php
include('../includes/db_connection.php');
?>
<html lang="es">
<head>
    <link rel="stylesheet" href="../css/fontawesome/fontawesome.css">
    <link rel="stylesheet" href="../css/fontawesome/solid.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/login.css">
    <title>Login - IntegriTest</title>

    <link rel="icon" href="../images/favicon/favicon.ico" type="image/x-icon">
</head>
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <div class="logo-container">
                <img src="../images/header/logoOF.png" alt="Logo de IntegriTest">
                <h4>IntegriTest</h4>
            </div>
            <form action="login_process.php" method="POST">
                <div class="textbox">
                    <div class="textbox-title">Usuario</div>
                    <input type="text" id="identificador_usuario" name="identificador_usuario" required placeholder="" oninput="validateNumbers(event)" />
                </div>
                <div class="textbox password-container">
                <div class="textbox-title">
                        <p>Contraseña</p>
                        <i class="fas fa-info-circle info-icon">
                            <div class="tooltip">La contraseña es tu número de usuario. Al ingresar, podrás cambiarla.</div>
                        </i>
                    </div>
                    <input type="password" id="password" name="password" required placeholder="" />
                    <i id="togglePassword" class="fas fa-eye" onclick="togglePasswordVisibility()"></i>
                </div>
                <button type="submit" class="btn">Iniciar sesión</button>
            </form>
            <p class="forgot-user">
                <a  onclick="toggleRecoveryMenu()">¿Olvidaste tu usuario?</a>
                <a  id="forgot-password-link" onclick="">¿Olvidaste tu contraseña?</a>
            </p>

            <?php
            if (isset($_GET['error'])) {
                echo "<p class='error-message'>". htmlspecialchars($_GET['error']) ."</p>";
            }
            ?>
        </div>
    </div>

    <div id="recovery-menu" class="recovery-menu hidden">
    <h4>
        Recuperar Usuario
        <span class="close-btn" onclick="toggleRecoveryMenu()">×</span> 
    </h4>
    <form id="recoveryForm" action="recover_user.php" method="POST" onsubmit="searchUserByCURP(event)">
        <label for="curp">Ingresa tu CURP:</label>
        <div class="input-container">
            <input type="text" id="curp" name="curp" oninput="removeSpaces(this); validateCURP(this)" maxlength="18" required>
            <span id="curp-length" class="length-indicator">0/18</span>
            <div class="search-icon-container">
                <i class="fas fa-search" onclick="searchUserByCURP(event)" id="search-icon"></i>
            </div>
        </div>
        <button type="submit" class="btn">Buscar</button>
    </form>
</div>


    <script src="js/login.js"></script>
</body>
</html>
