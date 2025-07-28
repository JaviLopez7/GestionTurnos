<?php
// Mostrar errores (solo para desarrollo)
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once '../../../Persistencia/conexionBD.php';

$conn = ConexionBD::conectar();

// Validar sesión
$paciente_id = $_SESSION['paciente_id'] ?? null;
if (!$paciente_id) {
    die("Debe iniciar sesión para ver sus turnos.");
}

// Consulta de turnos del paciente
$sql = "
    SELECT 
        t.id,
        t.fecha,
        t.hora,
        t.estado,
        e.nombre AS nombre_estudio,
        s.direccion AS sede
    FROM turnos t
    JOIN estudios e ON t.estudio_id = e.id
    JOIN recursos r ON t.recurso_id = r.id
    JOIN sedes s ON r.sede_id = s.id
    WHERE t.paciente_id = ?
    ORDER BY t.fecha DESC, t.hora DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $paciente_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Turnos</title>
</head>
<body>
    <h1>Mis Turnos</h1>

    <?php if ($result->num_rows === 0): ?>
        <p>No tenés turnos registrados.</p>
    <?php else: ?>
        <table border="1" cellpadding="5">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Estudio</th>
                    <th>Sede</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($turno = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($turno['fecha']); ?></td>
                        <td><?php echo htmlspecialchars($turno['hora']); ?></td>
                        <td><?php echo htmlspecialchars($turno['nombre_estudio']); ?></td>
                        <td><?php echo htmlspecialchars($turno['sede']); ?></td>
                        <td><?php echo htmlspecialchars($turno['estado']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php endif; ?>

</body>
</html>
