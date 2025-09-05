<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Inicio de sesión</title>
</head>
<body>
<style>
  body {
    background: linear-gradient(135deg, #e0eafc 0%, #cfdef3 100%);
    font-family: 'Segoe UI', 'Roboto', Arial, sans-serif;
  }
  .login-card {
    border-radius: 22px;
    box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.18);
    background: rgba(255,255,255,0.95);
    padding: 2.5rem 2rem 2rem 2rem;
    min-width: 350px;
    max-width: 370px;
    margin: 0 auto;
  }
  .login-title {
    font-weight: 600;
    color: #2d3a4b;
    margin-bottom: 1.2rem;
    text-align: center;
    letter-spacing: 0.5px;
  }
  .form-label {
    color: #3b4a5a;
    font-weight: 500;
  }
  .btn-primary {
    background: linear-gradient(90deg, #4e54c8 0%, #8f94fb 100%);
    border: none;
    font-weight: 600;
    letter-spacing: 0.5px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(78,84,200,0.08);
    transition: background 0.2s;
  }
  .btn-primary:hover {
    background: linear-gradient(90deg, #8f94fb 0%, #4e54c8 100%);
  }
  .alert-danger {
    border-radius: 10px;
    font-size: 0.98rem;
    margin-bottom: 1.2rem;
  }
</style>
<div class="container d-flex align-items-center justify-content-center min-vh-100">
  <div class="login-card">
    <div class="login-title">Iniciar Sesión</div>
    <?php if(isset($_GET['error'])): ?>
      <div class="alert alert-danger text-center" role="alert">
        Usuario o contraseña incorrectos.
      </div>
    <?php endif; ?>
    <form action="validacion.php" method="POST" novalidate>
      <div class="mb-3">
        <label for="inputEmail" class="form-label">Correo electrónico</label>
        <input type="email" class="form-control" id="inputEmail" name="email" placeholder="Ingresa tu correo" required autofocus>
        <div class="invalid-feedback">Por favor ingresa un correo válido.</div>
      </div>
      <div class="mb-3">
        <label for="inputPassword" class="form-label">Contraseña</label>
        <input type="password" class="form-control" id="inputPassword" name="pwd" placeholder="Contraseña" required>
        <div class="invalid-feedback">La contraseña es obligatoria.</div>
      </div>
      <button type="submit" class="btn btn-primary w-100">Iniciar Sesión</button>
    </form>
  </div>
</div>
<script>
// Validación Bootstrap personalizada
(() => {
  'use strict';
  const forms = document.querySelectorAll('form');
  Array.from(forms).forEach(form => {
    form.addEventListener('submit', event => {
      if (!form.checkValidity()) {
        event.preventDefault();
        event.stopPropagation();
      }
      form.classList.add('was-validated');
    }, false);
  });
})();
</script>
</body>
</html>

