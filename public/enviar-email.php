<?php
// Configuración de cabeceras para CORS y tipo de contenido
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// Solo procesar solicitudes POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

// Obtener los datos enviados como JSON
$json_data = file_get_contents('php://input');
$data = json_decode($json_data, true);

// Verificar que se recibieron los datos correctamente
if (!$data) {
    http_response_code(400); // Bad Request
    echo json_encode(['success' => false, 'message' => 'Datos no válidos']);
    exit;
}

// Validar campos requeridos
if (empty($data['nombre']) || empty($data['email']) || empty($data['telefono']) || empty($data['mensaje'])) {
    http_response_code(400); // Bad Request
    echo json_encode(['success' => false, 'message' => 'Todos los campos marcados con * son obligatorios']);
    exit;
}

// Sanitizar datos para prevenir inyección de código
$nombre = filter_var($data['nombre'], FILTER_SANITIZE_STRING);
$empresa = !empty($data['empresa']) ? filter_var($data['empresa'], FILTER_SANITIZE_STRING) : 'No especificada';
$email = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
$telefono = filter_var($data['telefono'], FILTER_SANITIZE_STRING);
$mensaje = filter_var($data['mensaje'], FILTER_SANITIZE_STRING);

// Validar email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400); // Bad Request
    echo json_encode(['success' => false, 'message' => 'El correo electrónico no es válido']);
    exit;
}

// Configurar destinatario y asunto
$destinatario = 'ingecaal.manager@outlook.com'; // Cambiar por el correo real de la empresa
$asunto = "Nuevo mensaje de contacto";

// Construir el cuerpo del mensaje
$cuerpo = "<html><body>";
$cuerpo .= "<h2>Nuevo mensaje de contacto</h2>";
$cuerpo .= "<p><strong>Nombre:</strong> $nombre</p>";
$cuerpo .= "<p><strong>Empresa:</strong> $empresa</p>";
$cuerpo .= "<p><strong>Email:</strong> $email</p>";
$cuerpo .= "<p><strong>Teléfono:</strong> $telefono</p>";
$cuerpo .= "<p><strong>Mensaje:</strong></p>";
$cuerpo .= "<p>$mensaje</p>";
$cuerpo .= "</body></html>";

// Cabeceras para enviar correo HTML
$cabeceras = "MIME-Version: 1.0\r\n";
$cabeceras .= "Content-type: text/html; charset=UTF-8\r\n";
$cabeceras .= "From: $email\r\n";
$cabeceras .= "Reply-To: $email\r\n";

// Intentar enviar el correo
$enviado = mail($destinatario, $asunto, $cuerpo, $cabeceras);

if ($enviado) {
    echo json_encode(['success' => true, 'message' => 'Email enviado correctamente']);
} else {
    http_response_code(500); // Internal Server Error
    echo json_encode(['success' => false, 'message' => 'Error al enviar el email']);
}