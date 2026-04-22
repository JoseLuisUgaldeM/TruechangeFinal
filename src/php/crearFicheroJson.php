<?php

require_once __DIR__ . "/../clases/Usuario.php";


function creaYactualiza($usuario)
{

    header('Content-Type: application/json');

    $resultado = $usuario->crearFichero();

    $datos = array();

    $datos = $resultado;

    $datosCopiados = false;

    $json_string = json_encode($datos, JSON_PRETTY_PRINT);

    $fichero = 'datos_usuario.json';

    if (file_put_contents($fichero, $json_string, LOCK_EX) !== false) {

        $datosCopiados = true;
    } else {
        echo "<script> alert ('Error al escribir el archivo.')</script>";
    }
}


function creaYactualiza2($id , $usuario)
{

    header('Content-Type: application/json');


    $resultado = $usuario->crearFicheroMisProductos($id);


    $datos = array();

    $datos = $resultado;

    $datosCopiados = false;

    $json_string = json_encode($datos, JSON_PRETTY_PRINT);

    $fichero = 'datos_usuario_mis_productos.json';

    if (file_put_contents($fichero, $json_string, LOCK_EX) !== false) {

        $datosCopiados = true;
        
    } else {
        echo "<script> alert ('Error al escribir el archivo.')</script>";
    }
}
