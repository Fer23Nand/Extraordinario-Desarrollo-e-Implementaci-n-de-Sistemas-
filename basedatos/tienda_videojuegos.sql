-- ============================================
-- Script de creacion de la base de datos
-- Tienda de Videojuegos
-- ============================================

CREATE DATABASE IF NOT EXISTS tienda_videojuegos CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci;
USE tienda_videojuegos;

-- tabla de usuarios (los clientes que se registran y hacen login)
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    correo VARCHAR(100) NOT NULL UNIQUE,
    contraseña VARCHAR(255) NOT NULL,
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- tabla de categorias (Accion y Aventura, RPG, JRPG, Shooter, Peleas, Ofertas)
CREATE TABLE categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL
);

-- tabla de productos (los juegos que se venden, con su stock)
CREATE TABLE productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    categoria_id INT,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id)
);

-- tabla de pedidos (una compra que hace un cliente, con sus datos de envio)
CREATE TABLE pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    total DECIMAL(10,2) NOT NULL,
    nombre_envio VARCHAR(100) NOT NULL,
    direccion_envio VARCHAR(150) NOT NULL,
    ciudad_envio VARCHAR(80) NOT NULL,
    cp_envio VARCHAR(10) NOT NULL,
    telefono_envio VARCHAR(20) NOT NULL,
    estado VARCHAR(20) DEFAULT 'pendiente',
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

-- tabla de detalle_pedido (que productos y cuantos lleva cada pedido)
CREATE TABLE detalle_pedido (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT NOT NULL,
    producto_id INT NOT NULL,
    cantidad INT NOT NULL,
    precio_unitario DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id),
    FOREIGN KEY (producto_id) REFERENCES productos(id)
);

-- ============================================
-- Datos iniciales (seed)
-- ============================================

INSERT INTO categorias (nombre) VALUES
('Accion y Aventura'),
('RPG'),
('JRPG'),
('Shooter'),
('Peleas'),
('Ofertas');

-- el nombre de cada producto debe ser IDENTICO al que usan los botones
-- "Agregar al carrito" en las paginas html, porque procesar_compra.php
-- busca el producto en la base de datos por su nombre
INSERT INTO productos (nombre, precio, stock, categoria_id) VALUES
('Zelda: Tears of the Kingdom', 69.99, 15, 1),
('Red Dead Redemption 2', 59.99, 20, 1),
('The Last of Us Parte II Remastered', 49.99, 18, 1),
('God Of War:Ragnarok', 69.99, 12, 1),
('Spider-Man 2', 69.99, 10, 1),
('Skyrim', 39.99, 25, 2),
('Nioh', 49.99, 14, 2),
('Baldur''s Gate 3', 69.99, 16, 2),
('Final Fantasy VII Remake', 59.99, 13, 3),
('Persona 3 Reload', 49.99, 17, 3),
('Metaphor: ReFantazio', 59.99, 11, 3),
('Black Ops 6', 69.99, 30, 4),
('Crysis 3 Remastered', 29.99, 22, 4),
('Rainbow Six Siege', 29.99, 28, 4),
('The King of Fighters XV', 49.99, 19, 5),
('Mortal Kombat 1', 59.99, 21, 5),
('Street Fighter 6', 59.99, 24, 5),
('Yakuza 0', 14.99, 30, 6),
('The Legend of Zelda: Ocarina of Time', 29.99, 20, 6),
('Dragon Ball FighterZ', 9.99, 35, 6),
('Red Dead Redemption 1', 19.99, 26, 6);
