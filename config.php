<?php
session_start();
$pdo = new PDO('mysql:host=localhost;dbname=comercial_pro;charset=utf8mb4', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
]);

// Roles como constantes
define('ROLE_ADMIN', 1);
define('ROLE_VENDEDOR', 2);
define('ROLE_ALMACEN', 3);
define('ROLE_ATENCION', 4);

// Permisos por módulo: rol => [módulos permitidos]
define('PERMISSIONS', [
    ROLE_ADMIN    => ['dashboard','products','inventory','customers','sales','tickets','users'],
    ROLE_VENDEDOR => ['dashboard','products','customers','sales','tickets'],
    ROLE_ALMACEN  => ['dashboard','products','inventory'],
    ROLE_ATENCION => ['dashboard','customers','tickets'],
]);