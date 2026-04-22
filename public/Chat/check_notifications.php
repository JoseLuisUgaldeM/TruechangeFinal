<?php
require 'db_config.php';

header('Content-Type: application/json');

// El ID del usuario actual (en este caso, el vendedor que espera la notificación)
$current_user_id =$_SESSION['id_usuario']; 

try {
    // Contamos cuántos mensajes hay dirigidos a mí que no han sido leídos
    $sql = "SELECT COUNT(*) as nuevos FROM messages WHERE receiver_id = ? AND is_read = 0";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$current_user_id]);
    $resultado = $stmt->fetch();

    

    echo json_encode(['status' => 'success', 'count' => $resultado['nuevos']]);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'count' => 0]);
}
