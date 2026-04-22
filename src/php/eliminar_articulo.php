<?php
session_start();
require_once "../clases/Usuario.php";
require_once "crearFicheroJson.php";
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

// 1. Verificamos sesión y datos
if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(['success' => false, 'message' => 'No has iniciado sesión']);
    exit;
}

if (!isset($_POST['id'])) {
    echo json_encode(['success' => false, 'message' => 'No se recibió el ID del artículo']);
    exit;
}


$rutaDB = "../clases/Database.php"; 
if (!file_exists($rutaDB)) {
    // Si no existe probamos en 
    $rutaDB = "Database.php"; 
}

if (file_exists($rutaDB)) {
    require_once($rutaDB);
} else {
    echo json_encode(['success' => false, 'message' => 'Error: No se encuentra Database.php']);
    exit;
}

$database = new Database();
$db = $database->getConnection();

$id_articulo = $_POST['id'];
$id_usuario = $_SESSION['id_usuario'];

try {
    // 3. PRIMERO: Verificar que el artículo es del usuario
    $checkSql = "SELECT id FROM articulos WHERE id = :id AND usuario_id = :usuario_id";
    $checkStmt = $db->prepare($checkSql);
    $checkStmt->execute([':id' => $id_articulo, ':usuario_id' => $id_usuario]);

    if ($checkStmt->rowCount() == 0) {
        echo json_encode(['success' => false, 'message' => 'No tienes permiso para borrar este artículo o no existe.']);
        exit;
    }

    // 4. SEGUNDO: Borrar las fotos asociadas (Para evitar error de clave foránea)
    $sqlFotos = "DELETE FROM articulos_fotos WHERE articulo_id = :id_articulo";
    $stmtFotos = $db->prepare($sqlFotos);
    $stmtFotos->execute([':id_articulo' => $id_articulo]);

    // 5. TERCERO: Borrar el artículo
    $sql = "DELETE FROM articulos WHERE id = :id_articulo";
    $stmt = $db->prepare($sql);
    
    if ($stmt->execute([':id_articulo' => $id_articulo])) {
        $usuarioObj = new Usuario($database);
        creaYactualiza($usuarioObj);
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al borrar el artículo en la BD']);
    }

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error SQL: ' . $e->getMessage()]);
}
?>