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
        estado_2fa TINYINT(1) NOT NULL DEFAULT 0,
        fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB;

    CREATE TABLE categoria (
        id_categoria INT AUTO_INCREMENT PRIMARY KEY,
        nombre_categoria VARCHAR(100) NOT NULL UNIQUE,
        descripcion TEXT
    ) ENGINE=InnoDB;

    CREATE TABLE producto (
        id_producto INT AUTO_INCREMENT PRIMARY KEY,
        id_categoria INT NOT NULL,
        nombre VARCHAR(100) NOT NULL,
        marca VARCHAR(100) NOT NULL,
        descripcion TEXT,
        precio DECIMAL(10,2) NOT NULL,
        stock INT NOT NULL DEFAULT 0,
        imagen VARCHAR(255) DEFAULT NULL,
        estado ENUM('activo','inactivo') NOT NULL DEFAULT 'activo',
        INDEX idx_producto_categoria (id_categoria),
        INDEX idx_producto_marca (marca),
        CONSTRAINT fk_producto_categoria FOREIGN KEY (id_categoria) REFERENCES categoria(id_categoria)
            ON UPDATE CASCADE ON DELETE RESTRICT
    ) ENGINE=InnoDB;

    CREATE TABLE venta (
        id_venta INT AUTO_INCREMENT PRIMARY KEY,
        id_usuario INT NOT NULL,
        fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        total DECIMAL(10,2) NOT NULL,
        estado_venta ENUM('pendiente','pagado','cancelado') NOT NULL DEFAULT 'pendiente',
        INDEX idx_venta_usuario (id_usuario),
        CONSTRAINT fk_venta_usuario FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario)
            ON UPDATE CASCADE ON DELETE RESTRICT
    ) ENGINE=InnoDB;

    CREATE TABLE detalle_venta (
        id_detalle INT AUTO_INCREMENT PRIMARY KEY,
        id_venta INT NOT NULL,
        id_producto INT NOT NULL,
        cantidad INT NOT NULL,
        subtotal DECIMAL(10,2) NOT NULL,
        INDEX idx_detalle_venta (id_venta),
        INDEX idx_detalle_producto (id_producto),
        CONSTRAINT fk_detalle_venta FOREIGN KEY (id_venta) REFERENCES venta(id_venta)
            ON UPDATE CASCADE ON DELETE CASCADE,
        CONSTRAINT fk_detalle_producto FOREIGN KEY (id_producto) REFERENCES producto(id_producto)
            ON UPDATE CASCADE ON DELETE RESTRICT
    ) ENGINE=InnoDB;

    CREATE TABLE favorito (
        id_favorito INT AUTO_INCREMENT PRIMARY KEY,
        id_usuario INT NOT NULL,
        id_producto INT NOT NULL,
        fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY uk_favorito_usuario_producto (id_usuario, id_producto),
        CONSTRAINT fk_favorito_usuario FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario)
            ON UPDATE CASCADE ON DELETE CASCADE,
        CONSTRAINT fk_favorito_producto FOREIGN KEY (id_producto) REFERENCES producto(id_producto)
            ON UPDATE CASCADE ON DELETE CASCADE
    ) ENGINE=InnoDB;

    CREATE TABLE carrito (
        id_carrito INT AUTO_INCREMENT PRIMARY KEY,
        id_usuario INT NOT NULL,
        fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        estado ENUM('activo','comprado') NOT NULL DEFAULT 'activo',
        INDEX idx_carrito_usuario_estado (id_usuario, estado),
        CONSTRAINT fk_carrito_usuario FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario)
            ON UPDATE CASCADE ON DELETE CASCADE
    ) ENGINE=InnoDB;

    CREATE TABLE detalle_carrito (
        id_detalle_carrito INT AUTO_INCREMENT PRIMARY KEY,
        id_carrito INT NOT NULL,
        id_producto INT NOT NULL,
        cantidad INT NOT NULL DEFAULT 1,
        subtotal DECIMAL(10,2) NOT NULL,
        UNIQUE KEY uk_carrito_producto (id_carrito, id_producto),
        CONSTRAINT fk_detalle_carrito FOREIGN KEY (id_carrito) REFERENCES carrito(id_carrito)
            ON UPDATE CASCADE ON DELETE CASCADE,
        CONSTRAINT fk_detalle_carrito_producto FOREIGN KEY (id_producto) REFERENCES producto(id_producto)
            ON UPDATE CASCADE ON DELETE CASCADE
    ) ENGINE=InnoDB;

    INSERT INTO usuario (nombre, correo, contrasena, rol, codigo_2fa, estado_2fa) VALUES
    ('Administrador', 'admin@gmail.com', '$2y$12$2zkupA2/b4kIGaBQ9oT3UevaH1dhVLcoTVd./tSt0l0c5aOnsYv3.', 'admin', NULL, 0),
    ('Cliente Demo', 'cliente@gmail.com', '$2y$12$gjMJgc5DDoVZDYj1gcbZNO3SVvA8BgsZjWfjRYGKnNmvTtYsc4FsC', 'cliente', NULL, 0),
    ('Administrador', 'adminxd67@gmail.com', '$2y$10$w6WkpNjM4bobDqU96gIS3uG.I11326LK0Ro4LmoZ.iXyzZcisdRKq', 'admin', NULL, 0);
    INSERT INTO categoria (nombre_categoria, descripcion) VALUES
    ('Celulares', 'Telefonos moviles de diferentes marcas y gamas.'),
    ('Accesorios', 'Cargadores, fundas, protectores, soportes y cables.'),
    ('Audio', 'Audifonos, parlantes y accesorios de sonido.'),
    ('Smartwatch', 'Relojes inteligentes y accesorios deportivos.'),
    ('Gaming', 'Accesorios para jugadores y dispositivos de alto rendimiento.');

    INSERT INTO producto (id_categoria, nombre, marca, descripcion, precio, stock, imagen, estado) VALUES
    (1, 'Samsung Galaxy A55', 'Samsung', 'Celular moderno con pantalla AMOLED, buena camara y bateria durable.', 2800.00, 10, 'assets/img/productos/galaxy-a55.svg', 'activo'),
    (1, 'iPhone 13', 'Apple', 'Celular Apple con excelente rendimiento y camara avanzada.', 5200.00, 5, 'assets/img/productos/iphone-13.svg', 'activo'),
    (1, 'Redmi Note 13', 'Xiaomi', 'Celular equilibrado para estudio, redes sociales y juegos casuales.', 1850.00, 14, 'assets/img/productos/redmi-note-13.svg', 'activo'),
    (2, 'Cargador Tipo C 33W', 'Xiaomi', 'Cargador rapido compatible con celulares Android.', 90.00, 30, 'assets/img/productos/cargador-33w.svg', 'activo'),
    (2, 'Funda Transparente Antigolpes', 'Generico', 'Funda resistente para proteger el celular.', 35.00, 40, 'assets/img/productos/funda-antigolpes.svg', 'activo'),
    (3, 'Audifonos Bluetooth Tune', 'JBL', 'Audifonos inalambricos con buena calidad de sonido.', 250.00, 15, 'assets/img/productos/audifonos-jbl.svg', 'activo'),
    (4, 'Smartwatch T500', 'Generico', 'Reloj inteligente deportivo con funciones basicas.', 180.00, 20, 'assets/img/productos/smartwatch-t500.svg', 'activo'),
    (5, 'Control Bluetooth Pro', 'GameTech', 'Control inalambrico compatible con PC y celular.', 210.00, 8, 'assets/img/productos/control-pro.svg', 'activo');
