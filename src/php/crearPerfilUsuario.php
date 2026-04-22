<?php

header('Content-Type: application/json; charset=utf-8');
session_start();

require_once("../clases/Usuario.php");
require_once("../clases/Database.php"); 

// 1. Seguridad: Si no está logueado, devolvemos lista vacía
if (!isset($_SESSION['id_usuario'])) {
    echo json_encode([]); 
    exit;
}

// 2. Conexión y obtención de datos
$database = new Database();
$usuario = new Usuario($database);

// Usamos tu función existente que busca los productos del ID actual
$miPerfil = $usuario->crearPerfilUsuario($_SESSION['id_usuario']);

// 3. LIMPIEZA DE SEGURIDAD (IMPORTANTE)
// Recorremos los productos para quitar contraseñas antes de enviarlos
$datosLimpios = array_map(function($item) {
    if(isset($item['password'])) unset($item['password']); // Quitamos la contraseña
    return $item;
}, $miPerfil);

// 4. Devolver el JSON
echo json_encode($datosLimpios);
?>