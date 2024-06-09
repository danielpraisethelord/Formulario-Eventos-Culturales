<?php
$servername = "localhost"; // Cambia esto si tu base de datos está en otro servidor
$username = "root"; // Cambia esto por tu usuario de la base de datos
$password = ""; // Cambia esto por tu contraseña de la base de datos
$dbname = "ordinarioingsoft";
$port = 3307; // Puerto donde está corriendo MariaDB

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
} else {
    echo "Conexion exitosa";
}
?>