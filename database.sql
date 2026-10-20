CREATE DATABASE IF NOT EXISTS comercial_pro CHARACTER SET utf8mb4;
USE comercial_pro;

-- Roles
CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(30) UNIQUE NOT NULL,
    label VARCHAR(50) NOT NULL
);
INSERT INTO roles (name, label) VALUES
('admin','Administrador'),
('vendedor','Vendedor'),
('almacen','Almacén'),
('atencion','Atención al Cliente');

-- Usuarios
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(120) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role_id INT NOT NULL,
    active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES roles(id)
);

-- Categorías de productos
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(80) NOT NULL,
    icon VARCHAR(10) DEFAULT '📦'
);
INSERT INTO categories (name, icon) VALUES
('Ropa','👕'),('Alimentos','🍎'),('Hogar','🏠'),
('Electrónica','📱'),('Belleza','💄'),('Deportes','⚽');

-- Productos
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(30) UNIQUE NOT NULL,
    name VARCHAR(150) NOT NULL,
    category_id INT NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    cost DECIMAL(10,2) DEFAULT 0,
    stock INT DEFAULT 0,
    min_stock INT DEFAULT 5,
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

-- Movimientos de inventario
CREATE TABLE stock_movements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    user_id INT NOT NULL,
    type ENUM('entrada','salida','ajuste') NOT NULL,
    quantity INT NOT NULL,
    reason VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Clientes
CREATE TABLE customers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    document VARCHAR(30),
    phone VARCHAR(20),
    email VARCHAR(120),
    address TEXT,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Ventas
CREATE TABLE sales (
    id INT AUTO_INCREMENT PRIMARY KEY,
    invoice VARCHAR(20) UNIQUE NOT NULL,
    customer_id INT,
    user_id INT NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    tax DECIMAL(10,2) DEFAULT 0,
    total DECIMAL(10,2) NOT NULL,
    payment_method ENUM('efectivo','tarjeta','transferencia') DEFAULT 'efectivo',
    status ENUM('completada','anulada') DEFAULT 'completada',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customers(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE sale_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sale_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (sale_id) REFERENCES sales(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id)
);

-- Tickets de atención al cliente
CREATE TABLE tickets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(20) UNIQUE NOT NULL,
    customer_id INT,
    customer_name VARCHAR(120) NOT NULL,
    subject VARCHAR(200) NOT NULL,
    priority ENUM('baja','media','alta') DEFAULT 'media',
    status ENUM('abierto','en_proceso','cerrado') DEFAULT 'abierto',
    assigned_to INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customers(id),
    FOREIGN KEY (assigned_to) REFERENCES users(id)
);

CREATE TABLE ticket_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ticket_id INT NOT NULL,
    user_id INT,
    message TEXT NOT NULL,
    is_client TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ticket_id) REFERENCES tickets(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Usuario admin por defecto (contraseña: admin123)
INSERT INTO users (name, email, password, role_id) VALUES
('Administrador','admin@comercial.com','$2y$10$e0NRb1y9qZ5XKvzp6Zq8y.XqYqJqZqJqZqJqZqJqZqJqZqJqZqJqO',1);
-- ⚠️ Reemplaza el hash ejecutando en PHP: echo password_hash('admin123', PASSWORD_DEFAULT);