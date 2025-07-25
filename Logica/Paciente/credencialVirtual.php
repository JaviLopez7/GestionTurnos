<?php

// Para depurar los parámetros GET
var_dump($_GET);

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Evitar iniciar sesión más de una vez
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Incluir archivos necesarios
require_once('../../Persistencia/conexionBD.php');
require_once('../../librerias/fpdf/fpdf.php');
require_once('../../librerias/phpqrcode/qrlib.php');

// Conexión a la base de datos
$conn = ConexionBD::conectar();

// Función para obtener los datos del paciente por ID
function obtenerDatosPaciente($conn, $id_paciente) {
    $stmt = $conn->prepare("SELECT p.nombre, p.apellido, p.numero_afiliado, a.tipo_beneficiario, a.seccional, a.estado 
                            FROM pacientes p
                            JOIN afiliados a ON p.id_afiliado = a.id 
                            WHERE p.id = ?");
    if (!$stmt) {
        throw new Exception("Error en la consulta de datos: " . $conn->error);
    }

    $stmt->bind_param("i", $id_paciente);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows !== 1) {
        $stmt->close();
        return null;
    }

    $stmt->bind_result($nombre, $apellido, $numero_afiliado, $tipo_beneficiario, $seccional, $estado);
    $stmt->fetch();
    $stmt->close();

    return compact('nombre', 'apellido', 'numero_afiliado', 'tipo_beneficiario', 'seccional', 'estado');
}

// Función para obtener los datos del paciente por token
function obtenerDatosPacientePorToken($conn, $token) {
    $stmt = $conn->prepare("SELECT p.nombre, p.apellido, p.numero_afiliado, a.tipo_beneficiario, a.seccional, a.estado 
                            FROM pacientes p
                            JOIN afiliados a ON p.id_afiliado = a.id 
                            WHERE p.token_qr = ?"); // Usamos el campo token_qr aquí
    if (!$stmt) {
        throw new Exception("Error en la consulta de datos: " . $conn->error);
    }

    $stmt->bind_param("s", $token); // Usamos 's' para pasar el token como string
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows !== 1) {
        $stmt->close();
        return null; // Si no se encuentra el token, retornamos null
    }

    $stmt->bind_result($nombre, $apellido, $numero_afiliado, $tipo_beneficiario, $seccional, $estado);
    $stmt->fetch();
    $stmt->close();

    return compact('nombre', 'apellido', 'numero_afiliado', 'tipo_beneficiario', 'seccional', 'estado');
}

// Función para mostrar los datos del paciente
function mostrarDatosPaciente($datos) {
    echo "<p><strong>Nombre:</strong> {$datos['nombre']}</p>";
    echo "<p><strong>Apellido:</strong> {$datos['apellido']}</p>";
    echo "<p><strong>Número de Afiliado:</strong> {$datos['numero_afiliado']}</p>";
    echo "<p><strong>Tipo de Beneficiario:</strong> {$datos['tipo_beneficiario']}</p>";
    echo "<p><strong>Seccional:</strong> {$datos['seccional']}</p>";
    echo "<p><strong>Estado:</strong> {$datos['estado']}</p>";
}

function descargarCredencial($conn, $token) {
    // Mostrar que hemos entrado en la función
    echo "Entrando en la función descargarCredencial<br>";

    // Obtener los datos del paciente
    $datos = obtenerDatosPacientePorToken($conn, $token);
    if (!$datos) {
        die("❌ No se encontraron los datos del paciente.");
    }

    echo "Datos del paciente obtenidos: " . print_r($datos, true) . "<br>";

    // Crear el PDF
    require_once('../../librerias/fpdf/fpdf.php'); // Incluir FPDF si no está incluido ya
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, 'Credencial Virtual del Paciente', 0, 1, 'C');
    $pdf->Ln(5);
    $pdf->SetFont('Arial', '', 12);

    // Añadir datos del paciente al PDF
    $pdf->Cell(0, 10, "Nombre: {$datos['nombre']}", 0, 1);
    $pdf->Cell(0, 10, "Apellido: {$datos['apellido']}", 0, 1);
    $pdf->Cell(0, 10, "Número de Afiliado: {$datos['numero_afiliado']}", 0, 1);
    $pdf->Cell(0, 10, "Tipo de Beneficiario: {$datos['tipo_beneficiario']}", 0, 1);
    $pdf->Cell(0, 10, "Seccional: {$datos['seccional']}", 0, 1);
    $pdf->Cell(0, 10, "Estado: {$datos['estado']}", 0, 1);
    $pdf->Ln(10);

    // Generar el QR para el PDF
    $qr_data = "http://192.168.1.7/interfaces/Paciente/verCredencial.php?token=$token";
    $qr_temp_path = sys_get_temp_dir() . "/qr_$token.png";
    
    // Verificar si se genera correctamente el archivo QR
    echo "Generando QR en: $qr_temp_path<br>";
    QRcode::png($qr_data, $qr_temp_path, QR_ECLEVEL_L, 3);
    
    // Comprobar si el archivo QR fue generado
    if (!file_exists($qr_temp_path)) {
        die("❌ El archivo QR no se pudo generar.");
    }

    // Añadir el QR al PDF
    $pdf->Image($qr_temp_path, 80, $pdf->GetY(), 50);
    unlink($qr_temp_path); // Limpiar el archivo temporal

    // Salida del PDF
    $pdf->Output('D', 'credencial_virtual.pdf');
    exit; // Detener el script
}

?>
