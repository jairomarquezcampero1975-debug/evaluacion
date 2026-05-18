DROP DATABASE IF EXISTS sistema_ventas;
CREATE DATABASE sistema_ventas CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE sistema_ventas;

CREATE TABLE usuario (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    correo VARCHAR(120) NOT NULL UNIQUE,
    contrasena VARCHAR(255) NOT NULL,
    rol ENUM('admin','cliente') NOT NULL DEFAULT 'cliente',
    codigo_2fa VARCHAR(10) DEFAULT NULL,
    estado_2fa TINYINT(1) DEFAULT 0,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE categoria (
    id_categoria INT AUTO_INCREMENT PRIMARY KEY,
    nombre_categoria VARCHAR(100) NOT NULL,
    descripcion TEXT
) ENGINE=InnoDB;

CREATE TABLE producto (
    id_producto INT AUTO_INCREMENT PRIMARY KEY,
    id_categoria INT NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    marca VARCHAR(100),
    descripcion TEXT,
    precio DECIMAL(10,2) NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    imagen VARCHAR(255) DEFAULT NULL,
    estado ENUM('activo','inactivo') NOT NULL DEFAULT 'activo',
    CONSTRAINT fk_producto_categoria FOREIGN KEY (id_categoria) REFERENCES categoria(id_categoria)
) ENGINE=InnoDB;

CREATE TABLE venta (
    id_venta INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    total DECIMAL(10,2) NOT NULL,
    estado_venta ENUM('pendiente','pagado','cancelado') NOT NULL DEFAULT 'pendiente',
    CONSTRAINT fk_venta_usuario FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario)
) ENGINE=InnoDB;

CREATE TABLE detalle_venta (
    id_detalle INT AUTO_INCREMENT PRIMARY KEY,
    id_venta INT NOT NULL,
    id_producto INT NOT NULL,
    cantidad INT NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    CONSTRAINT fk_detalle_venta FOREIGN KEY (id_venta) REFERENCES venta(id_venta),
    CONSTRAINT fk_detalle_producto FOREIGN KEY (id_producto) REFERENCES producto(id_producto)
) ENGINE=InnoDB;

CREATE TABLE favorito (
    id_favorito INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_producto INT NOT NULL,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uk_favorito_usuario_producto (id_usuario, id_producto),
    CONSTRAINT fk_favorito_usuario FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario),
    CONSTRAINT fk_favorito_producto FOREIGN KEY (id_producto) REFERENCES producto(id_producto)
) ENGINE=InnoDB;

CREATE TABLE carrito (
    id_carrito INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    estado ENUM('activo','comprado') NOT NULL DEFAULT 'activo',
    CONSTRAINT fk_carrito_usuario FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario)
) ENGINE=InnoDB;

CREATE TABLE detalle_carrito (
    id_detalle_carrito INT AUTO_INCREMENT PRIMARY KEY,
    id_carrito INT NOT NULL,
    id_producto INT NOT NULL,
    cantidad INT NOT NULL DEFAULT 1,
    subtotal DECIMAL(10,2) NOT NULL,
    UNIQUE KEY uk_carrito_producto (id_carrito, id_producto),
    CONSTRAINT fk_detalle_carrito FOREIGN KEY (id_carrito) REFERENCES carrito(id_carrito),
    CONSTRAINT fk_detalle_carrito_producto FOREIGN KEY (id_producto) REFERENCES producto(id_producto)
) ENGINE=InnoDB;

INSERT INTO usuario (nombre, correo, contrasena, rol, codigo_2fa, estado_2fa) VALUES
('Administrador', 'admin@gmail.com', '$2y$12$N1AnQDs2QF8EEnN37.IhFuXLt/rzu9r33gvIULhz/84jJv/OgEppK', 'admin', NULL, 0),
('Cliente Demo', 'cliente@gmail.com', '$2y$12$1AsDhwWDr7/NycQyIzMaqOF5Jkmn2k0kWxL97mQia.rDSogcsPn5q', 'cliente', NULL, 0);

INSERT INTO categoria (nombre_categoria, descripcion) VALUES
('Celulares', 'Telefonos moviles de diferentes marcas'),
('Accesorios', 'Cargadores, fundas, protectores y cables'),
('Audio', 'Audifonos, parlantes y accesorios de sonido'),
('Smartwatch', 'Relojes inteligentes y accesorios deportivos');

INSERT INTO producto (id_categoria, nombre, marca, descripcion, precio, stock, imagen, estado) VALUES
(1, 'Samsung Galaxy A55', 'Samsung', 'Celular moderno con pantalla AMOLED, buena camara y bateria durable.', 2800.00, 10, NULL, 'activo'),
(1, 'iPhone 13', 'Apple', 'Celular Apple con excelente rendimiento y camara avanzada.', 5200.00, 5, NULL, 'activo'),
(2, 'Cargador Tipo C', 'Xiaomi', 'Cargador rapido compatible con celulares Android.', 90.00, 30, NULL, 'activo'),
(2, 'Funda Transparente', 'Generico', 'Funda resistente para proteger el celular.', 35.00, 40, NULL, 'activo'),
(3, 'Audifonos Bluetooth', 'JBL', 'Audifonos inalambricos con buena calidad de sonido.', 250.00, 15, NULL, 'activo'),
(4, 'Smartwatch T500', 'Generico', 'Reloj inteligente deportivo con funciones basicas.', 180.00, 20, NULL, 'activo');
