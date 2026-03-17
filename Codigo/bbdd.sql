-- ============================================================
--  E-COMMERCE CARTAS POKÉMON — Script MySQL
--  Los datos de la carta (nombre, rareza, set, imagen, etc.)
--  se obtienen en runtime desde https://api.tcgdex.net/v2/es/cards
--  La BD solo guarda el tcgdex_card_id como referencia externa.
-- ============================================================

DROP DATABASE IF EXISTS tcg_market; -- Corregido el orden

CREATE DATABASE IF NOT EXISTS tcg_market
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE tcg_market;

-- Cambiado de 'user' a 'users' para ser consistente con el script
CREATE TABLE users (
  id            INT          NOT NULL AUTO_INCREMENT,
  username      VARCHAR(100) NOT NULL, -- Antes era 'nombre' en el INSERT
  email         VARCHAR(150) NOT NULL UNIQUE,
  password      VARCHAR(255) NOT NULL, -- Cambiado de password_hash a password para tu PHP
  phone         VARCHAR(20),           -- Cambiado de telefono a phone para tu PHP
  create_in     TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP, -- Cambiado para tu PHP
  PRIMARY KEY (id)
) ENGINE=InnoDB;

-- DIRECCIONES
CREATE TABLE direcciones (
  id            INT          NOT NULL AUTO_INCREMENT,
  usuario_id    INT          NOT NULL,
  calle         VARCHAR(200) NOT NULL,
  ciudad        VARCHAR(100) NOT NULL,
  pais          VARCHAR(100) NOT NULL DEFAULT 'España',
  codigo_postal VARCHAR(10)  NOT NULL,
  PRIMARY KEY (id),
  CONSTRAINT fk_dir_usuario FOREIGN KEY (usuario_id)
    REFERENCES users (id) -- Corregido: Ahora coincide con el nombre de la tabla
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB;

-- PRODUCTOS
CREATE TABLE productos (
  id             INT            NOT NULL AUTO_INCREMENT,
  tcgdex_card_id VARCHAR(50)    NOT NULL UNIQUE,
  condicion      ENUM('nueva','casi_nueva','excelente','buena','jugada') NOT NULL DEFAULT 'nueva',
  precio         DECIMAL(10, 2) NOT NULL CHECK (precio >= 0),
  stock          INT            NOT NULL DEFAULT 0 CHECK (stock >= 0),
  activo         TINYINT(1)     NOT NULL DEFAULT 1,
  creado_en      TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB;

-- PEDIDOS
CREATE TABLE pedidos (
  id            INT            NOT NULL AUTO_INCREMENT,
  usuario_id    INT            NOT NULL,
  direccion_id  INT,
  estado        ENUM('pendiente','pagado','enviado','entregado','cancelado') NOT NULL DEFAULT 'pendiente',
  total         DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
  creado_en     TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  CONSTRAINT fk_ped_usuario   FOREIGN KEY (usuario_id)
    REFERENCES users (id) -- Corregido referencia
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT fk_ped_direccion FOREIGN KEY (direccion_id)
    REFERENCES direcciones (id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ... (El resto de las tablas siguen igual, solo asegúrate que las FK apunten a "users")
-- ------------------------------------------------------------
-- DETALLE_PEDIDO
-- ------------------------------------------------------------
CREATE TABLE detalle_pedido (
  id              INT            NOT NULL AUTO_INCREMENT,
  pedido_id       INT            NOT NULL,
  producto_id     INT            NOT NULL,
  cantidad        INT            NOT NULL CHECK (cantidad > 0),
  precio_unitario DECIMAL(10, 2) NOT NULL CHECK (precio_unitario >= 0),
  PRIMARY KEY (id),
  CONSTRAINT fk_det_pedido   FOREIGN KEY (pedido_id)
    REFERENCES pedidos (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT fk_det_producto FOREIGN KEY (producto_id)
    REFERENCES productos (id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- PAGOS
-- ------------------------------------------------------------
CREATE TABLE pagos (
  id        INT            NOT NULL AUTO_INCREMENT,
  pedido_id INT            NOT NULL UNIQUE,
  metodo    ENUM(
              'tarjeta',
              'transferencia',
              'paypal',
              'contra_reembolso'
            )              NOT NULL,
  estado    ENUM(
              'pendiente',
              'completado',
              'fallido',
              'reembolsado'
            )              NOT NULL DEFAULT 'pendiente',
  monto     DECIMAL(10, 2) NOT NULL,
  fecha     TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  CONSTRAINT fk_pag_pedido FOREIGN KEY (pedido_id)
    REFERENCES pedidos (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- RESENAS
-- Una reseña por usuario y carta (tcgdex_card_id)
-- No se referencia productos.id porque una misma carta puede
-- tener varias condiciones/precios distintos en el catálogo.
-- ------------------------------------------------------------
CREATE TABLE resenas (
  id             INT       NOT NULL AUTO_INCREMENT,
  usuario_id     INT       NOT NULL,
  tcgdex_card_id VARCHAR(50) NOT NULL,             -- misma clave que en productos
  puntuacion     TINYINT   NOT NULL CHECK (puntuacion BETWEEN 1 AND 5),
  comentario     TEXT,
  creado_en      TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_resena (usuario_id, tcgdex_card_id),
  CONSTRAINT fk_res_usuario FOREIGN KEY (usuario_id)
    REFERENCES users (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ============================================================
-- ÍNDICES ADICIONALES
-- ============================================================
CREATE INDEX idx_productos_condicion  ON productos      (condicion);
CREATE INDEX idx_productos_activo     ON productos      (activo);
CREATE INDEX idx_pedidos_usuario      ON pedidos        (usuario_id);
CREATE INDEX idx_pedidos_estado       ON pedidos        (estado);
CREATE INDEX idx_detalle_pedido       ON detalle_pedido (pedido_id);
CREATE INDEX idx_resenas_card         ON resenas        (tcgdex_card_id);

-- ============================================================
-- DATOS DE EJEMPLO
-- IDs reales de TCGDex (verificables en https://api.tcgdex.net/v2/es/cards)
-- ============================================================

-- Datos de ejemplo corregidos para la tabla 'users'
INSERT INTO users (username, email, password, phone) VALUES
  ('Ana García',    'ana@ejemplo.com',  '$2y$10$nOUIs5...', '+34600111222'),
  ('Luis Martínez', 'luis@ejemplo.com', '$2y$10$nOUIs5...', '+34600333444'),
  ('prueba', 'prueba@ejemplo.com', 'prueba', '+34999999999');

-- Direcciones (Se mantiene igual, solo asegura que la tabla se llame 'direcciones')
INSERT INTO direcciones (usuario_id, calle, ciudad, pais, codigo_postal) VALUES
  (1, 'Calle Mayor 10', 'Madrid',    'España', '28001'),
  (2, 'Av. Diagonal 5', 'Barcelona', 'España', '08001');

-- Productos (Se mantiene igual)
INSERT INTO productos (tcgdex_card_id, condicion, precio, stock) VALUES
  ('sv01-006',     'nueva',       89.99,  5),
  ('swsh4-44',     'casi_nueva', 45.00, 10),
  ('swsh12pt5-30','nueva',     130.00,  2),
  ('sv01-001',     'nueva',       1.50, 80),
  ('base1-4',      'buena',     250.00,  1);

-- Pedidos
INSERT INTO pedidos (usuario_id, direccion_id, estado, total) VALUES
  (1, 1,    'pagado',    179.98),
  (2, NULL, 'pendiente',  45.00);

-- Detalle de Pedido
INSERT INTO detalle_pedido (pedido_id, producto_id, cantidad, precio_unitario) VALUES
  (1, 1, 1,  89.99),
  (1, 4, 60,  1.50),
  (2, 2, 1,  45.00);

-- Pagos
INSERT INTO pagos (pedido_id, metodo, estado, monto) VALUES
  (1, 'paypal', 'completado', 179.98);

-- Reseñas
INSERT INTO resenas (usuario_id, tcgdex_card_id, puntuacion, comentario) VALUES
  (1, 'sv01-006',  5, 'Carta impecable, llegó perfectamente embalada.'),
  (2, 'swsh4-44',  4, 'Muy buen estado, tal como se describía.');