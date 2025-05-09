// Mostrar la notificación si es necesario
<?php if ($showNotification): ?>
    document.getElementById("notification").style.display = "block";
    setTimeout(function() {
        document.getElementById("notification").style.display = "none";
    }, 3000); // Ocultar después de 3 segundos
<?php endif; ?>

// Llenar el formulario de edición con los datos del curso
document.querySelectorAll('.edit-btn').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        const id = this.getAttribute('data-id');
        const nombre = this.getAttribute('data-nombre');
        const descripcion = this.getAttribute('data-descripcion');
        const duracion = this.getAttribute('data-duracion');
        const estado = this.getAttribute('data-estado');

        document.getElementById('editCourseId').value = id;
        document.getElementById('editNombre').value = nombre;
        document.getElementById('editDescripcion').value = descripcion;
        document.getElementById('editDuracion').value = duracion;
        document.getElementById('editEstado').value = estado;

        document.getElementById('editModal').style.display = 'block';
    });
});


// Cerrar el modal de edición
document.querySelector('.close').addEventListener('click', function() {
    document.getElementById('editModal').style.display = 'none';
});

window.onload = function() {
// Limpiar los campos de búsqueda después de la carga
document.querySelector('input[name="searchId"]').value = '';
document.querySelector('input[name="searchName"]').value = '';
};

function searchCourses() {
const searchId = document.querySelector('input[name="searchId"]').value;
const searchName = document.querySelector('input[name="searchName"]').value;
const page = 1; // Página actual (puedes adaptarlo para paginación si es necesario)

// Crear la solicitud AJAX
const xhr = new XMLHttpRequest();
xhr.open('GET', `?searchId=${searchId}&searchName=${searchName}&page=${page}`, true);

xhr.onload = function() {
if (xhr.status === 200) {
    const response = xhr.responseText;
    // Actualizar la tabla de cursos con los datos obtenidos
    document.getElementById('coursesTable').innerHTML = response;
}
};

xhr.send();
}

// Evento para realizar la búsqueda al escribir
document.querySelector('form').addEventListener('submit', function(e) {
e.preventDefault(); // Evitar que el formulario se envíe y recargue la página
searchCourses(); // Llamar a la función para buscar los cursos
});


