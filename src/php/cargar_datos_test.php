<?php
require_once "../clases/Database.php";

$database = new Database();
$db = $database->getConnection();

try {
    $db->beginTransaction();

    // USUARIOS DE PRUEBA
    $usuarios = [
        ["Ana", "Lopez", "Garcia", "ana@test.com", "AnaTest", "Logroño"],
        ["Carlos", "Perez", "Ruiz", "carlos@test.com", "CarlosTest", "Vitoria"],
        ["Marta", "Sanz", "Mora", "marta@test.com", "MartaTest", "Bilbao"]
    ];

    foreach ($usuarios as $u) {
        $sql = "INSERT INTO usuarios 
        (nombre, apellido1, apellido2, email, password, usuarioNombre, ciudad, avatar)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $db->prepare($sql);
        $stmt->execute([
            $u[0],
            $u[1],
            $u[2],
            $u[3],
            password_hash("1234", PASSWORD_DEFAULT),
            $u[4],
            $u[5],
            "../imagenes/default.png"
        ]);
    }

    // OBTENER IDS
    $ids = $db->query("SELECT id FROM usuarios ORDER BY id DESC LIMIT 3")
              ->fetchAll(PDO::FETCH_COLUMN);

    // ARTÍCULOS DE PRUEBA
    $articulos = [
        [$ids[0], "Bicicleta de montaña", "Bicicleta en buen estado", "Bicicletas", "usado", "Patinete eléctrico", "disponible"],
        [$ids[0], "Cámara Canon", "Cámara casi nueva", "Tecnología y electrónica", "como nuevo", "Tablet", "reservado"],
        [$ids[1], "Chaqueta de cuero", "Chaqueta talla M", "Moda y accesorios", "usado", "Zapatillas", "disponible"],
        [$ids[1], "PlayStation 5", "Consola con mando", "Tecnología y electrónica", "como nuevo", "Nintendo Switch", "vendido"],
        [$ids[2], "Mesa de escritorio", "Mesa blanca grande", "Hogar y jardín", "usado", "Silla gaming", "disponible"]
    ];

    foreach ($articulos as $a) {
        $sql = "INSERT INTO articulos 
        (usuario_id, titulo, descripcion, categoria, estado, cambio, estadoArticulo, fecha_publicacion)
        VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";

        $stmt = $db->prepare($sql);
        $stmt->execute($a);

        $articulo_id = $db->lastInsertId();

        $sqlFoto = "INSERT INTO articulos_fotos (articulo_id, ruta_foto)
                    VALUES (?, ?)";
        $stmtFoto = $db->prepare($sqlFoto);
        $stmtFoto->execute([
            $articulo_id,
            "../imagenes/uploads/default.png"
        ]);
    }

    $db->commit();

    echo "Datos de prueba cargados correctamente.";

} catch (Exception $e) {
    $db->rollBack();
    echo "Error cargando datos: " . $e->getMessage();
}
?>