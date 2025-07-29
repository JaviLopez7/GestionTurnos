<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require_once __DIR__ . '/../../librerias/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../../librerias/PHPMailer/src/SMTP.php';
require_once __DIR__ . '/../../librerias/PHPMailer/src/Exception.php';


function enviarNotificacionTurno($conn, $turnoId) {
    // Consultar datos del turno, paciente, estudio, etc.
    $sql = "
        SELECT 
            t.fecha, t.hora, t.copago, t.observaciones,
            e.nombre AS nombre_estudio,
            e.requiere_acompaniante, e.requiere_ayuno, e.requiere_orden_medica,
            e.instrucciones_preparacion,
            r.nombre AS nombre_recurso, r.tipo AS tipo_recurso,
            s.nombre AS nombre_sede, s.direccion AS direccion_sede,
            p.nombre AS paciente_nombre, p.apellido AS paciente_apellido, p.email AS paciente_email
        FROM turnos t
        JOIN estudios e ON t.estudio_id = e.id
        JOIN recursos r ON t.recurso_id = r.id
        JOIN sedes s ON r.sede_id = s.id
        JOIN pacientes p ON t.paciente_id = p.id
        WHERE t.id = ?
        LIMIT 1
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $turnoId);
    $stmt->execute();
    $turno = $stmt->get_result()->fetch_assoc();

    if (!$turno) return;

    $paciente_nombre = $turno['paciente_nombre'] . ' ' . $turno['paciente_apellido'];
    $paciente_email = $turno['paciente_email'];
    $fecha = $turno['fecha'];
    $hora = $turno['hora'];
    $estudio = $turno['nombre_estudio'];
    $copago = "$" . number_format($turno['copago'], 2);
    $direccion = $turno['direccion_sede'];
    $profesional = ucfirst($turno['tipo_recurso']) . ": " . $turno['nombre_recurso'];

    // Recomendaciones
    $recomendaciones = [];
    $recomendaciones[] = "Presentarse 15 minutos antes del horario.";
    if ($turno['requiere_ayuno']) $recomendaciones[] = "Debe concurrir en ayunas.";
    if ($turno['requiere_acompaniante']) $recomendaciones[] = "Debe asistir con un acompañante.";
    if ($turno['requiere_orden_medica']) $recomendaciones[] = "Debe traer la orden médica.";
    if (!empty($turno['instrucciones_preparacion'])) $recomendaciones[] = nl2br($turno['instrucciones_preparacion']);
    if (!empty($turno['observaciones'])) $recomendaciones[] = nl2br($turno['observaciones']);

    $mensajeHTML = "
        <p>Estimado/a <strong>$paciente_nombre</strong>,</p>
        <p>Su turno ha sido confirmado:</p>
        <ul>
            <li><strong>Estudio:</strong> $estudio</li>
            <li><strong>Fecha:</strong> $fecha</li>
            <li><strong>Hora:</strong> $hora</li>
            <li><strong>Recurso:</strong> $profesional</li>
            <li><strong>Dirección:</strong> $direccion</li>
            <li><strong>Copago:</strong> $copago</li>
        </ul>
        <p><strong>Recomendaciones:</strong></p>
        <p>" . implode("<br>", $recomendaciones) . "</p>
        <p>Gracias por confiar en nosotros.</p>
    ";

    try {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'TUCORREO@EXAMPLE.COM'; // Tu cuenta de Gmail
        $mail->Password   = 'GENERAR LA CONTRASEÑA'; // Clave generada
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        $mail->setFrom('TUCORREO@EXAMPLE.COM', 'Clínica Central');
        $mail->addAddress($paciente_email, $paciente_nombre);
        $mail->isHTML(true);
        $mail->Subject = "Confirmación de turno médico";
        $mail->Body = $mensajeHTML;

        $mail->send();
    } catch (Exception $e) {
        error_log("Error al enviar mail: " . $mail->ErrorInfo);
    }
}
