<?php
require_once __DIR__ . '/config.php';

function currentUser() { return $_SESSION['user'] ?? null; }
function requireLogin() { if (!currentUser()) { header('Location: index.php'); exit; } }
function can(string $module): bool {
    $u = currentUser();
    if (!$u) return false;
    return in_array($module, PERMISSIONS[$u['role_id']] ?? []);
}
function requirePermission(string $module) {
    requireLogin();
    if (!can($module)) { http_response_code(403); die('Acceso denegado'); }
}
function e($s) { return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8'); }
function money($n) { return '$' . number_format($n, 2); }
function flash($msg, $type='success') { $_SESSION['flash'] = compact('msg','type'); }
function getFlash() { $f = $_SESSION['flash'] ?? null; unset($_SESSION['flash']); return $f; }
function generateCode($prefix) { return $prefix . strtoupper(substr(md5(uniqid()),0,6)); }