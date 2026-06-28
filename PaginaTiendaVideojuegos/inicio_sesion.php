<?php
session_start();
include("conexion.php");

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = trim($_POST['email']);
    $password = trim($_POST['password']);

    if ($correo && $password) {
        // buscamos al usuario por su correo
        $stmt = $conexion->prepare("SELECT id, nombre, contraseña FROM usuarios WHERE correo = ?");
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $usuario = $resultado->fetch_assoc();

        // password_verify compara la contraseña escrita contra el hash guardado
        if ($usuario && password_verify($password, $usuario["contraseña"])) {
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nombre'] = $usuario['nombre'];
            header("Location: index.php");
            exit();
        } else {
            $mensaje = "❌ Correo o contraseña incorrectos.";
        }
        $stmt->close();
    } else {
        $mensaje = "❌ Todos los campos son obligatorios.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión - Tienda de Videojuegos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f2f5;
        }
        .login-card {
            max-width: 400px;
            margin: 60px auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 2rem;
        }
        .login-card h2 {
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
            <div class="login-card">
                <h2>Iniciar sesión</h2>

                <?php if ($mensaje): ?>
                    <div class="alert alert-danger text-center"><?= $mensaje ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-3">
                        <label for="email" class="form-label">Correo electrónico</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="ejemplo@correo.com" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="******" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Iniciar sesión</button>

                    <p class="text-center mt-3 mb-0">
                        ¿No tienes cuenta? <a href="registro.php">Regístrate aquí</a>
                    </p>
                </form>
            </div>
        </div>
    </main>

    <footer class="bg-dark text-white text-center py-3">
        <p>&copy; 2025 Tienda de Videojuegos. Todos los derechos reservados.</p>
    </footer>

</body>
</html>
