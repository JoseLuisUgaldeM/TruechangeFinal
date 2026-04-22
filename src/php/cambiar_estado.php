<?php
// public/cambiar_estado.php
require_once "../clases/Usuario.php";
require_once "crearFicheroJson.php";
session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['id_usuario'])) {
    $database = new Database();
    $db = $database->getConnection();
    
    $idArticulo = $_POST['id'];
    $nuevoEstado = $_POST['estadoArticulo'];
    $idUsuario = $_SESSION['id_usuario'];
    $fecha_venta = date('Y-m-d H:i:s');
; 

    // --- CAMBIO 1: Recoger el receptor  si no viene, es null ---
    $receptorId = !empty($_POST['receptor_id']) ? $_POST['receptor_id'] : null;
     if ($receptorId==null) $fecha_venta=null;
    // --- CAMBIO 2: Añadir 'receptor_id = ?' a la consulta ---
    $query = "UPDATE articulos SET estadoArticulo = ?, receptor_id = ? WHERE id = ? AND usuario_id = ?";
    $stmt = $db->prepare($query);
    
    // --- CAMBIO 3: Añadir $receptorId al array de ejecución ---
    if ($stmt->execute([$nuevoEstado, $receptorId, $idArticulo, $idUsuario])) {

    // 1. Verificar que el artículo pertenece al usuario logueado
   $query = "UPDATE articulos SET estadoArticulo = ?, fecha_venta = ? WHERE id = ? AND usuario_id = ?";
$stmt = $db->prepare($query);


    
    if ($stmt->execute([$nuevoEstado, $fecha_venta, $idArticulo, $idUsuario])) {
        
        // 2. Regenerar los ficheros JSON para que el cambio se vea en la web
        $usuarioObj = new Usuario($database);
        creaYactualiza($usuarioObj); 
        
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "No se pudo actualizar"]);
    }
}
}