<?php
// public/obtener_usuarios_chat.php

// 1. Evitamos que los errores de PHP salgan como HTML y rompan el JSON
ini_set('display_errors', 0);
header('Content-Type: application/json');

try {
    session_start();

    // 2. Intentamos cargar Usuario.php buscando en las dos rutas posibles
    // (Según tu estructura puede estar en la misma carpeta o en ../src/)
    if (file_exists("Usuario.php")) {
        require_once "Usuario.php";
    } elseif (file_exists("../clases/Usuario.php")) {
        require_once "../clases/Usuario.php";
    } else {
        throw new Exception("No se encuentra el archivo Usuario.php. Revisa la ruta.");
    }

    if (!isset($_SESSION['id_usuario'])) {
        throw new Exception("No has iniciado sesión.");
    }

    $miId = $_SESSION['id_usuario'];
    
    // Conexión
    $database = new Database();
    $db = $database->getConnection();

    // Consulta: Todos los usuarios menos yo
    $sql = "SELECT id, nombre, apellido1 FROM usuarios WHERE id != ? ORDER BY nombre ASC";
    
    $stmt = $db->prepare($sql);
    $stmt->execute([$miId]);
    
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Devolvemos los datos
    echo json_encode($usuarios);

} catch (Exception $e) {
    // Si algo falla, devolvemos el error en JSON para verlo en la consola
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}
?>