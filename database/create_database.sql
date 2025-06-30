-- Script de création de la base de données pour l'application To-Do List

-- Création de la base de données (si elle n'existe pas)
CREATE DATABASE IF NOT EXISTS todo_app CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Utilisation de la base de données
USE todo_app;

-- Création de la table des tâches
CREATE TABLE IF NOT EXISTS tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    is_done TINYINT(1) DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertion de quelques tâches d'exemple (optionnel)
INSERT INTO tasks (title, is_done) VALUES 
('Apprendre Bootstrap 5', 0),
('Maîtriser jQuery et AJAX', 0),
('Créer une API REST en PHP', 1),
('Sécuriser l\'application contre XSS', 0),
('Ajouter des thèmes personnalisés', 1);

-- Affichage des tâches créées
SELECT * FROM tasks;
