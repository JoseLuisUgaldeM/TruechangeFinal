<?php



require "../clases/Usuario.php";

$database = new Database();

$db = $database->getConnection();

$usuario = new Usuario($database);

if(isset($_POST['enviar'])){

    $nombre = $_POST['nombre'];

    $apellido1 = $_POST['primerApellido'];

    $apellido2 = $_POST['segundoApellido'];

    $password = $_POST['pass'];

    $email = $_POST['email'];

    $ciudad = $_POST['ciudad'];

    $avatar =$_POST['usuarioNombre'];


}else{

     header("Location:index.php");

}

if ($usuario->crearUsuario($nombre, $apellido1, $apellido2,$email, $password, $ciudad, $avatar )){

    $_SESSION['usuario'] = $usuario;

    $_SESSION['inicioSession'] = true;

    header("Location:sesionIniciada.php");

}

    
