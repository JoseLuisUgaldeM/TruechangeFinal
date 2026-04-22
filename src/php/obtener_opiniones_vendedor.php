<?php

require_once "../clases/Usuario.php";
header('Content-Type: application/json');

if (isset($_GET['id'])) {
    $vendedorId = $_GET['id'];
    $database = new Database();
    $db = $database->getConnection();

    // Consultamos las reseñas y el nombre de quien las escribió (emisor)
    $sql = "SELECT r.*, u.nombre as nombre_emisor 
            FROM reseñas r 
            INNER JOIN usuarios u ON r.emisor_id = u.id 
            WHERE r.receptor_id = ? 
            ORDER BY r.fecha DESC";
    
    $stmt = $db->prepare($sql);
    $stmt->execute([$vendedorId]);
    $opiniones = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($opiniones);
}