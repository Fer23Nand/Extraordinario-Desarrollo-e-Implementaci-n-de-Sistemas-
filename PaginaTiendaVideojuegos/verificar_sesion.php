<?php
session_start();
header('Content-Type: application/json');
echo json_encode(['sesion' => isset($_SESSION['usuario_id'])]);
