<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ordinarioingsoft";
$port = 3307;

$response = array();

try {
    $conn = new mysqli($servername, $username, $password, $dbname, $port);

    if ($conn->connect_error) {
        throw new Exception("Conexión fallida: " . $conn->connect_error);
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!isset($_POST['matricula']) || !isset($_POST['password'])) {
            throw new Exception("Parámetros faltantes");
        }

        $matricula = $_POST['matricula'];
        $password = $_POST['password'];

        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        $sql = "SELECT * FROM usuarios WHERE matricula = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $matricula);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $response['status'] = 'error';
            $response['message'] = 'Usuario ya registrado';
        } else {
            $sql = "INSERT INTO usuarios (matricula, contrasena, form_submitted) VALUES (?, ?, 0)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $matricula, $hashed_password);

            if ($stmt->execute() === TRUE) {
                $response['status'] = 'success';
                $response['message'] = 'Nuevo registro creado exitosamente';
            } else {
                throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
            }
        }

        $stmt->close();
    } else {
        throw new Exception("Método de solicitud no permitido");
    }

    $conn->close();
} catch (Exception $e) {
    $response['status'] = 'error';
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
?>
