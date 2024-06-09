<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ordinarioingsoft";
$port = 3307;

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Verificar conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Establecer el conjunto de caracteres
$conn->set_charset("utf8");

// Verificar si el usuario está logueado
if (!isset($_SESSION['id_usuario'])) {
    die("Error: Usuario no autenticado.");
}

// Obtener el ID del usuario logueado desde la sesión
$Usuarios_idUsuarios = $_SESSION['id_usuario'];

// Verificar si el usuario ya envió el formulario
$sql = "SELECT form_submitted FROM usuarios WHERE idUsuarios = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $Usuarios_idUsuarios);
$stmt->execute();
$stmt->bind_result($form_submitted);
$stmt->fetch();
$stmt->close();

if ($form_submitted) {
    echo "<script>alert('Error, ya has enviado el formulario.'); window.location.href='index.html';</script>";
    exit();
}

// Verificar si los datos han sido enviados
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar la existencia de las variables y sanitizarlas
    $nombre = isset($_POST['nombre']) ? $conn->real_escape_string(trim($_POST['nombre'])) : '';
    $fecha_nacimiento = isset($_POST['fecha_nacimiento']) ? $conn->real_escape_string(trim($_POST['fecha_nacimiento'])) : '';
    $genero = isset($_POST['genero']) ? $conn->real_escape_string(trim($_POST['genero'])) : '';
    $residencia = isset($_POST['residencia']) ? $conn->real_escape_string(trim($_POST['residencia'])) : '';
    $eventos = isset($_POST['eventos']) ? $_POST['eventos'] : [];
    $otros_especificar = isset($_POST['otros_especificar']) ? $conn->real_escape_string(trim($_POST['otros_especificar'])) : '';
    $experiencia = isset($_POST['experiencia']) ? $conn->real_escape_string(trim($_POST['experiencia'])) : '';
    $explicacion_experiencia = isset($_POST['explicacion_experiencia']) ? $conn->real_escape_string(trim($_POST['explicacion_experiencia'])) : '';
    $organizacion = isset($_POST['organizacion']) ? $conn->real_escape_string(trim($_POST['organizacion'])) : '';
    $aspectos_organizacion = isset($_POST['aspectos_organizacion']) ? $conn->real_escape_string(trim($_POST['aspectos_organizacion'])) : '';
    $importancia = isset($_POST['importancia']) ? $conn->real_escape_string(trim($_POST['importancia'])) : '';
    $elementos_culturales = isset($_POST['elementos_culturales']) ? $conn->real_escape_string(trim($_POST['elementos_culturales'])) : '';
    $impacto = isset($_POST['impacto']) ? $conn->real_escape_string(trim($_POST['impacto'])) : '';
    $beneficios_comunidad = isset($_POST['beneficios_comunidad']) ? $conn->real_escape_string(trim($_POST['beneficios_comunidad'])) : '';
    $accesibilidad = isset($_POST['accesibilidad']) ? $conn->real_escape_string(trim($_POST['accesibilidad'])) : '';
    $dificultades_acceso = isset($_POST['dificultades_acceso']) ? $conn->real_escape_string(trim($_POST['dificultades_acceso'])) : '';

    $recomendar_eventos = isset($_POST['recomendar_eventos']) ? $conn->real_escape_string(trim($_POST['recomendar_eventos'])) : '';
    $sugerencias_mejora = isset($_POST['sugerencias_mejora']) ? $conn->real_escape_string(trim($_POST['sugerencias_mejora'])) : '';
    $futuro = isset($_POST['futuro']) ? $conn->real_escape_string(trim($_POST['futuro'])) : '';
        // Convertir a los valores adecuados para el ENUM
        switch ($futuro) {
            case 'si':
                $futuro = 'Sí';
                break;
            case 'no':
                $futuro = 'No';
                break;
            case 'no_estoy_seguro':
                $futuro = 'No estoy seguro';
                break;
            default:
                $futuro = NULL; // O manejar el caso de valores no válidos
        }
    $evento_esperado = isset($_POST['evento_esperado']) ? $conn->real_escape_string(trim($_POST['evento_esperado'])) : '';

    // Validar que no estén vacíos
    if (!empty($nombre) && !empty($fecha_nacimiento) && !empty($genero) && !empty($residencia) && !empty($experiencia) && !empty($explicacion_experiencia) && !empty($organizacion) && !empty($aspectos_organizacion) && !empty($importancia) && !empty($elementos_culturales) && !empty($impacto) && !empty($beneficios_comunidad) && !empty($accesibilidad) && !empty($dificultades_acceso)) {
        // Preparar y bind para insertar el participante
        $stmt = $conn->prepare("INSERT INTO participante (nombre, fecha_nacimiento, genero, lugar_residencia, Usuarios_idUsuarios) VALUES (?, ?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("ssssi", $nombre, $fecha_nacimiento, $genero, $residencia, $Usuarios_idUsuarios);

            // Ejecutar
            if ($stmt->execute()) {
                $participante_id = $stmt->insert_id;

                // Insertar eventos seleccionados en participante_evento
                if (!empty($eventos)) {
                    $stmt_evento = $conn->prepare("INSERT INTO participante_evento (Participante_idParticipante, Evento_idEvento) VALUES (?, ?)");
                    foreach ($eventos as $evento) {
                        if ($evento != 'otros') {
                            $stmt_evento->bind_param("ii", $participante_id, $evento);
                            $stmt_evento->execute();
                        }
                    }
                    $stmt_evento->close();
                }

                // Insertar otros eventos si se especificaron
                if (!empty($otros_especificar)) {
                    $stmt_otros_evento = $conn->prepare("INSERT INTO evento (nombre_evento, otro_evento) VALUES ('Otros', ?)");
                    $stmt_otros_evento->bind_param("s", $otros_especificar);
                    $stmt_otros_evento->execute();
                    $otro_evento_id = $stmt_otros_evento->insert_id;
                    $stmt_otros_evento->close();

                    // Insertar la relación en participante_evento
                    $stmt_part_evento = $conn->prepare("INSERT INTO participante_evento (Participante_idParticipante, Evento_idEvento) VALUES (?, ?)");
                    $stmt_part_evento->bind_param("ii", $participante_id, $otro_evento_id);
                    $stmt_part_evento->execute();
                    $stmt_part_evento->close();
                }

                // Insertar la experiencia
                $stmt_experiencia = $conn->prepare("INSERT INTO experiencia (Participante_idParticipante, calificacion, explicacion) VALUES (?, ?, ?)");
                if ($stmt_experiencia) {
                    $stmt_experiencia->bind_param("iss", $participante_id, $experiencia, $explicacion_experiencia);
                    if ($stmt_experiencia->execute()) {
                        // Insertar la organización
                        $stmt_organizacion = $conn->prepare("INSERT INTO organizacion (Participante_idParticipante, calificacion, aspectos_destacables_problemas) VALUES (?, ?, ?)");
                        if ($stmt_organizacion) {
                            $stmt_organizacion->bind_param("iss", $participante_id, $organizacion, $aspectos_organizacion);
                            if ($stmt_organizacion->execute()) {
                                // Insertar los aspectos culturales
                                $stmt_culturales = $conn->prepare("INSERT INTO aspectos_culturales (Participante_idParticipante, importancia, elementos_disfrutados) VALUES (?, ?, ?)");
                                if ($stmt_culturales) {
                                    $stmt_culturales->bind_param("iss", $participante_id, $importancia, $elementos_culturales);
                                    if ($stmt_culturales->execute()) {
                                        // Insertar el impacto económico y social
                                        $stmt_impacto = $conn->prepare("INSERT INTO impacto_economico_social (Participante_idParticipante, impacto_positivo, beneficios_comunidad) VALUES (?, ?, ?)");
                                        if ($stmt_impacto) {
                                            $stmt_impacto->bind_param("iss", $participante_id, $impacto, $beneficios_comunidad);
                                            if ($stmt_impacto->execute()) {
                                                // Insertar accesibilidad y comodidad
                                                $stmt_accesibilidad = $conn->prepare("INSERT INTO accesibilidad_comodidad (Participante_idParticipante, calificacion, dificultades_acceso) VALUES (?, ?, ?)");
                                                if ($stmt_accesibilidad) {
                                                    $stmt_accesibilidad->bind_param("iss", $participante_id, $accesibilidad, $dificultades_acceso);
                                                    if ($stmt_accesibilidad->execute()) {
                                                        // Insertar recomendaciones y sugerencias
                                                        $stmt_recomendaciones = $conn->prepare("INSERT INTO recomendaciones_sugerencias (Participante_idParticipante, recomendar_evento, sugerir_mejora) VALUES (?, ?, ?)");
                                                        if ($stmt_recomendaciones) {
                                                            $stmt_recomendaciones->bind_param("iss", $participante_id, $recomendar_eventos, $sugerencias_mejora);
                                                            if ($stmt_recomendaciones->execute()) {
                                                                // Insertar participación en el futuro
                                                                $stmt_participacion = $conn->prepare("INSERT INTO participacion_futuro (Participante_idParticipante, asistir_futuro, evento_esperado) VALUES (?, ?, ?)");
                                                                if ($stmt_participacion) {
                                                                    $stmt_participacion->bind_param("iss", $participante_id, $futuro, $evento_esperado);
                                                                    if ($stmt_participacion->execute()) {
                                                                        // Marcar el formulario como enviado
                                                                        $sql = "UPDATE usuarios SET form_submitted = 1 WHERE idUsuarios = ?";
                                                                        $stmt = $conn->prepare($sql);
                                                                        $stmt->bind_param("i", $Usuarios_idUsuarios);
                                                                        $stmt->execute();
                                                                        $stmt->close();

                                                                        echo "<script>alert('Formulario enviado correctamente.'); window.location.href='index.html';</script>";
                                                                    } else {
                                                                        echo "Error al insertar participación en el futuro: " . $stmt_participacion->error;
                                                                    }
                                                                    $stmt_participacion->close();
                                                                } else {
                                                                    echo "Error en la preparación de la declaración de participación en el futuro: " . $conn->error;
                                                                }
                                                            } else {
                                                                echo "Error al insertar recomendaciones y sugerencias: " . $stmt_recomendaciones->error;
                                                            }
                                                            $stmt_recomendaciones->close();
                                                        } else {
                                                            echo "Error en la preparación de la declaración de recomendaciones y sugerencias: " . $conn->error;
                                                        }
                                                    } else {
                                                        echo "Error al insertar accesibilidad y comodidad: " . $stmt_accesibilidad->error;
                                                    }
                                                    $stmt_accesibilidad->close();
                                                } else {
                                                    echo "Error en la preparación de la declaración de accesibilidad y comodidad: " . $conn->error;
                                                }
                                            } else {
                                                echo "Error al insertar el impacto económico y social: " . $stmt_impacto->error;
                                            }
                                            $stmt_impacto->close();
                                        } else {
                                            echo "Error en la preparación de la declaración de impacto económico y social: " . $conn->error;
                                        }
                                    } else {
                                        echo "Error al insertar los aspectos culturales: " . $stmt_culturales->error;
                                    }
                                    $stmt_culturales->close();
                                } else {
                                    echo "Error en la preparación de la declaración de aspectos culturales: " . $conn->error;
                                }
                            } else {
                                echo "Error al insertar la organización: " . $stmt_organizacion->error;
                            }
                            $stmt_organizacion->close();
                        } else {
                            echo "Error en la preparación de la declaración de organización: " . $conn->error;
                        }
                    } else {
                        echo "Error al insertar la experiencia: " . $stmt_experiencia->error;
                    }
                    $stmt_experiencia->close();
                } else {
                    echo "Error en la preparación de la declaración de experiencia: " . $conn->error;
                }
            } else {
                echo "Error al insertar el participante: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Error en la preparación de la declaración: " . $conn->error;
        }
    } else {
        echo "Por favor, complete todos los campos.";
    }
}

$conn->close();
?>