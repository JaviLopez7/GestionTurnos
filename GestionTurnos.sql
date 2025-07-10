-- Creacion de la base de datos 
CREATE DATABASE GestionTurnos;

USE GestionTurnos;

-- Creacion de las tablas
CREATE TABLE pacientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    apellido VARCHAR(50) NOT NULL,
    tipo_documento ENUM('DNI', 'Pasaporte', 'Otro') NOT NULL,
    numero_documento VARCHAR(20) NOT NULL UNIQUE,
    img_dni LONGTEXT NOT NULL, -- Ruta o nombre del archivo
    genero ENUM('Masculino', 'Femenino', 'Otro') NOT NULL,
    fecha_nacimiento DATE NOT NULL,
    domicilio VARCHAR(100) NOT NULL,
    numero_contacto VARCHAR(20) NOT NULL,
    cobertura_salud ENUM('UOM', 'OSDE', 'Swiss Medical', 'Galeno', 'Otra') NOT NULL,
    numero_afiliado VARCHAR(30) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL, -- Se recomienda guardar un hash, no la contraseña en texto
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

