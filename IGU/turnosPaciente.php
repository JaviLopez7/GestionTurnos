<?php include '../Logica/verificarSesion.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Gestión turnos</title>
</head>
<body>

<!-- Mensaje de inactividad (oculto inicialmente) -->
<div id="mensajeInactividad" style="
    display:none; 
    position: fixed; 
    top: 20px; 
    left: 50%; 
    transform: translateX(-50%);
    background: yellow; 
    padding: 10px 20px; 
    border: 2px solid orange; 
    z-index: 9999;
    font-weight: bold;
    font-family: Arial, sans-serif;
">
    ⚠️ Estás inactivo. Tu sesión se cerrará si no hacés nada en los próximos 10 segundos.
    <button id="btnContinuar" style="
        margin-left: 10px; 
        padding: 5px 10px;
        cursor: pointer;
    ">Continuar sesión</button>
</div>

<!-- Menú de navegación -->
<nav>
    <ul>
        <li><a href="index.html">Inicio</a></li>
        <li><a href="#">Mis Turnos</a></li>
        <li>
            <input type="text" placeholder="Buscar..."/>
            <button>Buscar</button>
        </li>
        <li><a href="../Logica/cerrarSesion.php">Cerrar Sesión</a></li>
    </ul>
</nav>

<!-- Contenido principal -->
<div>
    <h1>Bienvenido/a al Sistema de turnos</h1>
    <div>
        <a href="turnoMedico.php">Solicitar turno medico</a>
        <a href="turnoEstudio.php">Solicitar estudio</a>
        <button>Ver Mis Turnos</button>
        <button>Cancelar Turno</button>
    </div>

    <!-- Credencial virtual y QR -->
    <div>
        <div>Credencial Virtual:</div>
        <div>
            <img src="https://api.qrserver.com/v1/create-qr-code/?data=MiPortalMedico&size=120x120" alt="QR de credencial" />
        </div>
    </div>
</div>

<script src="js/controlInactividad.js"></script>

</body>
</html>
