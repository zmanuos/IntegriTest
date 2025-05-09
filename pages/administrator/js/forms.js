function previewImage(event) {
    let reader = new FileReader();
    reader.onload = function() {
        let preview = document.getElementById('preview');
        preview.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
}

// Función para alternar la visibilidad de la contraseña
function togglePasswordVisibility() {
    const passwordField = document.getElementById('password');
    const toggleIcon = document.getElementById('togglePassword');

    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        toggleIcon.classList.remove('fa-eye');
        toggleIcon.classList.add('fa-eye-slash');
    } else {
        passwordField.type = 'password';
        toggleIcon.classList.remove('fa-eye-slash');
        toggleIcon.classList.add('fa-eye');
    }
}

function showSuccessMessage() {
    var successMessageElement = document.createElement('div');
    successMessageElement.classList.add('success-message');
    successMessageElement.textContent = 'Docente creado exitosamente';

    // Añadir el mensaje a la parte superior de la página
    document.body.insertBefore(successMessageElement, document.body.firstChild);
    setTimeout(function() {
        successMessageElement.classList.add('hide');
        setTimeout(function() {
            successMessageElement.remove();
        }, 500); // Tiempo para que la animación de ocultar se complete
    }, 10000); // Después de 5 segundos ocultar el mensaje
    
}
