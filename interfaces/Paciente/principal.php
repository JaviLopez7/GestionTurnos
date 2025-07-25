<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Gestión turnos</title>
</head>
<body>


<!-- Menú de navegación -->
<nav>
    <ul>
        <li><a href="../../index.php">Inicio</a></li>
        <li><a href="#">Mis Turnos</a></li>
        <li><a href="verCredencial.php">Ver credencial</a></li>
        <li>
            <input type="text" placeholder="Buscar..."/>
            <button>Buscar</button>
        </li>
        <li><a href="../../Logica/General/cerrarSesion.php">Cerrar Sesión</a></li>
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

    
</div>

<script src="js/controlInactividad.js"></script>

</body>
</html>
