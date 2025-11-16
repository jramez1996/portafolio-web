<?php
header("Access-Control-Allow-Origin: *"); // permite peticiones desde cualquier dominio
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

// Conexión a la base de datos
$servername = "localhost";
$username = "root"; // tu usuario
$password = ""; // tu contraseña
$dbname = "bd_info"; // cambia por tu nombre de BD

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["error" => "Error de conexión a la base de datos."]));
}

// Obtener los datos enviados en formato JSON
$data = json_decode(file_get_contents("php://input"), true);

$name = $data["name"] ?? null;
$email = $data["email"] ?? null;
$company = $data["company"] ?? null;
$phone = $data["phone"] ?? null;
$message = $data["message"] ?? null;

// Validación básica
if (!$name || !$email || !$message) {
    echo json_encode(["error" => "Faltan campos obligatorios."]);
    exit;
}

// Preparar la consulta
$stmt = $conn->prepare("INSERT INTO contact (name, email, company, phone, message) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $name, $email, $company, $phone, $message);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Contacto registrado correctamente."]);
} else {
    echo json_encode(["error" => "Error al registrar el contacto."]);
}

$stmt->close();
$conn->close();
?>

