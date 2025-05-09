<?php
include 'header-logic.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Styles -->
    <link rel="stylesheet" href="../../css/header.css">
    <link rel="stylesheet" href="css/headerProfile.css">
    <link rel="stylesheet" href="../../css/menuThemes.css">
    <link rel="stylesheet" href="../../css/menu.css">
    <link rel="stylesheet" href="../../css/fontawesome/fontawesome.css">
    <link rel="stylesheet" href="../../css/fontawesome/solid.css">
    <link rel="stylesheet" href="../../css/styles.css">
    <!-- JavaScript -->
    <script src="data/menuConfig.js"></script>
    <script src="data/menu.js" defer></script>
    <script src="../../js/fontawesome/solid.js"></script>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <!-- Meta -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body onload="init()">
    <header>
        <div id="header-right">
            <div style="position: relative; display: inline-block;">
                <img src="<?php echo $image_url; ?>" alt="Imagen de perfil" id="profile-pic">
                <div id="profile-icon-container">
                    <i class="fas fa-chevron-down" id="profile-icon"></i>
                </div>
            </div>
            <div id="profile-popup" class="hidden">
            <div id="profile-popup-content" onclick="redirectToProfile()">
                    <img src="<?php echo $image_url; ?>" alt="Imagen de perfil" class="profile-pic-popup">
                    <div>
                        <p id="profile-name"><?php echo htmlspecialchars($nombre); ?></p>
                    </div>
                </div>
                <div id="profile-popup-separator"></div>
                <ul id="profile-popup-options">
                    <li>
                        <form action="../../login/logout.php" method="POST" id="logout-form">
                            <button type="submit" name="logout" class="logout-button">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>Cerrar sesión</span>
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
        <div id="header-logo">
            <a href="view_groups.php">
                <img src="../../images/header/logoOF.png" alt="Logo">
                <p>IntegriTest</p>
            </a>
        </div>
        <div id="header-menu">
            <i class="fas fa-bars" id="menu-toggle"></i>
        </div>
    </header>
    <div id="menu" class="hidden"></div>
    <script src="../../js/menu.js"></script>
    <script src="js/header.js"></script>
</body>
</html>
