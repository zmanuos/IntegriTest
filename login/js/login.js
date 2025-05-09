function togglePasswordVisibility() {
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.getElementById('togglePassword');

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        toggleIcon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}

function validateNumbers(event) {
    const input = event.target;
    input.value = input.value.replace(/[^0-9]/g, ''); 
}

function toggleRecoveryMenu() {
    const menu = document.getElementById('recovery-menu');
    menu.classList.toggle('show');
    menu.classList.toggle('hidden');
}

function validateCURP(input) {
    const regex = /^[A-Za-z0-9]*$/; 
    const maxLength = 18;

    input.value = input.value.toUpperCase();

    if (input.value.length > maxLength) {
        input.value = input.value.slice(0, maxLength);
    }

    const errorElement = document.getElementById("curp-error");
    if (!regex.test(input.value) || input.value.length !== maxLength) {
        errorElement.textContent = "CURP inválida. Debe tener 18 caracteres y solo letras o números.";
    } else {
        errorElement.textContent = "";
    }

    document.getElementById("curp-length").textContent = `${input.value.length}/18`;
}

function removeSpaces(input) {
    input.value = input.value.replace(/\s+/g, ''); 
}


function convertToUppercase(event) {
    const curpInput = event.target;  
    curpInput.value = curpInput.value.toUpperCase();  
}

document.getElementById('curp').addEventListener('input', convertToUppercase);



function updateCURPLength() {
    const curpInput = document.getElementById('curp');
    const curpLength = curpInput.value.length;
    const lengthIndicator = document.getElementById('curp-length');

    lengthIndicator.textContent = `${curpLength}/18`;

    if (curpLength === 18) {
        lengthIndicator.style.color = 'green';
    } else {
        lengthIndicator.style.color = 'black';
    }
}

document.getElementById('curp').addEventListener('input', updateCURPLength);


async function searchUserByCURP(event) {
    event.preventDefault(); 
    const curp = document.getElementById('curp').value;

    if (curp.length !== 18) {
        showNotification('CURP inválido. Por favor verifica.', 'error');
        return;
    }

    try {
        const response = await fetch('recover_user.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `curp=${encodeURIComponent(curp)}`
        });

        if (response.ok) {
            const user = await response.text(); 

            if (user) {
                document.getElementById('identificador_usuario').value = user;
                toggleRecoveryMenu();
                showNotification('Usuario encontrado y cargado.', 'success');
            } else {
                showNotification('No se encontró un usuario con ese CURP.', 'error');
            }
        } else {
            showNotification('Hubo un error al conectar con el servidor.', 'error');
        }
    } catch (error) {
        showNotification('Hubo un error al conectar con el servidor.', 'error');
    }
}

function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.textContent = message;
    document.body.appendChild(notification);

    setTimeout(() => {
        notification.remove();
    }, 5000); 
}

document.querySelectorAll("input, select").forEach((input) => {
    input.addEventListener("blur", function () {
        const errorElement = document.getElementById(`${input.id}-error`);
        if (errorElement && errorElement.textContent === "") {
            errorElement.style.display = "none";
        } else {
            errorElement.style.display = "block"; 
        }
    });

    input.addEventListener("focus", function () {
        const errorElement = document.getElementById(`${input.id}-error`);
        if (errorElement) {
            errorElement.style.display = "none"; 
        }
    });
});

function toggleRecoveryMenu() {
    var recoveryMenu = document.getElementById('recovery-menu');
    recoveryMenu.classList.toggle('show');
    recoveryMenu.classList.toggle('hidden');
}



document.querySelector('.info-icon').addEventListener('mouseenter', function() {
    var tooltip = document.createElement('div');
    tooltip.classList.add('tooltip');
    tooltip.textContent = 'La contraseña es tu número de usuario. Al ingresar, podrás cambiarla.';
    this.appendChild(tooltip);
});

document.querySelector('.info-icon').addEventListener('mouseleave', function() {
    var tooltip = this.querySelector('.tooltip');
    if (tooltip) {
        tooltip.remove();
    }
});

document.querySelector('#forgot-password-link').addEventListener('mouseenter', function() {
    var tooltip = document.createElement('div');
    tooltip.classList.add('password-tooltip');  
    tooltip.textContent = 'Por favor, comuníquese con la dirección de la escuela para solicitar un cambio de contraseña.';
    this.appendChild(tooltip);
});

document.querySelector('#forgot-password-link').addEventListener('mouseleave', function() {
    var tooltip = this.querySelector('.tooltip');
    if (tooltip) {
        tooltip.remove();
    }
});


