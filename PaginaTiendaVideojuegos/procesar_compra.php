<?php
// este script recibe el carrito + los datos de envio desde pago.php
// y se encarga de: validar stock, guardar el pedido y bajar la cantidad
// disponible de cada producto comprado

session_start();
header('Content-Type: application/json');
include("conexion.php");

// hay que estar logueado para comprar
if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(["ok" => false, "mensaje" => "Debes iniciar sesión para comprar."]);
    exit();
}

$datos = json_decode(file_get_contents("php://input"), true);
$carritoRecibido = $datos['carrito'] ?? [];
$envio = $datos['envio'] ?? [];

if (empty($carritoRecibido)) {
    echo json_encode(["ok" => false, "mensaje" => "El carrito está vacío."]);
    exit();
}

if (empty($envio['nombre']) || empty($envio['direccion']) || empty($envio['ciudad']) || empty($envio['cp']) || empty($envio['telefono'])) {
    echo json_encode(["ok" => false, "mensaje" => "Faltan datos de envío."]);
    exit();
}

// agrupamos el carrito por nombre, asi sabemos cuantas unidades pidieron de cada juego
$resumen = [];
foreach ($carritoRecibido as $item) {
    $nombre = $item['nombre'];
    if (!isset($resumen[$nombre])) {
        $resumen[$nombre] = 0;
    }
    $resumen[$nombre]++;
}

$conexion->begin_transaction();

try {
    $total = 0;
    $detalles = [];

    // primero revisamos que SI haya stock de todo antes de guardar nada
    foreach ($resumen as $nombre => $cantidad) {
        $stmt = $conexion->prepare("SELECT id, precio, stock FROM productos WHERE nombre = ?");
        $stmt->bind_param("s", $nombre);
        $stmt->execute();
        $producto = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$producto) {
            throw new Exception("No se encontró el producto: $nombre");
        }
        if ($producto['stock'] < $cantidad) {
            throw new Exception("Ya no hay suficiente stock de \"$nombre\".");
        }

        $total += $producto['precio'] * $cantidad;
        $detalles[] = [
            "producto_id" => $producto['id'],
            "cantidad" => $cantidad,
            "precio_unitario" => $producto['precio']
        ];
    }

    // creamos el pedido con los datos de envio
    $usuarioId = $_SESSION['usuario_id'];
    $stmt = $conexion->prepare("INSERT INTO pedidos (usuario_id, total, nombre_envio, direccion_envio, ciudad_envio, cp_envio, telefono_envio) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param(
        "idsssss",
        $usuarioId,
        $total,
        $envio['nombre'],
        $envio['direccion'],
        $envio['ciudad'],
        $envio['cp'],
        $envio['telefono']
    );
    $stmt->execute();
    $pedidoId = $conexion->insert_id;
    $stmt->close();

    // guardamos el detalle y bajamos el stock de cada producto
    foreach ($detalles as $d) {
        $stmtDetalle = $conexion->prepare("INSERT INTO detalle_pedido (pedido_id, producto_id, cantidad, precio_unitario) VALUES (?, ?, ?, ?)");
        $stmtDetalle->bind_param("iiid", $pedidoId, $d['producto_id'], $d['cantidad'], $d['precio_unitario']);
        $stmtDetalle->execute();
        $stmtDetalle->close();

        $stmtStock = $conexion->prepare("UPDATE productos SET stock = stock - ? WHERE id = ?");
        $stmtStock->bind_param("ii", $d['cantidad'], $d['producto_id']);
        $stmtStock->execute();
        $stmtStock->close();
    }

    $conexion->commit();
    echo json_encode(["ok" => true, "mensaje" => "Compra realizada con éxito.", "pedido_id" => $pedidoId]);

} catch (Exception $e) {
    $conexion->rollback();
    echo json_encode(["ok" => false, "mensaje" => $e->getMessage()]);
}
?>
