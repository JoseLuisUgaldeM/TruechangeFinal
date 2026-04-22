<?php

session_start();

require "../clases/Usuario.php";

$database = new Database();

$db = $database->getConnection();

$usuario = new Usuario($database);

// Comprobamos si el usuario está logueado
 

$usuarioNombre = $_POST['usuarioNombre'];

$password = $_POST['pass'];

// Guardamos el id del usuario en la variable global session.

if ($datos=  $usuario->login($usuarioNombre, $password)){


    $_SESSION['inicioSesion'] = true;

    $_SESSION['id_usuario'] = $datos['id'];
  

    header("Location:sesionIniciada.php");

}else{


    echo "<script>alert('ERROR El usuario no existe. Registrese');
     window.location.href = 'index.php';
     </script>";
}




