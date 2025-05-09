// Espera a que el DOM esté completamente cargado
document.addEventListener("DOMContentLoaded", () => {
    const mainContainer = document.querySelector("#main-container"); // Asegúrate de que este selector sea correcto
    if (sessionStorage.getItem("showAnimation") === "true") {
        sessionStorage.removeItem("showAnimation");  // Elimina el valor después de usarlo
        triggerFireworks();  // Dispara la animación de fuegos artificiales
    }
});

// Función que genera los fuegos artificiales
function triggerFireworks() {
    const container = document.getElementById("fireworks-container");
    for (let i = 0; i < 30; i++) { // Cambia el número de fuegos si es necesario
        createFirework(container);
    }
}

// Crea un fuego artificial con color, tamaño y movimiento aleatorio
function createFirework(container) {
    const firework = document.createElement("div");
    firework.classList.add("firework");

    // Asignamos un color aleatorio para cada fuego artificial
    firework.style.setProperty('--firework-color', randomColor());

    // Generamos una rotación aleatoria para que cada fuego explote en una dirección distinta
    const randomRotation = Math.random() * 360; // Rango de 0 a 360 grados
    firework.style.setProperty('--random-rotation', `${randomRotation}deg`);

    // Genera posiciones aleatorias dentro de la pantalla (en vw/vh)
    const randomX = Math.random() * 100; // Posición aleatoria en el eje X (de 0% a 100%)
    const randomY = Math.random() * 100; // Posición aleatoria en el eje Y (de 0% a 100%)
    
    firework.style.left = `${randomX}vw`;  // Posición horizontal aleatoria
    firework.style.top = `${randomY}vh`;   // Posición vertical aleatoria

    // Tamaño aleatorio
    firework.style.width = `${Math.random() * 3 + 2}px`; 
    firework.style.height = `${Math.random() * 100 + 30}px`;  // La longitud de las líneas

    container.appendChild(firework);

    // Elimina el fuego artificial después de la animación
    setTimeout(() => {
        firework.remove();
    }, 5000); // 2 segundos de duración de la animación
}

// Función para generar un color aleatorio de una lista predefinida
function randomColor() {
    const colors = ['#FF5733', '#FFBD33', '#75FF33', '#33FF57', '#3357FF', '#5733FF', '#FF33A8'];
    return colors[Math.floor(Math.random() * colors.length)];
}
