-- SQL para crear la base de datos y tablas (MySQL)
CREATE DATABASE IF NOT EXISTS `coile_gestion` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `coile_gestion`;

-- Vendedores
CREATE TABLE IF NOT EXISTS vendedores (
  id INT AUTO_INCREMENT PRIMARY KEY,
  codigo VARCHAR(20) NOT NULL UNIQUE,
  nombre VARCHAR(200) NOT NULL
);

-- Inventario
CREATE TABLE IF NOT EXISTS inventario (
  id INT AUTO_INCREMENT PRIMARY KEY,
  producto VARCHAR(255) NOT NULL UNIQUE,
  stock_inicial INT NOT NULL DEFAULT 0,
  entradas INT NOT NULL DEFAULT 0,
  salidas INT NOT NULL DEFAULT 0
);

-- Actas (documentos de entrega)
CREATE TABLE IF NOT EXISTS actas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  numero VARCHAR(20) NOT NULL, -- ACTA-001 ...
  fecha DATE NOT NULL,
  codigo_cliente VARCHAR(100),
  nombre_cliente VARCHAR(255),
  cedula_ruc VARCHAR(50),
  vendedor_codigo VARCHAR(50),
  producto VARCHAR(255),
  cantidad INT NOT NULL DEFAULT 0,
  descripcion TEXT,
  recibido_por VARCHAR(255)
);

-- Índices útiles
CREATE INDEX idx_actas_numero ON actas(numero);
CREATE INDEX idx_actas_fecha ON actas(fecha);
CREATE INDEX idx_actas_vendedor ON actas(vendedor_codigo);

-- Datos iniciales de ejemplo
INSERT INTO vendedores (codigo, nombre) VALUES ('V001','Carlos Pérez'), ('V002','María López');
INSERT INTO inventario (producto, stock_inicial) VALUES ('Arroz 25kg', 100), ('Azúcar 50kg', 50);
