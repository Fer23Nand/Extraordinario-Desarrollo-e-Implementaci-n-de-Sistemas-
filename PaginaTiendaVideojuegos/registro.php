<?php
include("conexion.php");

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST['nombre']);
    $correo = trim($_POST['correo']);
    $contrasena = $_POST['contrasena'];

    // Validaciones básicas
    if (empty($nombre) || empty($correo) || empty($contrasena)) {
        $mensaje = "Todos los campos son obligatorios.";
    } else {
        // Verificar si el correo ya está registrado
        $stmt = $conexion->prepare("SELECT id FROM usuarios WHERE correo = ?");
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $mensaje = "❌ El correo ya está registrado.";
        } else {
            // Guardar usuario
            $hash = password_hash($contrasena, PASSWORD_DEFAULT);
            $stmt = $conexion->prepare("INSERT INTO usuarios (nombre, correo, contraseña) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $nombre, $correo, $hash);

            if ($stmt->execute()) {
                $mensaje = "✅ Cuenta registrada con éxito.";
            } else {
                $mensaje = "❌ Error al registrar la cuenta.";
            }
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear cuenta - Tienda de Videojuegos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f2f5;
        }
        .registro-card {
            max-width: 400px;
            margin: 60px auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 2rem;
        }
        .registro-card h2 {
            text-align: center;
            margin-bottom: 1.5rem;
            color: #343a40;
        }
    </style>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>

    <header class="bg-dark text-white p-3">
        <div class="container d-flex flex-wrap justify-content-between align-items-center">
            <div class="logo d-flex align-items-center">
                <a href="index.html"><img src="imagenes/logo.png" alt="Tienda de Videojuegos" class="img-fluid" style="max-width: 50px;"></a>
            </div>
            <nav>
                <ul class="nav">
                    <li class="nav-item"><a class="nav-link text-white" href="index.html">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="pagina02.html">Catálogo</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="ofertas.html">Ofertas</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="blog.html">Blog</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="noticias.html">Noticias</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="reseñas.html">Reseñas</a></li>
                    <li class="nav-item"><a class="nav-link text-white fw-bold" href="inicio_sesion.php">Iniciar sesión</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <div class="container">
            <div class="registro-card">
                <h2>Crear cuenta</h2>

                <?php if ($mensaje): ?>
                    <div class="alert <?= strpos($mensaje, '✅') === 0 ? 'alert-success' : 'alert-danger' ?> text-center"><?= $mensaje ?></div>
                <?php endif; ?>

                <?php if (strpos($mensaje, '✅') === 0): ?>
                    <a href="inicio_sesion.php" class="btn btn-success w-100 mb-2">Iniciar sesión</a>
                    <a href="index.html" class="btn btn-secondary w-100">Volver al inicio</a>
                <?php else: ?>
                    <form method="POST">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Tu nombre completo" required>
                        </div>
                        <div class="mb-3">
                            <label for="correo" class="form-label">Correo electrónico</label>
                            <input type="email" class="form-control" id="correo" name="correo" placeholder="ejemplo@correo.com" required>
                        </div>
                        <div class="mb-3">
                            <label for="contrasena" class="form-label">Contraseña</label>
                            <input type="password" class="form-control" id="contrasena" name="contrasena" placeholder="******" minlength="6" required>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Crear cuenta</button>

                        <p class="text-center mt-3 mb-0">
                            ¿Ya tienes cuenta? <a href="inicio_sesion.php">Inicia sesión aquí</a>
                        </p>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <footer class="bg-dark text-white text-center py-3">
        <p>&copy; 2025 Tienda de Videojuegos. Todos los derechos reservados.</p>
    </footer>

</body>
</html>
