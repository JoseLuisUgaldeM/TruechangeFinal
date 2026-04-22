<?php
session_start();
require_once("../clases/Usuario.php");
require_once("../clases/Database.php");
require_once("crearFicheroJson.php");

if (!isset($_SESSION['id_usuario']) || !isset($_POST['id'])) {
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit;
}

$database = new Database();
$db = $database->getConnection();

try {
    $sql = "UPDATE articulos SET 
            titulo = :titulo, 
            descripcion = :descripcion, 
            categoria = :categoria, 
            estado = :estado 
            WHERE id = :id AND usuario_id = :usuario_id";

    $stmt = $db->prepare($sql);
    $resultado = $stmt->execute([
        ':titulo' => $_POST['titulo'],
        ':descripcion' => $_POST['descripcion'],
        ':categoria' => $_POST['categoria'],
        ':estado' => $_POST['estado'],
        ':id' => $_POST['id'],
        ':usuario_id' => $_SESSION['id_usuario']
    ]);

    $usuarioObj = new Usuario($database);
    creaYactualiza($usuarioObj);
echo json_encode(['success' => $resultado]);
     
     } catch (PDOException $e) {
         echo json_encode(['success' => false, 'message' => $e->getMessage()]);
         }
?>