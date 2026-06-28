<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: inicio_sesion.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Finalizar Compra - Tienda de Videojuegos</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <link rel="stylesheet" href="css/estilo.css">
  <style>
    body {
      background-color: #f0f2f5;
      font-family: 'Arial', sans-serif;
    }

    /* Header usa los estilos de estilo.css; solo ajustamos el layout interno */
    header {
      text-align: center;
      padding: 1rem 0;
      position: relative;
    }

    header h1 {
      font-size: 1.5rem;
      margin: 0;
    }

    .btn-volver {
      position: absolute;
      top: 50%;
      left: 1.5rem;
      transform: translateY(-50%);
      background-color: #6d28d9;
      color: white;
      border: none;
      padding: 0.45rem 1rem;
      border-radius: 6px;
      text-decoration: none;
      font-size: 0.85rem;
      font-weight: 600;
      transition: background-color 0.2s;
    }

    .btn-volver:hover {
      background-color: #5b21b6;
      color: white;
    }

    main {
      max-width: 920px;
      margin: 2rem auto 3rem;
      background: white;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.08);
      padding: 2rem 2.5rem;
    }

    .seccion-titulo {
      font-size: 1rem;
      font-weight: 700;
      color: #4c1d95;
      margin-bottom: 1rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
      border-bottom: 2px solid #ede9fe;
      padding-bottom: 0.5rem;
    }

    .list-group-item {
      font-size: 0.9rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 0.6rem 0.9rem;
    }

    .total-line {
      font-weight: 700;
      font-size: 1rem;
      color: #4c1d95;
      margin-top: 0.75rem;
    }

    .form-label {
      font-size: 0.85rem;
      font-weight: 600;
      color: #374151;
      margin-bottom: 0.3rem;
    }

    .form-control {
      font-size: 0.9rem;
      border-radius: 8px;
      border: 1px solid #d1d5db;
    }

    .form-control:focus {
      border-color: #7c3aed;
      box-shadow: 0 0 0 3px rgba(124,58,237,0.15);
    }

    .btn-pay {
      background-color: #6d28d9;
      border: none;
      color: white;
      font-weight: 700;
      font-size: 1rem;
      padding: 0.75rem 1.5rem;
      border-radius: 8px;
      transition: background-color 0.2s;
      letter-spacing: 0.02em;
    }

    .btn-pay:hover {
      background-color: #5b21b6;
      color: white;
    }

    .divider-v {
      border-left: 1px solid #e5e7eb;
      min-height: 100%;
    }

    /* Pantalla de confirmación */
    .confirmacion {
      text-align: center;
      padding: 3rem 1rem;
    }

    .confirmacion .icono-check {
      font-size: 4rem;
      color: #6d28d9;
      margin-bottom: 1rem;
    }

    .confirmacion h2 {
      font-size: 1.8rem;
      font-weight: 700;
      color: #1e1b4b;
      margin-bottom: 0.5rem;
    }

    .confirmacion p {
      color: #6b7280;
      margin-bottom: 1.5rem;
    }

    footer {
      background-color: #1a1a2e;
      color: rgba(255,255,255,0.7);
      text-align: center;
      padding: 1rem 0;
      font-size: 0.82rem;
      border-top: 2px solid #6d28d9;
    }

    footer p { margin: 0; }
  </style>
</head>

<body>

<header class="bg-dark text-white p-3">
  <div class="container" style="position:relative;">
    <a href="index.html" class="btn-volver">← Volver</a>
    <h1>Finalizar Compra</h1>
  </div>
</header>

<main id="contenidoPrincipal">
  <div class="row g-4">

    <!-- Resumen del carrito -->
    <div class="col-md-5">
      <p class="seccion-titulo"><i class="bi bi-cart-fill"></i> Tu carrito</p>
      <ul id="carrito-lista" class="list-group mb-3"></ul>
      <p class="total-line">Total: $<span id="carrito-total">0.00</span></p>
      <button type="button" class="btn btn-outline-danger btn-sm w-100 mt-1" onclick="vaciarCarrito()">
        <i class="bi bi-trash"></i> Vaciar carrito
      </button>
    </div>

    <div class="col-md-1 d-none d-md-flex justify-content-center">
      <div class="divider-v"></div>
    </div>

    <!-- Formulario -->
    <div class="col-md-6">
      <form id="formPago" novalidate>

        <p class="seccion-titulo"><i class="bi bi-truck"></i> Datos de envío</p>

        <div class="mb-2">
          <label for="nombreEnvio" class="form-label">Nombre completo</label>
          <input type="text" class="form-control" id="nombreEnvio" placeholder="Juan Pérez" required />
        </div>

        <div class="mb-2">
          <label for="direccionEnvio" class="form-label">Dirección</label>
          <input type="text" class="form-control" id="direccionEnvio" placeholder="Calle, número, colonia" required />
        </div>

        <div class="row g-2 mb-2">
          <div class="col-7">
            <label for="ciudadEnvio" class="form-label">Ciudad</label>
            <input type="text" class="form-control" id="ciudadEnvio" required />
          </div>
          <div class="col-5">
            <label for="cpEnvio" class="form-label">Código postal</label>
            <input type="text" class="form-control" id="cpEnvio" required />
          </div>
        </div>

        <div class="mb-3">
          <label for="telefonoEnvio" class="form-label">Teléfono</label>
          <input type="tel" class="form-control" id="telefonoEnvio" placeholder="10 dígitos" required />
        </div>

        <p class="seccion-titulo"><i class="bi bi-credit-card-2-front-fill"></i> Información de pago</p>

        <div class="mb-2">
          <label for="nombre" class="form-label">Nombre en la tarjeta</label>
          <input type="text" class="form-control" id="nombre" placeholder="Juan Pérez" required />
        </div>

        <div class="mb-2">
          <label for="tarjeta" class="form-label">Número de tarjeta</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-credit-card"></i></span>
            <input type="text" class="form-control" id="tarjeta" placeholder="1234 5678 9012 3456" maxlength="19" pattern="[\d ]{13,19}" required />
          </div>
        </div>

        <div class="row g-2 mb-3">
          <div class="col-6">
            <label for="expiracion" class="form-label">Expiración</label>
            <input type="month" class="form-control" id="expiracion" min="2023-06" required />
          </div>
          <div class="col-6">
            <label for="cvv" class="form-label">CVV</label>
            <input type="password" class="form-control" id="cvv" maxlength="4" pattern="\d{3,4}" placeholder="123" required />
          </div>
        </div>

        <div id="errorCompra" class="alert alert-danger d-none"></div>

        <button type="submit" class="btn btn-pay w-100">
          <i class="bi bi-lock-fill me-2"></i>Pagar ahora
        </button>

      </form>
    </div>

  </div>
</main>

<footer>
  <p>&copy; 2025 Tienda de Videojuegos. Todos los derechos reservados.</p>
</footer>

<script src="js/carrito.js"></script>
<script>
  // === Validaciones en tiempo real de tarjeta ===

  // Número de tarjeta: solo dígitos, formato 1234 5678 9012 3456
  const inputTarjeta = document.getElementById('tarjeta');
  inputTarjeta.addEventListener('input', () => {
    let val = inputTarjeta.value.replace(/\D/g, '').slice(0, 16);
    inputTarjeta.value = val.replace(/(.{4})/g, '$1 ').trim();
  });

  // CVV: solo 3 o 4 dígitos
  const inputCvv = document.getElementById('cvv');
  inputCvv.addEventListener('input', () => {
    inputCvv.value = inputCvv.value.replace(/\D/g, '').slice(0, 4);
  });

  // Teléfono: solo dígitos, máximo 10
  const inputTel = document.getElementById('telefonoEnvio');
  inputTel.addEventListener('input', () => {
    inputTel.value = inputTel.value.replace(/\D/g, '').slice(0, 10);
  });

  // CP: solo dígitos, máximo 5
  const inputCp = document.getElementById('cpEnvio');
  inputCp.addEventListener('input', () => {
    inputCp.value = inputCp.value.replace(/\D/g, '').slice(0, 5);
  });

  // === Envío del formulario ===
  (() => {
    'use strict';
    const form = document.getElementById('formPago');
    const main = document.getElementById('contenidoPrincipal');
    const errorBox = document.getElementById('errorCompra');

    form.addEventListener('submit', (event) => {
      event.preventDefault();

      if (!form.checkValidity()) {
        form.classList.add('was-validated');
        return;
      }

      if (carrito.length === 0) {
        alert("Tu carrito está vacío.");
        return;
      }

      const datos = {
        carrito: carrito,
        envio: {
          nombre: document.getElementById('nombreEnvio').value,
          direccion: document.getElementById('direccionEnvio').value,
          ciudad: document.getElementById('ciudadEnvio').value,
          cp: document.getElementById('cpEnvio').value,
          telefono: document.getElementById('telefonoEnvio').value
        }
      };

      fetch('procesar_compra.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(datos)
      })
        .then(r => r.json())
        .then(resultado => {
          if (resultado.ok) {
            vaciarCarrito();
            main.innerHTML = `
              <div class="confirmacion">
                <div class="icono-check"><i class="bi bi-check-circle-fill"></i></div>
                <h2>¡Compra realizada con éxito!</h2>
                <p>Gracias por tu compra. Recibirás tu pedido pronto.</p>
                <a href="index.html" class="btn btn-pay px-5">Volver al inicio</a>
              </div>
            `;
          } else {
            errorBox.textContent = resultado.mensaje;
            errorBox.classList.remove('d-none');
          }
        })
        .catch(() => {
          errorBox.textContent = "Hubo un error al procesar tu compra. Intenta de nuevo.";
          errorBox.classList.remove('d-none');
        });
    });
  })();
</script>

</body>
</html>
