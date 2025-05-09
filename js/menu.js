document.addEventListener('DOMContentLoaded', function() {
    const menu = document.getElementById('menu');
    const menuToggle = document.getElementById('menu-toggle');

    menuToggle.addEventListener('click', function() {
        // Toggle la clase visible y hidden
        if (menu.classList.contains('hidden')) {
            menu.classList.remove('hidden');
            setTimeout(() => menu.classList.add('visible')); // Permitir que el DOM se actualice antes de agregar 'visible'
        } else {
            menu.classList.remove('visible');
            setTimeout(() => menu.classList.add('hidden')); // Esperar la duración de la transición antes de ocultar
        }
    });
});
