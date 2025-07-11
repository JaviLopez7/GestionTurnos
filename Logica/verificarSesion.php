<?php
session_start();

// Tiempo máximo de inactividad (en segundos)
$inactividadMaxima = 30; // ⚠️ Solo 30 segundos: ideal para pruebas

// Si hay un tiempo de último acceso registrado
if (isset($_SESSION['ultimo_acceso'])) {
    $inactividad = time() - $_SESSION['ultimo_acceso'];
    
    if ($inactividad > $inactividadMaxima) {
        // Si se excede el tiempo de inactividad, cerrar sesión
        session_unset();
        session_destroy();
        header('Location: ../index.php?mensaje=inactividad'); // Podés capturar esto con JS o PHP
        exit;
    }
}

// Se actualiza el último acceso en cada carga de página
$_SESSION['ultimo_acceso'] = time();

// Si no hay sesión activa, redirigir al login
if (!isset($_SESSION['paciente_id'])) {
    header('Location: ../index.php');
    exit;
}
?>
