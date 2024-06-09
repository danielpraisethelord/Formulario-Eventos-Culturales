<?php
session_start();

header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ordinarioingsoft";
$port = 3307;

$conn = new mysqli($servername, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    die(json_encode(['status' => 'error', 'message' => 'Conexión fallida: ' . $conn->connect_error]));
}

$matricula = trim($_POST['matricula']);
$password = trim($_POST['password']);

if (empty($matricula) || empty($password)) {
    echo json_encode(['status' => 'error', 'message' => 'Por favor, ingrese todos los datos requeridos']);
    exit();
}

$sql = "SELECT idUsuarios, contrasena FROM usuarios WHERE matricula = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $matricula);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($id_usuario, $hashed_password);

$response = array();

if ($stmt->num_rows > 0) {
    $stmt->fetch();
    if (password_verify($password, $hashed_password)) {
        $_SESSION['id_usuario'] = $id_usuario; // Guardar el ID del usuario en la sesión
        $response['status'] = 'success';
        $response['message'] = 'Inicio de sesión exitoso';
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Credenciales incorrectas';
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'Credenciales incorrectas';
}

$stmt->close();
$conn->close();

echo json_encode($response);
?>
