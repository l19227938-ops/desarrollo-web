-- Crear base de datos y tabla para el proyecto
CREATE DATABASE IF NOT EXISTS proyecto_etapa3 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE proyecto_etapa3;

CREATE TABLE IF NOT EXISTS contactos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(200) NOT NULL,
  correo VARCHAR(200) NOT NULL,
  telefono VARCHAR(100) DEFAULT NULL,
  mensaje TEXT NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de usuarios para autenticación (registro / login)
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(200) NOT NULL,
  correo VARCHAR(200) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);
