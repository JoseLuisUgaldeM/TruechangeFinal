<?php 



require_once ("../clases/Articulos_fotos.php");

require_once ("../clases/Articulos.php");


session_start(); // Iniciamos la sesión y traemos las variables globales del usuario


$database = new Database();

$db = $database->getConnection();

if ($_SESSION['inicioSesion'] == true){



if(isset($_POST['publicar'])){

    $titulo = $_POST['titulo'];
    $categoria = $_POST['categoria'];
    $descripcion = $_POST['descripcion'];
    $estado = $_POST['estado'];
    $cambio = $_POST['cambio'];

    $id_usuario = $_SESSION['id_usuario'];
    
    $articulo = new Articulos($database);

    $articulo->subirArticulo($id_usuario, $titulo,$descripcion,$categoria, $estado, $cambio);

    $articulo_id = $articulo->idUltimoArticulo();

    $directorioSubidas = "../imagenes/uploads/";

     $nombreOriginal = $_FILES["foto"]["name"];
    $tempArchivo = $_FILES["foto"]["tmp_name"];
    $extension = pathinfo($nombreOriginal, PATHINFO_EXTENSION);

    // Generar un nombre único para evitar sobrescrituras y problemas de seguridad
    $nombreGuardado = uniqid() . "." . $extension;
    $rutaCompleta = $directorioSubidas . $nombreGuardado;

    // Mover el archivo de la carpeta temporal a la carpeta permanente
    if (move_uploaded_file($tempArchivo, $rutaCompleta)) {
        try {
            // Guardar solo la ruta relativa en la base de datos
            $articulofoto = new Articulos_fotos($database);
           
            $articulofoto->subirFotoArticulo( $articulo_id, $rutaCompleta);

                echo "<script>
                 alert('El articulo se ha subido correctamente')
                </script>";
           

        } catch (PDOException $e) {
            die("Error al guardar la ruta en la DB: " . $e->getMessage());
        }
    } else {
             echo "<script>
             alert('Error subiendo el articulo');
             </script>";
    }

        $usuarioNombre =$_SESSION['usuarioNombre'];
        $id_usuario =$_SESSION['id_usuario'];

      


           

      
    }


}

header("Location:sesionIniciada.php");

?>


