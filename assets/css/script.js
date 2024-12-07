// Función para validar el formulario de inicio de sesión
function validarFormulario() {
    const username = document.getElementById('nombre_usuario').value;
    const password = document.getElementById('password').value;
    let valid = true;

    // Validación de usuario
    if (username === '') {
        mostrarError('nombre_usuario', 'El nombre de usuario es requerido.');
        valid = false;
    } else {
        limpiarError('nombre_usuario');
    }

    // Validación de contraseña
    if (password === '') {
        mostrarError('password', 'La contraseña es requerida.');
        valid = false;
    } else {
        limpiarError('password');
    }

    return valid;
}

// Mostrar error visual en los campos
function mostrarError(campoId, mensaje) {
    const campo = document.getElementById(campoId);
    const error = document.createElement('div');
    error.classList.add('error');
    error.textContent = mensaje;
    campo.parentNode.appendChild(error);
    campo.classList.add('input-error');
}

// Limpiar los errores visuales
function limpiarError(campoId) {
    const campo = document.getElementById(campoId);
    const errores = campo.parentNode.querySelectorAll('.error');
    errores.forEach(error => error.remove());
    campo.classList.remove('input-error');
}

// Agregar evento de envío de formulario
document.querySelector('form').addEventListener('submit', function (e) {
    if (!validarFormulario()) {
        e.preventDefault(); // Evitar el envío del formulario si hay errores
    }
});

// Animación de bienvenida cuando se carga la página
window.addEventListener('load', function () {
    const header = document.querySelector('header h1');
    header.style.opacity = 0;
    header.style.transform = 'translateY(-30px)';
    
    setTimeout(() => {
        header.style.transition = 'all 0.8s ease-out';
        header.style.opacity = 1;
        header.style.transform = 'translateY(0)';
    }, 300);
});

// Efecto de cambio de color de los botones al hacer hover
document.querySelectorAll('button').forEach(button => {
    button.addEventListener('mouseenter', () => {
        button.style.transform = 'scale(1.05)';
        button.style.transition = 'transform 0.3s ease-in-out';
    });

    button.addEventListener('mouseleave', () => {
        button.style.transform = 'scale(1)';
    });
});

// Función para ocultar y mostrar el menú de navegación en dispositivos móviles
document.getElementById('menu-toggle').addEventListener('click', function () {
    const nav = document.querySelector('header nav');
    nav.classList.toggle('nav-visible');
});

// Función para suavizar el scroll hasta las secciones
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        document.querySelector(this.getAttribute('href')).scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    });
});
