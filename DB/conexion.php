<?php
$servername = "127.0.0.1";
$username = "root"; // Usuario de tu base de datos
$password = ""; // Contraseña de tu base de datos
$dbname = "examen_pr2";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Establecer el modo de error de PDO a excepción
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Conexión fallida: " . $e->getMessage();
}
?>
