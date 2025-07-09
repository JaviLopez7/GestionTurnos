<?php
require_once '../Persistencia/conexionBD.php'; // Ajustá la ruta según tu estructura
$conn = ConexionBD::conectar();
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $tipo_documento = $_POST['tipo_documento'];
    $numero_documento = trim($_POST['numero_documento']);
    $genero = $_POST['genero'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $domicilio = trim($_POST['domicilio']);
    $numero_contacto = trim($_POST['numero_contacto']);
    $cobertura_salud = $_POST['cobertura_salud'];
    $numero_afiliado = trim($_POST['numero_afiliado']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    if (isset($_FILES['imagen_dni']) && is_uploaded_file($_FILES['imagen_dni']['tmp_name'])) {
        $contenido_imagen = file_get_contents($_FILES['imagen_dni']['tmp_name']);
        $imagen_base64 = base64_encode($contenido_imagen);
    } else {
        echo "<script>alert('Error: no se subió correctamente la imagen del DNI.'); window.history.back();</script>";
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO pacientes 
        (nombre, apellido, tipo_documento, numero_documento, img_dni, genero, fecha_nacimiento, domicilio, numero_contacto, cobertura_salud, numero_afiliado, email, password_hash) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param(
        "sssssssssssss",
        $nombre,
        $apellido,
        $tipo_documento,
        $numero_documento,
        $imagen_base64,
        $genero,
        $fecha_nacimiento,
        $domicilio,
        $numero_contacto,
        $cobertura_salud,
        $numero_afiliado,
        $email,
        $password_hash
    );

    try {
    $stmt->execute();
    echo "<script>alert('✅ Registro exitoso.'); window.location.href = '../index.php';</script>";
} catch (mysqli_sql_exception $e) {
    $errorMsg = $e->getMessage();

    if (strpos($errorMsg, 'numero_documento') !== false) {
        echo "<script>alert('❌ Error: el número de documento ya está registrado.'); window.history.back();</script>";
    } elseif (strpos($errorMsg, 'email') !== false) {
        echo "<script>alert('❌ Error: el correo electrónico ya está registrado.'); window.history.back();</script>";
    } else {
        echo "<script>alert('❌ Error al registrar: " . addslashes($errorMsg) . "'); window.history.back();</script>";
    }
}

$stmt->close();
$conn->close();
exit;
}
?>
