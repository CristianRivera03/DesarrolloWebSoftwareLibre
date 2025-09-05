<?php
// === conexión robusta (soporta /conf/conf.php o /conf.php) ===
$conf = __DIR__ . '/conf/conf.php';
if (!file_exists($conf)) { $conf = __DIR__ . '/conf.php'; }
if (!file_exists($conf)) { http_response_code(500); die('No se encontró conf.php'); }
require_once $conf;

// normalizar $conn / $con
if (!isset($conn) && isset($con)) { $conn = $con; }
if (!($conn instanceof mysqli)) { http_response_code(500); die('Conexión a BD no disponible'); }

// --- tomar id ---
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) { header('Location: personal.php'); exit; }

// --- eliminar seguro ---
$stmt = $conn->prepare('DELETE FROM personas WHERE id = ?');
$stmt->bind_param('i', $id);
$stmt->execute();

// --- volver al listado ---
header('Location: personal.php');
exit;
