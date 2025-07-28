<?php
// Mostrar errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../../../../Persistencia/conexionBD.php';

// Conexión a la base de datos
$conn = ConexionBD::conectar();

// Obtener tipos de estudio
$tipos = $conn->query("SELECT id, nombre FROM tipos_estudio");

// Obtener sedes
$sedes = $conn->query("SELECT id, nombre FROM sedes");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Solicitar Estudio</title>
</head>
<body>
    <h1>Solicitar estudio</h1>
    <form action="buscarEstudios.php" method="POST">
        <label for="tipoEstudio">Tipo de estudio:</label>
        <select name="tipoEstudio" id="tipoEstudio" required>
            <option value="">Seleccione...</option>
            <?php while($row = $tipos->fetch_assoc()): ?>
                <option value="<?= $row['id'] ?>"><?= $row['nombre'] ?></option>
            <?php endwhile; ?>
        </select>

        <label for="sede">Sede:</label>
        <select name="sede" id="sede" required>
            <option value="">Seleccione...</option>
            <?php while($row = $sedes->fetch_assoc()): ?>
                <option value="<?= $row['id'] ?>"><?= $row['nombre'] ?></option>
            <?php endwhile; ?>
        </select>

        <button type="submit">Buscar estudios</button>
    </form>
</body>
</html>