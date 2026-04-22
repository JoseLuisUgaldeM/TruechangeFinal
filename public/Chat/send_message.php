<?php



require 'db_config.php';
header('Content-Type: application/json');

// El sender_id se obtiene de la constante definida en db_config.php
$sender_id = CURRENT_USER_ID; 

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message_text'], $_POST['receiver_id'])) {
    
    $receiver_id = filter_var($_POST['receiver_id'], FILTER_VALIDATE_INT);
    $message_text = trim($_POST['message_text']);

    // Validación básica
    if (!$sender_id || !$receiver_id || empty($message_text)) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Datos inválidos o faltantes.']);
        exit;
    }

    try {
        $sql = "INSERT INTO messages (sender_id, receiver_id, message_text) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$sender_id, $receiver_id, $message_text]);

        echo json_encode(['status' => 'success', 'message' => 'Mensaje enviado.', 'id' => $pdo->lastInsertId()]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Error al guardar el mensaje.']);
    }

} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Método no permitido.']);
}
?>