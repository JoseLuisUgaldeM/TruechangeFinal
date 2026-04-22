<?php
require 'db_config.php';
header('Content-Type: application/json');

if (!defined('CURRENT_USER_ID')) {
    echo json_encode(['status' => 'error', 'message' => 'Sesión no iniciada']);
    exit;
}
$current_user_id = CURRENT_USER_ID; 

if (isset($_GET['partner_id'])) {
    
    $partner_id = filter_var($_GET['partner_id'], FILTER_VALIDATE_INT);
    // last_id se usa para optimizar y solo cargar mensajes nuevos
    $last_message_id = isset($_GET['last_id']) ? filter_var($_GET['last_id'], FILTER_VALIDATE_INT) : 0; 

    if (!$current_user_id || !$partner_id) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'ID de compañero inválido.']);
        exit;
    }

    try {
        // Selecciona mensajes donde la conversación es entre current_user_id y partner_id
        $sql = "SELECT id, sender_id, message_text, timestamp 
                FROM messages 
                WHERE ((sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?))
                AND id > ? 
                ORDER BY id ASC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $current_user_id, 
            $partner_id, 
            $partner_id, 
            $current_user_id, 
            $last_message_id
        ]);
        $messages = $stmt->fetchAll();

        echo json_encode(['status' => 'success', 'messages' => $messages]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Error al obtener mensajes.']);
    }

} else {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Falta el ID del compañero.']);
}
?>