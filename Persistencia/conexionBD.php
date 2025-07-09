<?php
class ConexionBD {
    private static $host = "localhost";
    private static $usuario = "root";       // O el usuario que uses
    private static $clave = "FMiN6Rx=IewO";             // Tu contraseña
    private static $bd = "GestionTurnos";

    public static function conectar() {
        $conn = new mysqli(self::$host, self::$usuario, self::$clave, self::$bd);
        if ($conn->connect_error) {
            die("Error de conexión: " . $conn->connect_error);
        }
        return $conn;
    }
}
?>
