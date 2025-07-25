<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión - Gestión de turnos</title>
</head>
<body>
    <h1>INICIAR SESIÓN</h1>
    <form action="Logica/General/iniciarSesion.php" method="POST">
        <div>
            <label for="email">Correo Electrónico:</label>
            <input type="text" id="email" name="email" required>
        </div>
        
        <div>
            <label for="clave">Contraseña:</label>
            <input type="password" id="password" name="password" required>
        </div>
        
        <div>
            <button type="submit">INICIAR SESIÓN</button>
        </div>
        
        <div>
            <a href="interfaces/olvidasteContrasenia.html">¿Olvidaste tu contraseña?</a>
        </div>
        
        <div>
            ¿No tienes cuenta? <a href="interfaces/Paciente/registrarPaciente.php">Regístrate</a>
        </div>
    </form>
</body>
</html>