-- ================================================
-- ShinnyHunt — Database Schema completo
-- ================================================

CREATE DATABASE IF NOT EXISTS shinny_hunt CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE shinny_hunt;

-- ─── USUARIOS ─────────────────────────────────────
CREATE TABLE IF NOT EXISTS users (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    username        VARCHAR(50)  NOT NULL UNIQUE,
    email           VARCHAR(100) NOT NULL UNIQUE,
    password        VARCHAR(255) NOT NULL,
    phone           VARCHAR(20)  DEFAULT NULL,
    avatar          VARCHAR(255) DEFAULT NULL,
    bio             TEXT         DEFAULT NULL,
    saldo_acumulado DECIMAL(10,2) DEFAULT 0.00,
    create_in       DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- ─── COLECCIÓN PERSONAL ───────────────────────────
-- Cartas físicas que el usuario registra como suyas
CREATE TABLE IF NOT EXISTS collections (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    user_id     INT          NOT NULL,
    card_id     VARCHAR(50)  NOT NULL,   -- ID de la API (ej: base1-4)
    card_name   VARCHAR(100) NOT NULL,
    image_url   VARCHAR(255) DEFAULT NULL,
    quantity    INT          NOT NULL DEFAULT 1,
    added_at    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_card (user_id, card_id)
);

-- ─── LISTA DE DESEOS ──────────────────────────────
CREATE TABLE IF NOT EXISTS wishlists (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    user_id     INT          NOT NULL,
    card_id     VARCHAR(50)  NOT NULL,
    quantity    INT          NOT NULL,
    card_name   VARCHAR(100) NOT NULL,
    image_url   VARCHAR(255) DEFAULT NULL,
    added_at    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_wish (user_id, card_id)
);

-- ─── LISTINGS (cartas en venta) ───────────────────
-- Un usuario pone una carta a la venta con un precio
CREATE TABLE IF NOT EXISTS listings (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    seller_id   INT            NOT NULL,
    card_id     VARCHAR(50)    NOT NULL,
    card_name   VARCHAR(100)   NOT NULL,
    image_url   VARCHAR(255)   DEFAULT NULL,
    price       DECIMAL(10,2)  NOT NULL,
    quantity    INT            NOT NULL DEFAULT 1,
    `condition` ENUM('mint','near_mint','excellent','good','light_played','played','poor') 
                               NOT NULL DEFAULT 'near_mint',
    description TEXT           DEFAULT NULL,
    status      ENUM('active','sold') 
                               NOT NULL DEFAULT 'active',
    created_at  DATETIME       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at  DATETIME       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (seller_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ─── CARRITO ──────────────────────────────────────
CREATE TABLE IF NOT EXISTS cart_items (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    user_id     INT NOT NULL,
    listing_id  INT NOT NULL,
    quantity    INT NOT NULL DEFAULT 1,
    added_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id)    REFERENCES users(id)    ON DELETE CASCADE,
    FOREIGN KEY (listing_id) REFERENCES listings(id) ON DELETE CASCADE,
    UNIQUE KEY unique_cart_item (user_id, listing_id)
);

-- ─── PEDIDOS ──────────────────────────────────────
CREATE TABLE IF NOT EXISTS orders (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    buyer_id        INT           NOT NULL,
    total           DECIMAL(10,2) NOT NULL,
    status          ENUM('pending','paid','shipped','completed','cancelled')
                                  NOT NULL DEFAULT 'pending',
    payment_method  VARCHAR(50)   DEFAULT NULL,
    payment_ref     VARCHAR(255)  DEFAULT NULL,   -- referencia del pago (Stripe, etc.)
    created_at      DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (buyer_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ─── LÍNEAS DE PEDIDO ─────────────────────────────
CREATE TABLE IF NOT EXISTS order_items (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    order_id    INT           NOT NULL,
    listing_id  INT           NOT NULL,
    seller_id   INT           NOT NULL,
    card_id     VARCHAR(50)   NOT NULL,
    card_name   VARCHAR(100)  NOT NULL,
    image_url   VARCHAR(255)  DEFAULT NULL,
    price       DECIMAL(10,2) NOT NULL,   -- precio en el momento de la compra
    quantity    INT           NOT NULL DEFAULT 1,
    FOREIGN KEY (order_id)   REFERENCES orders(id)   ON DELETE CASCADE,
    FOREIGN KEY (listing_id) REFERENCES listings(id) ON DELETE CASCADE,
    FOREIGN KEY (seller_id)  REFERENCES users(id)    ON DELETE RESTRICT
);

-- ─── RESEÑAS ──────────────────────────────────────
-- El comprador valora al vendedor tras completar un pedido
CREATE TABLE IF NOT EXISTS reviews (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    order_id    INT         NOT NULL,
    reviewer_id INT         NOT NULL,   -- quien escribe la reseña (comprador)
    seller_id   INT         NOT NULL,   -- quien recibe la reseña (vendedor)
    rating      TINYINT     NOT NULL CHECK (rating BETWEEN 1 AND 5),
    comment     TEXT        DEFAULT NULL,
    created_at  DATETIME    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id)    REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (reviewer_id) REFERENCES users(id)  ON DELETE CASCADE,
    FOREIGN KEY (seller_id)   REFERENCES users(id)  ON DELETE CASCADE,
    UNIQUE KEY unique_review (order_id, reviewer_id)  -- solo 1 reseña por pedido
);

-- ─── MENSAJES ─────────────────────────────────────
CREATE TABLE IF NOT EXISTS messages (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    sender_id   INT  NOT NULL,
    receiver_id INT  NOT NULL,
    content     TEXT NOT NULL,
    is_read     TINYINT(1) NOT NULL DEFAULT 0,
    sent_at     DATETIME   NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id)   REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ─── ÍNDICES ──────────────────────────────────────
CREATE INDEX idx_listings_seller   ON listings(seller_id);
CREATE INDEX idx_listings_status   ON listings(status);
CREATE INDEX idx_listings_card     ON listings(card_id);
CREATE INDEX idx_cart_user         ON cart_items(user_id);
CREATE INDEX idx_orders_buyer      ON orders(buyer_id);
CREATE INDEX idx_order_items_order ON order_items(order_id);
CREATE INDEX idx_order_items_seller ON order_items(seller_id);
CREATE INDEX idx_reviews_seller    ON reviews(seller_id);
CREATE INDEX idx_messages_sender   ON messages(sender_id);
CREATE INDEX idx_messages_receiver ON messages(receiver_id);
CREATE INDEX idx_collections_user  ON collections(user_id);
CREATE INDEX idx_wishlists_user    ON wishlists(user_id);
