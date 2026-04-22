<?php
require 'db_config.php';
header('Content-Type: application/json');

$mi_id = CURRENT_USER_ID; 

try {
    // Usamos "?"
    $sql = "SELECT 
                u.id AS partner_id, 
                u.nombre as nombre_usuario, 
                m.message_text AS ultimo_mensaje, 
                m.timestamp AS fecha,
                (SELECT COUNT(*) FROM messages 
                 WHERE sender_id = u.id AND receiver_id = ? AND is_read = 0) as total_no_leidos
            FROM messages m
            INNER JOIN usuarios u ON (u.id = m.sender_id OR u.id = m.receiver_id)
            WHERE (m.sender_id = ? OR m.receiver_id = ?) 
            AND u.id != ?
            AND m.id = (
                SELECT MAX(id) 
                FROM messages 
                WHERE (sender_id = ? AND receiver_id = u.id) 
                   OR (sender_id = u.id AND receiver_id = ?)
            )
            GROUP BY u.id
            ORDER BY m.id DESC";

    $stmt = $pdo->prepare($sql);
    
    // Pasamos el ID tantas veces como aparecen los "?" en la consulta (6 veces)
    $stmt->execute([$mi_id, $mi_id, $mi_id, $mi_id, $mi_id, $mi_id]);
    
    $chats = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'status' => 'success', 
        'chats' => $chats ? $chats : []
    ]);

} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error', 
        'message' => $e->getMessage(), 
        'chats' => []
    ]);
}