<?php
include('header.php');
include('../../includes/authentication.php');

checkAuthentication('administrador');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/form_user_teacher.css">
    <link rel="stylesheet" href="css/modalForms.css">

    <link rel="icon" href="../../images/favicon/favicon.ico" type="image/x-icon">
    <title>Registro de Docente</title>
</head>
<body>
    <div class="outer-container">
        <div class="container">
            <h2><i id="icon-teacher" class="fas fa-chalkboard-teacher"></i> Registro de Docente</h2>

            <?php
            if (isset($_GET['success_message'])) {
                echo "<p class='success-message'>" . htmlspecialchars($_GET['success_message']) . "</p>";
            }
            if (isset($_GET['error_message'])) {
                echo "<p class='error-message'>" . htmlspecialchars($_GET['error_message']) . "</p>";
            }
            ?>

            <form id="teacherForm" action="register_teacher_user.php" method="POST" enctype="multipart/form-data">

            <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" required oninput="validateText(this, 'nombre-error')" maxlength="50">
                <div id="nombre-error" class="error-message"></div>

                <label for="apellidoP">Apellido Paterno:</label>
                <input type="text" id="apellidoP" name="apellidoP" required oninput="validateText(this, 'apellidoP-error')" maxlength="50">
                <div id="apellidoP-error" class="error-message"></div>

                <label for="apellidoM">Apellido Materno:</label>
                <input type="text" id="apellidoM" name="apellidoM" oninput="validateText(this, 'apellidoM-error')" maxlength="50">
                <div id="apellidoM-error" class="error-message"></div>

                <label for="genero">Género:</label>
                <select id="genero" name="genero" required>
                    <option value="M">Hombre</option>
                    <option value="F">Mujer</option>
                </select>

                <label for="fechaNacimiento">Fecha de nacimiento:</label>
                <input type="date" id="fechaNacimiento" name="fechaNacimiento" required onchange="validateDate(this)">
                <div id="fechaNacimiento-error" class="error-message"></div>

                <label for="telefono">Teléfono:</label>
                <div class="input-container">
                    <input type="text" id="telefono" name="telefono" required maxlength="10" oninput="validatePhone(this)">
                    <span id="telefono-length" class="length-indicator">0/10</span>
                </div>
                <div id="telefono-error" class="error-message"></div>

                <label for="curp">CURP:</label>
                <div class="input-container">
                <input type="text" id="curp" name="curp" oninput="removeSpaces(this); validateCURP(this)" maxlength="18" required>
                <span id="curp-length" class="length-indicator">0/18</span>
                </div>
                <div id="curp-error" class="error-message"></div>

                <label for="foto">Foto:</label>
                <div class="photo-upload" onclick="document.getElementById('foto').click();">
                    <img id="preview" src="" />
                    <p>Click para añadir una foto</p>
                    <span class="file-hint"></span>
                </div>
                <input type="file" id="foto" name="foto" accept="image/png, image/jpeg, image/jpg" style="display: none;" onchange="validatePhoto(event)">
                <div id="photo-error" class="error-message"></div>

                <button class='button' type="submit">Registrar</button>
            </form>

            <div id="modal" class="modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <img id="modal-logo" src="../../images/header/logoOF.png" alt="Logo">IntegriTest
                    </div>
                    <div class="modal-left">
                        <img id="modal-foto" src="" alt="Foto de Usuario" />
                    </div>
                    <div class="modal-right">
                        <span class="close" onclick="closeModal()">&times;</span>
                        <h3>Detalles del Docente registrado</h3>
                        <ul>
                            <li><strong>Numero de empleado:</strong> <span id="modal-numEmpleado"></span></li>
                            <li><strong>Nombre:</strong> <span id="modal-nombre"></span></li>
                            <li><strong>Apellido Paterno:</strong> <span id="modal-apellidoP"></span></li>
                            <li><strong>Apellido Materno:</strong> <span id="modal-apellidoM"></span></li>
                            <li><strong>Fecha de Nacimiento:</strong> <span id="modal-fechaNacimiento"></span></li>
                            <li><strong>Curp:</strong> <span id="modal-curp"></span></li>
                            <li><strong>Género:</strong> <span id="modal-genero"></span></li>
                            <li><strong>Teléfono:</strong> <span id="modal-telefono"></span></li>
                            <li><strong>Correo:</strong> <span id="modal-correo"></span></li>
                        </ul>
                    </div>
                </div>
            </div>

            <script src="js/forms.js"></script>
            <script src="js/validations.js"></script>
            <script>
                function openModal() {
                    document.getElementById('modal').style.display = 'block';
                }

                function closeModal() {
                    document.getElementById('modal').style.display = 'none';
                    location.reload();
                }

                function showSuccessMessage() {
                    var successMessageElement = document.createElement('div');
                    successMessageElement.classList.add('success-message');
                    successMessageElement.textContent = 'Docente registrado exitosamente';

                    document.body.appendChild(successMessageElement);

                    setTimeout(function() {
                        successMessageElement.remove();
                    }, 2500);
                }

                document.getElementById('teacherForm').addEventListener('submit', function (e) {
                    e.preventDefault();

                    var formData = new FormData(this);
                    var xhr = new XMLHttpRequest();
                    xhr.open("POST", "register_teacher_user.php", true);

                    xhr.onload = function () {
                        if (xhr.status == 200) {
                            var response = JSON.parse(xhr.responseText);

                            if (response.success) {
                                showSuccessMessage();

                                document.getElementById('modal-numEmpleado').textContent = response.numEmpleado;
                                document.getElementById('modal-nombre').textContent = response.nombre;
                                document.getElementById('modal-apellidoP').textContent = response.apellidoP;
                                document.getElementById('modal-apellidoM').textContent = response.apellidoM || 'No especificado';
                                document.getElementById('modal-fechaNacimiento').textContent = response.fechaNacimiento;
                                document.getElementById('modal-genero').textContent = response.genero;
                                document.getElementById('modal-foto').src = '../../images/users/' + response.foto;
                                document.getElementById('modal-telefono').textContent = response.telefono;
                                document.getElementById('modal-curp').textContent = response.curp;
                                document.getElementById('modal-correo').textContent = response.correo;

                                openModal();
                            } else {
                                alert('Error: ' + response.error);
                            }
                        } else {
                            alert('Error en la solicitud.');
                        }
                    };
                    xhr.send(formData);
                });
            </script>

        </div>
    </div>
</body>
</html>