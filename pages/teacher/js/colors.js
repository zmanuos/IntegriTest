document.addEventListener('DOMContentLoaded', () => {
    const divs = document.querySelectorAll('.card-header');

    const colores = window.colores || [];

    divs.forEach((div, index) => {
        const colorAleatorio = colores[index % colores.length]; 
        div.style.backgroundColor = colorAleatorio;
    });
});
