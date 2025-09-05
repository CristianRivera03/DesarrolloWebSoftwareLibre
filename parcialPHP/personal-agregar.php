<?php
// === conexión robusta (soporta /conf/conf.php o /conf.php) ===
$conf = __DIR__ . '/conf/conf.php';
if (!file_exists($conf)) { $conf = __DIR__ . '/conf.php'; }
if (!file_exists($conf)) { die('No se encontró conf.php'); }
require_once $conf;

// normalizar $conn / $con
if (!isset($conn) && isset($con)) { $conn = $con; }
if (!($conn instanceof mysqli)) { die('Conexión a BD no disponible'); }

// --- estado inicial ---
$err = '';
$val = [
  'nombres'   => '',
  'apellidos' => '',
  'dui'       => '',
  'telefono'  => '',
  'correo'    => '',
  'direccion' => '',
  'foto_path' => ''
];

// --- manejar envío ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  foreach ($val as $k => $_) { $val[$k] = trim($_POST[$k] ?? ''); }

  // validar DUI único (prepared)
  $stmt = $conn->prepare('SELECT 1 FROM personas WHERE dui=? LIMIT 1');
  $stmt->bind_param('s', $val['dui']);
  $stmt->execute();
  $existe = $stmt->get_result()->num_rows > 0;

  if ($existe) {
    $err = 'El DUI ya está registrado. Corrige el dato.';
  } else {
    // insertar (fecha_registro la pone el TIMESTAMP por defecto)
    $stmt = $conn->prepare('INSERT INTO personas
      (nombres, apellidos, dui, telefono, correo, direccion, foto_path)
      VALUES (?, ?, ?, ?, ?, ?, ?)');
    $stmt->bind_param(
      'sssssss',
      $val['nombres'], $val['apellidos'], $val['dui'], $val['telefono'],
      $val['correo'], $val['direccion'], $val['foto_path']
    );

    if ($stmt->execute()) {
      header('Location: personal.php'); exit;
    } else {
      $err = 'Error al guardar: ' . $conn->error;
    }
  }
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Agregar persona</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <?php include 'nav.php'; ?>

  <div class="container py-4" style="max-width: 840px;">
    <h3 class="mb-3">Agregar persona</h3>

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
               placeholder="00000000-0"
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
               placeholder="ruta de imagen"
               value="<?= htmlspecialchars($val['foto_path']) ?>">
      </div>

      <div class="col-12 d-flex gap-2 mt-2">
        <button class="btn btn-success" type="submit">Guardar</button>
        <a class="btn btn-secondary" href="personal.php">Cancelar</a>
      </div>
    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
