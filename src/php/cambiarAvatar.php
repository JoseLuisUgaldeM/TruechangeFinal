
<?php
require("../clases/Usuario.php");

$database = new Database();

$db = $database->getConnection();

session_start();
$target_dir = "../imagenes/uploads/"; // Directorio donde se guardarán las imágenes
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

// Verifica si el archivo de imagen es real o falso
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        echo "El archivo es una imagen - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "El archivo no es una imagen.";
        $uploadOk = 0;
    }
}

// Verifica si el archivo ya existe


// Limita el tamaño del archivo (ejemplo: 5MB)
if ($_FILES["fileToUpload"]["size"] > 5000000) {
    echo "Lo sentimos, tu archivo es demasiado grande.";
    $uploadOk = 0;
}

// Permite ciertos formatos de archivo
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
   
    $uploadOk = 0;
}

// Verifica si $uploadOk es 0 por un error
if ($uploadOk == 0) {
   
// Si todo está bien, intenta subir el archivo
} else {
    // move_uploaded_file mueve el archivo temporal a la ubicación final
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        
        $usuarioNombre =$_SESSION['usuarioNombre'];
        $id_usuario =$_SESSION['id_usuario'];

      
        $usuario = new Usuario($database);

     $usuario ->cambiarAvatar( $id_usuario, $target_file);

           

      
    }
}


header("Location:sesionIniciada.php");

?>

