<?php

require_once "crearFicheroJson.php";
require_once "../clases/Usuario.php";

try {
    $database = new Database();
    $db = $database->getConnection();

    $emailsPrueba = [
        'ana@test.com',
        'marta@test.com',
        'carlos@test.com'
    ];

    foreach ($emailsPrueba as $email) {

        $stmt = $db->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        $idUsuario = $stmt->fetchColumn();

        if ($idUsuario) {

            $stmt = $db->prepare("SELECT id FROM articulos WHERE usuario_id = ?");
            $stmt->execute([$idUsuario]);
            $articulos = $stmt->fetchAll(PDO::FETCH_COLUMN);

            foreach ($articulos as $idArticulo) {

                $stmt = $db->prepare("DELETE FROM reseñas WHERE articulo_id = ?");
                $stmt->execute([$idArticulo]);

                $stmt = $db->prepare("DELETE FROM articulos_fotos WHERE articulo_id = ?");
                $stmt->execute([$idArticulo]);

                $stmt = $db->prepare("DELETE FROM articulos WHERE id = ?");
                $stmt->execute([$idArticulo]);
            }

            // Borrar reseñas donde participa ese usuario
            $stmt = $db->prepare("DELETE FROM reseñas WHERE emisor_id = ? OR receptor_id = ?");
            $stmt->execute([$idUsuario, $idUsuario]);

            $stmt = $db->prepare("DELETE FROM usuarios WHERE id = ?");
            $stmt->execute([$idUsuario]);
        }
    }

    $usuario = new Usuario($database);
    creaYactualiza($usuario);

    header("Location: pruebas.php?borrado=ok");
    exit();

} catch (PDOException $e) {
    die("ERROR SQL: " . $e->getMessage());
}
?>