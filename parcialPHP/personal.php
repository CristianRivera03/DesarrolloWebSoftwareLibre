<?php
$conf = __DIR__ . '/conf/conf.php';
if (!file_exists($conf)) { $conf = __DIR__ . '/conf.php'; }
if (!file_exists($conf)) { die('No se encontró conf.php'); }
require_once $conf;
if (!isset($conn) && isset($con)) { $conn = $con; }
if (!($conn instanceof mysqli)) { die('Conexión a BD no disponible'); }

$personas = $conn->query("SELECT * FROM personas ORDER BY id DESC");
if ($personas === false) {
  die('Error de consulta: ' . $conn->error);
}
?>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Personal</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .avatar{width:50px;height:50px;border-radius:50%;object-fit:cover}
  </style>
</head>
<body>
  <?php include 'nav.php'; ?>

  <div class="container py-4">
    <h3 class="mb-3">Personal</h3>
    <a href="personal-agregar.php" class="btn btn-success mb-3">Agregar persona</a>

    <div class="table-responsive">
      <table class="table table-bordered table-hover align-middle">
        <thead class="table-light">
          <tr>
            <th>Foto</th>
            <th>Nombres</th>
            <th>Apellidos</th>
            <th>DUI</th>
            <th>Teléfono</th>
            <th>Correo</th>
            <th>Fecha registro</th>
            <th style="width:140px;">Acciones</th>
          </tr>
        </thead>
        <tbody>
        <?php if($personas->num_rows): ?>
          <?php while($p = $personas->fetch_assoc()): ?>
            <tr>
              <td><img class="avatar" src="assets/avatar-user.svg" alt="avatar"></td>
              <td><?= htmlspecialchars($p['nombres']) ?></td>
              <td><?= htmlspecialchars($p['apellidos']) ?></td>
              <td><?= htmlspecialchars($p['dui']) ?></td>
              <td><?= htmlspecialchars($p['telefono']) ?></td>
              <td><?= htmlspecialchars($p['correo']) ?></td>
              <td><?= htmlspecialchars($p['fecha_registro']) ?></td>
              <td>
                <a class="btn btn-sm btn-primary" href="personal-editar.php?id=<?= (int)$p['id'] ?>">Editar</a>
                <a class="btn btn-sm btn-danger"
                   href="personal-eliminar.php?id=<?= (int)$p['id'] ?>"
                   onclick="return confirm('¿Eliminar este registro?')">Eliminar</a>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="8" class="text-center text-muted">sin registros</td></tr>
        <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>