// Validar texto (nombres, apellidos)
function validateText(input, errorId) {
    const regex = /^[A-Za-zÑñÁÉÍÓÚáéíóú\s]*$/; // Letras, espacios, ñ y acentos (sin números ni caracteres especiales)
    const maxLength = input.maxLength;

    if (input.value.length > maxLength) {
        input.value = input.value.slice(0, maxLength); // Truncar texto
    }

    const errorElement = document.getElementById(errorId);
    if (!regex.test(input.value)) {
        errorElement.textContent = "Solo se permiten letras, espacios y acentos.";
    } else {
        errorElement.textContent = "";
    }
}

// Validar fecha de nacimiento
function validateDate(input) {
    const selectedDate = new Date(input.value);
    const currentDate = new Date();
    const minDate = new Date(); // Fecha máxima permitida (hoy menos 100 años)
    minDate.setFullYear(currentDate.getFullYear() - 100);

    const errorElement = document.getElementById("fechaNacimiento-error");

    if (selectedDate > currentDate || selectedDate < minDate) {
        errorElement.textContent = "Fecha inválida.";
    } else {
        errorElement.textContent = "";
    }
}

// Validar número de teléfono
function validatePhone(input) {
    const maxLength = 10;
    input.value = input.value.replace(/\D/g, ""); // Solo números

    if (input.value.length > maxLength) {
        input.value = input.value.slice(0, maxLength);
    }

    const errorElement = document.getElementById("telefono-error");
    if (input.value.length !== maxLength && input.value !== "") {
        errorElement.textContent = "El teléfono debe tener 10 dígitos.";
    } else {
        errorElement.textContent = "";
    }

    // Mostrar indicador de longitud
    document.getElementById("telefono-length").textContent = `${input.value.length}/10`;
}

// Validar CURP
function validateCURP(input) {
    const regex = /^[A-Za-z0-9]*$/; // Solo letras y números
    const maxLength = 18;

    input.value = input.value.toUpperCase(); // Convertir a mayúsculas automáticamente

    if (input.value.length > maxLength) {
        input.value = input.value.slice(0, maxLength);
    }

    const errorElement = document.getElementById("curp-error");
    if (!regex.test(input.value) || input.value.length !== maxLength) {
        errorElement.textContent = "CURP inválida. Debe tener 18 caracteres y solo letras o números.";
    } else {
        errorElement.textContent = "";
    }

    // Mostrar indicador de longitud
    document.getElementById("curp-length").textContent = `${input.value.length}/18`;
}

// Evitar ingreso de espacios
function removeSpaces(input) {
    input.value = input.value.replace(/\s+/g, ''); // Reemplaza todos los espacios por nada
}


// Validar tamaño y tipo de archivo de foto
function validatePhoto(event) {
    const file = event.target.files[0];
    const errorElement = document.getElementById("photo-error");
    const preview = document.getElementById("preview");

    if (!file) {
        errorElement.textContent = "Debe seleccionar una foto.";
        return;
    }

    if (!["image/png", "image/jpeg"].includes(file.type)) {
        errorElement.textContent = "Solo se permiten imágenes PNG o JPG.";
        event.target.value = ""; // Limpiar el archivo
        preview.src = "../../images/default/default.png"; // Restaurar imagen por defecto
        return;
    }

    if (file.size > 5 * 1024 * 1024) {
        errorElement.textContent = "El archivo no debe superar los 5MB.";
        event.target.value = ""; // Limpiar el archivo
        preview.src = ""; // Restaurar imagen por defecto
        return;
    }

    errorElement.textContent = ""; // Limpia errores
    const reader = new FileReader();
    reader.onload = function (e) {
        preview.src = e.target.result;
    };
    reader.readAsDataURL(file);
}

// Validar formulario antes de enviar
function validateForm(event) {
    event.preventDefault(); // Evitar envío del formulario si hay errores

    let isValid = true;
    const fields = document.querySelectorAll("input[required], select[required]");

    fields.forEach((field) => {
        const errorElement = document.getElementById(`${field.id}-error`);
        if (field.value.trim() === "" || errorElement.textContent !== "") {
            errorElement.textContent = "Este campo es obligatorio o contiene errores.";
            errorElement.style.display = "block";
            isValid = false;
        }
    });

    if (isValid) {
        alert("Formulario enviado correctamente.");
        // Aquí puedes agregar el código para enviar el formulario
    } else {
        alert("Corrige los errores antes de enviar el formulario.");
    }
}

// Manejo de mensajes de error al salir del campo
document.querySelectorAll("input, select").forEach((input) => {
    input.addEventListener("blur", function () {
        const errorElement = document.getElementById(`${input.id}-error`);
        if (errorElement && errorElement.textContent === "") {
            errorElement.style.display = "none"; // Ocultar mensaje si no hay error
        } else {
            errorElement.style.display = "block"; // Mostrar mensaje solo si hay error
        }
    });

    input.addEventListener("focus", function () {
        const errorElement = document.getElementById(`${input.id}-error`);
        if (errorElement) {
            errorElement.style.display = "none"; // Ocultar mensaje al enfocar
        }
    });
});

// Asociar validación al formulario
document.getElementById("teacherForm").addEventListener("submit", validateForm);
document.getElementById("studentForm").addEventListener("submit", validateForm);

