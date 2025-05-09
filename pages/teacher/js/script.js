document.addEventListener('DOMContentLoaded', function() {

    function abrirPopup(btnSelector) {
        const botones = document.querySelectorAll(btnSelector);
        botones.forEach((btn) => {
            btn.addEventListener('click', (e) => {
                const popupId = e.target.getAttribute('data-popup-id');
                const popup = document.getElementById(popupId);

                if (popup) {
                    popup.style.display = 'flex';
                }
            });
        });
    }

    function cerrarPopup(cerrarBtnsSelector) {
        const botonesCerrar = document.querySelectorAll(cerrarBtnsSelector);
        botonesCerrar.forEach((btn) => {
            btn.addEventListener('click', (e) => {
                const popupId = e.target.getAttribute('data-popup-id');
                const popup = document.getElementById(popupId);

                if (popup) {
                    popup.style.display = 'none';
                }
            });
        });
    }

    function cerrarPopupFueraDelContenido(popupSelector) {
        const popups = document.querySelectorAll(popupSelector);
        popups.forEach((popup) => {
            popup.addEventListener('click', (event) => {
                if (event.target === popup) {
                    popup.style.display = 'none';
                }
            });
        });
    }

    abrirPopup('.fa-user-graduate', '.popup');
    cerrarPopup('.cerrarPopup');
    cerrarPopupFueraDelContenido('.popup');  

    abrirPopup('.fa-info-circle', '.popup2');  
    cerrarPopup('.cerrarPopup');  
    cerrarPopupFueraDelContenido('.popup2'); 
});
