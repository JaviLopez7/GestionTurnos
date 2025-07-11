let tiempoInactividad = 30000; // 30 segundos sin actividad
let tiempoRespuesta = 10000;   // 10 segundos para responder

let temporizadorInactividad;
let temporizadorCierre;

function resetearTemporizador() {
    clearTimeout(temporizadorInactividad);
    clearTimeout(temporizadorCierre);
    ocultarMensajeInactividad();
    temporizadorInactividad = setTimeout(mostrarAlertaInactividad, tiempoInactividad);
}

function mostrarAlertaInactividad() {
    const mensaje = document.getElementById('mensajeInactividad');
    mensaje.style.display = 'block';

    temporizadorCierre = setTimeout(() => {
        cerrarSesion();
    }, tiempoRespuesta);

    document.getElementById('btnContinuar').onclick = () => {
        resetearTemporizador();
    }
}

function ocultarMensajeInactividad() {
    const mensaje = document.getElementById('mensajeInactividad');
    mensaje.style.display = 'none';
}

function cerrarSesion() {
    window.location.href = '../Logica/cerrarSesion.php';
}

// Solo click y teclas reinician temporizador
document.addEventListener('click', resetearTemporizador);
document.addEventListener('keydown', resetearTemporizador);

// Iniciar temporizador al cargar página
resetearTemporizador();
