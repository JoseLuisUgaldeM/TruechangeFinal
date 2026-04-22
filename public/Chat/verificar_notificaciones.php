<?php
session_start();
include 'db_config.php'; // Tu conexión a la base de datos

$vendedor_id = $_SESSION['id_usuario']; // El ID del usuario logueado

// Contar mensajes no leídos para este usuario
$sql = "SELECT COUNT(*) as nuevos FROM messages WHERE receiver_id = ? AND leido = 0";
$stmt = $pdo->prepare($sql);
$stmt->execute([$vendedor_id]);
$resultado = $stmt->fetch();

// Devolvemos el número de mensajes nuevos en formato JSON
echo json_encode(['nuevos' => $resultado['nuevos']]);

?>