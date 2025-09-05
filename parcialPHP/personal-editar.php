<?php
// === conexión robusta (soporta /conf/conf.php o /conf.php) ===
$conf = __DIR__ . '/conf/conf.php';
if (!file_exists($conf)) { $conf = __DIR__ . '/conf.php'; }
if (!file_exists($conf)) { die('No se encontró conf.php'); }
require_once $conf;

// normalizar $conn / $con
if (!isset($conn) && isset($con)) { $conn = $con; }
if (!($conn instanceof mysqli)) { die('Conexión a BD no disponible'); }

// --- id válido ---
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) { header('Location: personal.php'); exit; }

// --- cargar registro actual (prepared) ---
$stmt = $conn->prepare('SELECT * FROM personas WHERE id=?');
$stmt->bind_param('i', $id);
$stmt->execute();
$res = $stmt->get_result();
if (!$res || !$res->num_rows) { die('Registro no encontrado'); }
$row = $res->fetch_assoc();

$err = '';
$val = $row; // valores iniciales

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  foreach (['nombres','apellidos','dui','telefono','correo','direccion','foto_path'] as $k) {
    $val[$k] = trim($_POST[$k] ?? '');
  }

  // --- DUI único exceptuando este id ---
  $stmt = $conn->prepare('SELECT 1 FROM personas WHERE dui=? AND id<>? LIMIT 1');
  $stmt->bind_param('si', $val['dui'], $id);
  $stmt->execute();
  $duiTomado = $stmt->get_result()->num_rows > 0;

  if ($duiTomado) {
    $err = 'El DUI ya está registrado en otra persona.';
  } else {
    $stmt = $conn->prepare('UPDATE personas
      SET nombres=?, apellidos=?, dui=?, telefono=?, correo=?, direccion=?, foto_path=?
      WHERE id=?');
    $stmt->bind_param(
      'sssssssi',
      $val['nombres'], $val['apellidos'], $val['dui'], $val['telefono'],
      $val['correo'], $val['direccion'], $val['foto_path'], $id
    );
    if ($stmt->execute()) {
      header('Location: personal.php'); exit;
    } else {
      $err = 'Error al actualizar: ' . $conn->error;
    }
  }
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Editar persona</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <?php include 'nav.php'; ?>

  <div class="container py-4" style="max-width: 840px;">
    <h3 class="mb-3">Editar persona</h3>

    <?php if($err): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($err) ?></div>
    <?php endif; ?>

    <form method="post" class="row gy-3">
      <div class="col-md-6">
        <label class="form-label">Nombres</label>
        <input class="form-control" type="text" name="nombres" required
               value="<?= htmlspecialchars($val['nombres']) ?>">
      </div>
      <div class="col-md-6">
        <label class="form-label">Apellidos</label>
        <input class="form-control" type="text" name="apellidos" required
               value="<?= htmlspecialchars($val['apellidos']) ?>">
      </div>
      <div class="col-md-4">
        <label class="form-label">DUI</label>
        <input class="form-control" type="text" name="dui" required
               value="<?= htmlspecialchars($val['dui']) ?>">
      </div>
      <div class="col-md-4">
        <label class="form-label">Teléfono</label>
        <input class="form-control" type="text" name="telefono"
               value="<?= htmlspecialchars($val['telefono']) ?>">
      </div>
      <div class="col-md-4">
        <label class="form-label">Correo</label>
        <input class="form-control" type="email" name="correo"
               value="<?= htmlspecialchars($val['correo']) ?>">
      </div>
      <div class="col-12">
        <label class="form-label">Dirección</label>
        <textarea class="form-control" name="direccion" rows="3"><?= htmlspecialchars($val['direccion']) ?></textarea>
      </div>
      <div class="col-12">
        <label class="form-label">Ruta de imagen (opcional)</label>
        <input class="form-control" type="text" name="foto_path"
               value="<?= htmlspecialchars($val['foto_path']) ?>" placeholder="/img/fotos/sofi.png">
      </div>

      <div class="col-12 d-flex gap-2 mt-2">
        <button class="btn btn-primary" type="submit">Actualizar</button>
        <a class="btn btn-secondary" href="personal.php">Cancelar</a>
      </div>
    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
