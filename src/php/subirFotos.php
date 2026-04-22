<?php


if (isset($_POST["subirFotos"])) {
    // Directorio donde se guardarán las imágenes

   

    $target_dir = "/TrueChange/src/imagenes/uploads";

    $target_file = $target_dir . basename($_FILES["imagen"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // 1. Verificar si la imagen es real o un ataque de imagen falso
    if (isset($_POST["subirFotos"])) {
        $check = getimagesize($_FILES["imagen"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            echo "El archivo no es una imagen.";
            $uploadOk = 0;
        }
    }


    // 3. Permitir ciertos formatos de archivo
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
        echo "Lo sentimos, solo se permiten archivos JPG, JPEG y PNG.";
        $uploadOk = 0;
    }

    // 4. Verificar si $uploadOk es 0 por algún error
    if ($uploadOk == 0) {
        echo "Lo sentimos, su archivo no fue subido.";
    } else {
        // Intentar subir el archivo
        if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $target_file)) {
            echo "El archivo " . htmlspecialchars(basename($_FILES["imagen"]["name"])) . " ha sido subido.";

            // 5. Insertar la ruta del archivo en la base de datos
        }
    }
}
